<?php

// read EXIF-Data
function fb_read_write_exif_data($post_ID, $debug = FALSE) {
			
			if ( !isset($post_ID) || '' == $post_ID )
				return FALSE;
			
			if ( 0 == ini_get( 'allow_url_fopen' ) )
				return FALSE;
			
			$return = '';
			$image  = '';
			$exif   = '';
			
			if ( function_exists( 'fb_meta_image' ) )
				$image = fb_meta_image();
			if ( '' != $image ) {
				if ( strpos( $image, 'http://' ) )
					$image = clean_url($image);
				else
					$image = WP_CONTENT_URL . '/uploads/' . esc_attr($image);
			} elseif ( function_exists( 'fb_getTeaserPics' ) ) {
				$image = fb_getTeaserPics($post_ID, 'src' );
			}
			
			if ( '' != $image ) {
				//error_reporting(0);
				if ( IMAGETYPE_GIF == @exif_imagetype($image) )
					$type = TRUE;
				elseif ( IMAGETYPE_JPEG == @exif_imagetype($image) )
					$type = TRUE;
				else
					$type = FALSE;
					
				if ($type)
					$exif = @exif_read_data($image, 0, TRUE);
				else
					$return = '<!--'. __( 'Falscher Datentyp (gif, jpeg)', FB_GREYFOTO_TEXTDOMAIN ) . '-->';
				
				if ( isset($exif["EXIF"]["DateTimeOriginal"]) ) {
					$fbdateoriginal = str_replace(":","-",substr($exif["EXIF"]["DateTimeOriginal"], 0, 10));
					$fbtimeoriginal = substr($exif["EXIF"]["DateTimeOriginal"], 10);
					$return .= __( 'Datum:', FB_GREYFOTO_TEXTDOMAIN ) . " {$fbdateoriginal}";
					$return .= ' ' . __( '&middot; Uhrzeit:', FB_GREYFOTO_TEXTDOMAIN ) . " {$fbtimeoriginal}";
					$return .= "<br />\n";
				}
				
				if ( isset($exif["EXIF"]["FNumber"]) ) {
					list($num, $den) = explode("/",$exif["EXIF"]["FNumber"]);
					$fbaperture  = "F/" . ($num/$den);
					$return .= __( 'Blende:', FB_GREYFOTO_TEXTDOMAIN ) . " {$fbaperture}";;
				}
				
				if ( isset($exif["EXIF"]["ExposureTime"]) ) {
					$values = split("/", $exif["EXIF"]["ExposureTime"]);
					if ( 2 == count($values) ) {
						if ( $values[1] == 0)
							$fbexposure = round($values[0] . $values[1],2);
						else {
							$fbexposure = round($values[0] / $values[1],2);
							if ( $fbexposure < 1) $fbexposure = '1/' . round($values[1] / $values[0],0);
						}
					} else {
						$fbexposure = round($values[0] / $values[1], 2);
					}
					$return .= ' ' . __( '&middot; Belichtungsdauer:', FB_GREYFOTO_TEXTDOMAIN ) . " {$fbexposure}";
				}
				
				if ( isset($exif["EXIF"]["ExposureBiasValue"]) ) {
					list($num,$den) = explode("/",$exif["EXIF"]["ExposureBiasValue"]);
					$exposurebias  = ($num/$den);
					$exposurebias = number_format($exposurebias, 1, '.', '' );
					$return .= ' ' . __( '&middot; Belichtungswert:', FB_GREYFOTO_TEXTDOMAIN ) . "{$exposurebias}" . ( ' EV' ) . ( '' );
				}
				
				if ( isset($exif["EXIF"]["FocalLength"]) ) {
					list($num, $den) = explode("/", $exif["EXIF"]["FocalLength"]);
					$fbfocallength  = ($num/$den) . __( 'mm', FB_GREYFOTO_TEXTDOMAIN );
					$return .= ' ' . __( '&middot; Brennweite:', FB_GREYFOTO_TEXTDOMAIN ) . ' ' . $fbfocallength;
				}
				
				if ( isset($exif["EXIF"]["FocalLengthIn35mmFilm"]) ) {
					$fbfbfocallength35 = $exif["EXIF"]["FocalLengthIn35mmFilm"];
					$return .= __( ', (KB-Format entsprechend:', FB_GREYFOTO_TEXTDOMAIN ) . " {$fbfbfocallength35}" . __( 'mm)' );
				}
				
				$return .= "<br />\n";
				
				if ( isset($exif['EXIF']['ISOSpeedRatings']) ) {
					$return .= __( 'ISO:', FB_GREYFOTO_TEXTDOMAIN ) . ' ' . $exif['EXIF']['ISOSpeedRatings'];
				}
				
				if ( isset($exif["EXIF"]["WhiteBalance"]) ) {
					switch($exif["EXIF"]["WhiteBalance"]) {
						case 0:
							$fbwhitebalance = __( 'Auto', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 1:
							$fbwhitebalance = __( 'Manuel', FB_GREYFOTO_TEXTDOMAIN );
							break;
					}
					$return .= ' ' . __( '&middot; Wei&szlig;abgleich:', FB_GREYFOTO_TEXTDOMAIN ) . ' ' . $fbwhitebalance;
				}
				
				if ( isset($exif["EXIF"]["LightSource"]) ) {
					switch($exif["EXIF"]["LightSource"]) {
						case 0:
							$fblightsource = __( 'Auto', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 1:
							$fblightsource = __( 'Daylight', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 2:
							$fblightsource = __( 'Fluorescent', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 3:
							$fblightsource = __( 'Tungsten (incandescent light)', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 4:
							$fblightsource = __( 'Flash', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 9:
							$fblightsource = __( 'Fine weather', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 10:
							$fblightsource = __( 'Cloudy weather', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 11:
							$fblightsource = __( 'Shade', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 12:
							$fblightsource = __( 'Daylight fluorescent (D 5700 – 7100K)', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 13:
							$fblightsource = __( 'Day white fluorescent (N 4600 – 5400K)', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 14:
							$fblightsource = __( 'Cool white fluorescent (W 3900 – 4500K)', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 15:
							$fblightsource = __( 'White fluorescent (WW 3200 – 3700K)', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 17:
							$fblightsource = __( 'Standard light A', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 18:
							$fblightsource = __( 'Standard light B', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 19:
							$fblightsource = __( 'Standard light C', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 20:
							$fblightsource = __( 'D55', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 21:
							$fblightsource = __( 'D65', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 22:
							$fblightsource = __( 'D75', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 23:
							$fblightsource = __( 'D50', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 24:
							$fblightsource = __( 'ISO studio tungsten', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 255:
							$fblightsource = __( 'other light source', FB_GREYFOTO_TEXTDOMAIN );
							break;
						default:
							$fblightsource = '';
							break;
					}
					$return .= ' ' . __( '&middot; Licht&ndash;Verh&auml;ltnisse:', FB_GREYFOTO_TEXTDOMAIN ) . ' ' . $fblightsource;
				}
				
				if (isset($exif["EXIF"]["Flash"]) ) {
					switch($exif["EXIF"]["Flash"]) {
						case 0:
							$fbexif_flash = __( 'Flash did not fire', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 1:
							$fbexif_flash = __( 'Flash fired', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 5:
							$fbexif_flash = __( 'Strobe return light not detected', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 7:
							$fbexif_flash = __( 'Strobe return light detected', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 9:
							$fbexif_flash = __( 'Flash fired, compulsory flash mode', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 13:
							$fbexif_flash = __( 'Flash fired, compulsory flash mode, return light not detected', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 15:
							$fbexif_flash = __( 'Flash fired, compulsory flash mode, return light detected', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 16:
							$fbexif_flash = __( 'Flash did not fire, compulsory flash mode', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 24:
							$fbexif_flash = __( 'Flash did not fire, auto mode', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 25:
							$fbexif_flash = __( 'Flash fired, auto mode', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 29:
							$fbexif_flash = __( 'Flash fired, auto mode, return light not detected', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 31:
							$fbexif_flash = __( 'Flash fired, auto mode, return light detected', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 32:
							$fbexif_flash = __( 'No flash function', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 65:
							$fbexif_flash = __( 'Flash fired, red-eye reduction mode', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 69:
							$fbexif_flash = __( 'Flash fired, red-eye reduction mode, return light not detected', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 71:
							$fbexif_flash = __( 'Flash fired, red-eye reduction mode, return light detected', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 73:
							$fbexif_flash = __( 'Flash fired, compulsory flash mode, red-eye reduction mode', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 77:
							$fbexif_flash = __( 'Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 79:
							$fbexif_flash = __( 'Flash fired, compulsory flash mode, red-eye reduction mode, return light detected', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 89:
							$fbexif_flash = __( 'Flash fired, auto mode, red-eye reduction mode', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 93:
							$fbexif_flash = __( 'Flash fired, auto mode, return light not detected, red-eye reduction mode', FB_GREYFOTO_TEXTDOMAIN );
							break;
						case 95:
							$fbexif_flash = __( 'Flash fired, auto mode, return light detected, red-eye reduction mode', FB_GREYFOTO_TEXTDOMAIN );
							break;
						default:
							$fbexif_flash = '';
							break;
						}
						$return .= ' ' . __( '&middot; Blitz:', FB_GREYFOTO_TEXTDOMAIN ) . " {$fbexif_flash}";
				}
				
				/**
				if (isset($exif["EXIF"]["Flash"])) {
					$fbflash = (bindec($exif["EXIF"]["Flash"]) ? "On" : "Off");
					$return .= ' ' . __( '&middot; Blitz:', FB_GREYFOTO_TEXTDOMAIN ) . " {$fbflash}";
				}
				*/
				
					$return .= "<br />\n";
				
				if (isset($exif["IFD0"]["Make"]) && isset($exif["IFD0"]["Model"])) {
					$fbmake = ucwords(strtolower($exif["IFD0"]["Make"]));
					$fbmodel = ucwords($exif["IFD0"]["Model"]);
					$return .= __( 'Kamera o. DIA-Scanner:', FB_GREYFOTO_TEXTDOMAIN ) . " {$fbmake}";
					$return .= __( ',', FB_GREYFOTO_TEXTDOMAIN ) . " {$fbmodel}";
				}
				
				/* Alle EXIF-Daten untereinander ausgeben */
				if ($debug) {
					$return = __( 'Debug-Mode !!!', FB_GREYFOTO_TEXTDOMAIN ) . '<br />';
					foreach ($exif as $key => $section) {
						foreach ($section as $name => $val) {
							$return .= "$key.$name: $val<br />\n";
						}
					}
				}
				
				return $return;
			}
}

function fb_exif($post_ID, $debug) {
	
	$echo = fb_read_write_exif_data($post_ID, $debug);
	
	if ( $echo && '' != $echo )
		echo $echo;
}

?>
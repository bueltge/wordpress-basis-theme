<?php

// grab exif data from wp attachment
function grab_exif_data_from_wp($post_ID, $debug = FALSE) {
	global $id, $post;
	
	if ( !isset($post_ID) || '' == $post_ID )
		return FALSE;
	
	$meta = wp_get_attachment_metadata($post_ID, FALSE);
	
	$return = '';
	
	if ( $meta['image_meta']['created_timestamp'] )
		$return .= date( "d-M-Y H:i:s", $meta['image_meta']['created_timestamp'] );
	if ( $meta['image_meta']['copyright'] )
		$return .= $meta['image_meta']['copyright'];
	if ( $meta['image_meta']['credit'] )
		$return .= $meta['image_meta']['credit'];
	if ( $meta['image_meta']['title'] )
		$return .= $meta['image_meta']['title'];
	if ( $meta['image_meta']['caption'] )
		$return .= $meta['image_meta']['caption'];
	if ( $meta['image_meta']['camera'] )
		$return .= $meta['image_meta']['camera'];
	if ( $meta['image_meta']['focal_length'] )
		$return .= __( '&middot; Brennweite:', FB_GREYFOTO_TEXTDOMAIN )
		           . ' ' . $meta['image_meta']['focal_length'] 
		           . __( 'mm', FB_GREYFOTO_TEXTDOMAIN );
	if ( $meta['image_meta']['aperture'] )
		$return .= $meta['image_meta']['aperture'];
	if ( $meta['image_meta']['iso'] )
		$return .= $meta['image_meta']['iso'];
	if ( $meta['image_meta']['shutter_speed'] )
		$return .= number_format($meta['image_meta']['shutter_speed'], 2) . ' ' . __( 'seconds', FB_GREYFOTO_TEXTDOMAIN );
	
	if ($debug) {
		ob_start();
		var_dump($meta);
		$return = ob_get_clean();
	}
	
	return $return;
}

function fb_simple_exif($post_ID, $debug) {
	
	$echo = grab_exif_data_from_wp($post_ID, $debug);
	
	if ( $echo && '' != $echo )
		echo $echo;
}

?>
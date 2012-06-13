<?php
/**
 * @package WordPress
 * @subpackage WP-Basis Theme
 * @template class template
 * @since 0.0.1
 */

class Wp_Basis_Exif {
	
	public static function get_text_domain() {
		
		return Wp_Basis_Core :: get_text_domain();
	}
	
	// grab exif data from wp attachment
	public static function get_exif_data_with_wp( $post_if = NULL, $debug = FALSE ) {
		global $id, $post;
		
		if ( ! isset($post_id) )
			return FALSE;
		
		$meta = wp_get_attachment_metadata($post_id, FALSE);
		
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
			$return .= __( '&middot; Brennweite:', self :: get_text_domain() )
				. ' ' . $meta['image_meta']['focal_length'] 
				. __( 'mm', self :: get_text_domain() );
		if ( $meta['image_meta']['aperture'] )
			$return .= $meta['image_meta']['aperture'];
		if ( $meta['image_meta']['iso'] )
			$return .= $meta['image_meta']['iso'];
		if ( $meta['image_meta']['shutter_speed'] )
			$return .= number_format($meta['image_meta']['shutter_speed'], 2) . 
				' ' . __( 'seconds', self :: get_text_domain() );
		
		if ($debug) {
			ob_start();
			var_dump($meta);
			$return = ob_get_clean();
		}
		
		return $return;
	}
	
	public static function get_flattened_array( $array= array(), $keyname = FALSE ) {
		// Flatten an array to a twodimensional array
		$tmp = array();
	
		foreach ( $array as $key => $value ) {
			if ( is_array($value) )
				$tmp = array_merge($tmp, self :: get_flattened_array( $value, $key ) );
			else
				$tmp[$key . $keyname] = $value;
		}
		return $tmp;
	}
	
	public static function get_exif_data( $path = FALSE, $verbose = FALSE, $args = array() ) {
		
		$defaults = array(
			'flatten'          => TRUE,
			'readable'         => TRUE,
			'key'              => '-1',
			'replace_string'   => FALSE,
			'after_replace_string' => ': '
		);
		
		$args = wp_parse_args( $args, apply_filters( 'wp_basis_get_exif', $defaults ) );
		
		locate_template( array( 'inc/exifer/exif.php' ), TRUE, TRUE );
		$return = NULL;
		$result = @read_exif_data_raw( $path, $verbose );
		
		if ( $args['flatten'] )
			$result = self :: get_flattened_array( $result );
		
		if ( $args['readable'] && '-1' == $args['key'] ) {
			foreach ($result as $key => $value) {
				$result .= $key . ': ' . $value . '<br>';
			}
			
		}
		if ( '-1' === $args['key'] )
			return $result;
		
		if ( $args['key'] && '-1' != $args['key'] ) {
			if ( $args['replace_string'] )
				$replace_string = $args['replace_string'];
			else
				$replace_string = $args['key'];
			return $replace_string . $args['after_replace_string'] . $result[ $args['key'] ];
		}
		
		return $result;
	}
	
} // end class
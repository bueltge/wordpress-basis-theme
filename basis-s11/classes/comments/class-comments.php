<?php
/**
 * @package WordPress
 * @subpackage WP-Basis Theme
 * @template class comments
 * @since 0.0.1
 */

class Wp_Basis_Comments {
	
	public function init() {
		
		add_action( 'get_header', array( __CLASS__, 'enable_threaded_comments' ) );
	}
	
	public function enable_threaded_comments() {
		
		if ( is_admin() )
			return;
		
		if ( is_singular() && comments_open() && ( 1 == get_option( 'thread_comments' ) ) )
			wp_enqueue_script( 'comment-reply' );
		
	}
	
}
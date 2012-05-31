<?php
/**
 * @package WordPress
 * @subpackage WP-Basis Theme
 * @template class login style
 * @since 0.0.1
 */

class Wp_Basis_Login_Style {
	
	static $login_style = '#login h1 a {
		background: none !important;
		text-indent: 0 !important;
		padding-left: 100px;
		display: table;
		height: auto;
		width: auto;
		text-decoration:none; }';
	
	public function init() {
		
		add_filter( 'login_headerurl',		array( __CLASS__, 'get_login_style' ) );
		add_filter( 'login_headertitle',	array( __CLASS__, 'get_login_style' ) );
		add_filter( 'login_head',			array( __CLASS__, 'get_login_style' ) );
	}
	
	public static function get_login_style( $str ) {
		
		if ( 'login_headerurl' == current_filter() )
			return home_url();
		
		if ( 'login_headertitle' == current_filter() )
			return get_bloginfo( 'name' );
		
		if ( 'login_head' == current_filter() )
			echo '<link rel="stylesheet" href="' .
				get_stylesheet_directory_uri() . '/css/login_style.css' . 
				'" type="text/css" media="all" />';
			
	}
	
}
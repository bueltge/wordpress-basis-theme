<?php
/**
 * The Header for our WP Basis theme
 * 
 * @package    WP Basis
 * @subpackage 
 * @since      05/08/2012  0.0.1
 * @version    05/08/2012
 * @author     fb
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]--> 
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]--> 
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]--> 
<!--[if IE 9 ]>    <html <?php language_attributes(); ?> class="no-js ie9"> <![endif]--> 
<!--[if (gt IE 9)|!(IE)]><!--><html <?php language_attributes(); ?> class="no-js" manifest="cache-manifest.manifest"><!--<![endif]--> 
	
	<head profile="http://gmpg.org/xfn/11">
		
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		
		<title><?php wp_title( '-' ); ?></title>
		
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		
		<?php wp_head(); ?>
		
	</head>
	
	<body <?php body_class(); ?>>
	
	<div id="wrap" class="hfeed">
	
		<header role="banner">
			<hgroup>
				<h1>
					<span><a href="<?php echo home_url( '/' ); ?>" title="<?php echo get_bloginfo( 'name', 'display' ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span>
				</h1>
				<h2><?php bloginfo( 'description' ); ?></h2>
			</hgroup>
		</header>
	

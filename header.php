<?php
/**
 * @package WordPress
 * @subpackage Basis_Theme xHTML5
 * @template header
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> >

<head>
	<meta charset="utf-8" />

	<title><?php if ( is_archive() ) { _e( 'Kategorie', FB_BASIS_TEXTDOMAIN ); echo ' '; } wp_title('-', true, 'right'); bloginfo('name'); ?></title>

	<?php if ( (!is_paged() ) && ( is_single() || is_page() || is_home() ) ) { echo '<meta name="robots" content="index, follow" />' . "\n"; } else { echo '<meta name="robots" content="noindex, follow, noodp, noydir" />' . "\n"; } ?>
	<meta name="description" content="<?php if ( is_single() ) { wp_title('-', true, 'right'); echo  strip_tags( get_the_excerpt() ); } elseif ( is_page() ) { wp_title('-', true, 'right'); echo strip_tags( get_the_excerpt() ); } else { bloginfo('description'); } ?>" />
	<meta name="author" content="Frank Bueltge - http://bueltge.de" />
	
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/layout/css/html5.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/layout/css/print.css" type="text/css" media="print" />
	<!--[if IE 8]>
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<![endif]--> 
	<!--[if gte IE 7]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/DOMAssistantCompressed-2.7.4.js" type="text/javascript"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/ie-css3.js" type="text/javascript"></script>
	<![endif]--> 
	
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="Shortcut Icon" type="image/x-icon" href="<?php echo home_url(); ?>/favicon.ico" />
	<link rel="apple-touch-icon" type="image/x-icon" href="<?php echo home_url(); ?>/apple-touch-icon.png"/>
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="search" type="application/opensearchdescription+xml" title="<?php bloginfo('name'); ?>" href="<?php echo get_template_directory_uri(); ?>/os.xml" />
	<?php if ( is_singular() ) echo '<link rel="canonical" href="' . get_permalink() . '" />'; ?>
	
	<?php 
	// for threaded comments at v2.7
	if ( is_singular() )
		wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head(); ?>

</head>
<?php flush(); ?>
<body <?php if (function_exists('body_class') ) body_class(); ?>>
	
<div id="wrap">
	
	<a id="skipLink" title="<?php _e('Direkt zum Beitrag; Tastaturkuerzel 0', FB_BASIS_TEXTDOMAIN); ?>" href="#content"><?php _e('&raquo; Direkt zum Beitrag', FB_BASIS_TEXTDOMAIN); ?></a>
	
	<header id="header">
		<hgroup>
			<h1><a href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a></h1>
			<h6><?php bloginfo('description'); ?></h6>
		</hgroup>
	</header>

	<div id="content">

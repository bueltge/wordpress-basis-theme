<?php
/**
 * @package WordPress
 * @subpackage Basis_Theme
 * @template header
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?> id="fb" >

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />

	<title><?php if ( is_archive() ) { _e( 'Kategorie', FB_BASIS_TEXTDOMAIN ); echo ' '; } wp_title('-', true, 'right'); bloginfo('name'); ?></title>

	<?php if ( (!is_paged() ) && ( is_single() || is_page() || is_home() ) ) { echo '<meta name="robots" content="index, follow" />' . "\n"; } else { echo '<meta name="robots" content="noindex, follow, noodp, noydir" />' . "\n"; } ?>
	<meta name="description" content="<?php if ( is_single() ) { wp_title('-', true, 'right'); echo  strip_tags( get_the_excerpt() ); } elseif ( is_page() ) { wp_title('-', true, 'right'); echo strip_tags( get_the_excerpt() ); } else { bloginfo('description'); } ?>" />
	<meta name="language" content="<?php echo get_bloginfo('language'); ?>" />
	<meta name="content-language" content="<?php echo get_bloginfo('language'); ?>" />
	<meta name="siteinfo" content="robots.txt" />
	<meta name="publisher" content="<?php bloginfo('name'); ?>" />
	<meta name="author" content="Frank Bueltge - http://bueltge.de" />

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<?php if ( is_single() || is_page() ) { ?>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/layout/css/single.css" type="text/css" media="screen" />
	<?php } ?>
	<!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/layout/css/ie.css" /><![endif]-->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/layout/css/print.css" type="text/css" media="print" />
	
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />

	<link rel="Shortcut Icon" type="image/x-icon" href="<?php bloginfo('url'); ?>/favicon.ico" />
	<link rel="apple-touch-icon" type="image/x-icon" href="<?php bloginfo('url'); ?>/apple-touch-icon.png"/>
	
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="search" type="application/opensearchdescription+xml" title="<?php bloginfo('name'); ?>" href="<?php bloginfo('template_directory'); ?>/os.xml" />
	<?php if ( is_singular() ) echo '<link rel="canonical" href="' . get_permalink() . '" />'; ?>
	
	<!--<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/.js"></script>-->
	
	<?php 
	// for threaded comments at v 2.7
	if ( is_singular() )
		wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head(); ?>

</head>
<body <?php if (function_exists('body_class') ) body_class(); ?>>

<div id="wrap">

	<a id="skipLink" title="<?php _e('Direkt zum Beitrag; Tastaturkuerzel 0', FB_BASIS_TEXTDOMAIN); ?>" href="#content"><?php _e('&raquo; Direkt zum Beitrag', FB_BASIS_TEXTDOMAIN); ?></a>
	
	<div id="header" onclick="location.href='<?php bloginfo('url'); ?>';" onkeypress="location.href='<?php bloginfo('url'); ?>';" style="cursor: pointer;" >
		<h1><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></h1>
		<p><?php bloginfo('description'); ?></p>
	</div>

	<div id="content">

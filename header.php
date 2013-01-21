<?php
/**
 * The Header for our WP Basis theme
 * 
 * @package    WP Basis
 * @subpackage 
 * @since      05/08/2012  0.0.1
 * @version    01/21/2013
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
	
	<?php
	/**
	 * If you want to use an element as a wrapper, 
	 * i.e. for styling only, then <div> is still the element to use
	 */
	?>
	<div id="wrap" class="hfeed">
	
		<?php
		/*
		 * The page header typically contains items such as your site heading, 
		 * logo and possibly the main site navigation 
		 * ARIA: the landmark role "banner" is set as it is the prime heading or internal title of the page
		 */
		?>
		<header role="banner">
			<hgroup>
				<h1>
					<span><a href="<?php echo home_url( '/' ); ?>" title="<?php echo get_bloginfo( 'name', 'display' ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span>
				</h1>
				<h2><?php bloginfo( 'description' ); ?></h2>
			</hgroup>
		</header>
		
		<?php
		/**
		 * The <main> element is used to enclose the main content, 
		 * i.e. that which contains the central topic of a document
		 * ARIA: the landmark role "main" is added here as it contains the main content
		 * of the document, and it is recommended to add it to the 
		 * <main> element until user agents implement the required role mapping.
		 */
		?>
		<main role="main">
		

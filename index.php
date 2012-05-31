<?php
/**
 * The main template file.
 * 
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 * @link        http://codex.wordpress.org/Template_Hierarchy
 * 
 * @package    WP Basis
 * @subpackage 
 * @since      05/08/2012  0.0.1
 * @version    05/08/2012
 * @author     fb
 */

get_header();
	
	do_action( 'wp_basis_index_before_content' );
	
	// Whether current WordPress query has results to loop over
	if ( have_posts() ) {
	
		/* Start the Loop */
		while ( have_posts() ) : 
			the_post();
			
			/**
			 * Include the Post-Format-specific template for the content.
			 * If you want to overload this in a child theme then include a file
			 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
			 */
			get_template_part( 'parts/content', get_post_format() );
			
		endwhile;
	
	// if the loop has no results
	} else {
		/**
		 * Include the template for the loop dosn't find and result
		 * If you will overwrite this in in a child theme the include a file
		 * called no-results-index.php and that will be used instead.
		 */
		get_template_part( 'parts/no-results', 'index' );
		
	} // endif
	
	do_action( 'wp_basis_index_after_content' );
	
get_sidebar();
get_footer();

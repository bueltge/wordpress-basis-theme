<?php
/**
 * The Template for displaying all pages.
 * 
 * @package    WP Basis
 * @subpackage 
 * @since      06/06/2012  0.0.1
 * @version    06/06/2012
 * @author     fb
 */

get_header();
	
	do_action( 'wp_basis_page_before_content' );
	?>
	
	<div id="primary" class="site-content">
		<div id="content" role="main">
			
	<?php
	// Whether current WordPress query has results to loop over
	if ( have_posts() ) {
		
		do_action( 'wp_basis_page_query_result' );
		
		/* Start the Loop */
		while ( have_posts() ) : 
			the_post();
			
			/**
			 * If you want to overload this in a child theme then include a file
			 * called content-page.php and that will be used instead.
			 */
			get_template_part( 'parts/content', 'page' );
			
			// If comments are open or we have at least one comment, load up the comment template
			if ( comments_open() || '0' != get_comments_number() )
				comments_template( '', TRUE );
			
		endwhile;
	
	// if the loop has no results
	} else {
		/**
		 * Include the template for the loop dosn't find and result
		 * If you will overwrite this in in a child theme the include a file
		 * called no-results-page.php and that will be used instead.
		 */
		get_template_part( 'parts/no-results', 'page' );
		
	} // endif
	?>
	
		</div> <?php // end #content ?>
	</div> <?php // end #primary
	
	do_action( 'wp_basis_page_after_content' );
	
get_sidebar();
get_footer();

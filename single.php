<?php
/**
 * The Template for displaying all single posts
 * 
 * @package    WP Basis
 * @subpackage 
 * @since      05/08/2012  0.0.1
 * @version    05/08/2012
 * @author     fb
 */

get_header();
	
	do_action( 'wp_basis_single_before_content' );
	?>
	
	<div id="primary" class="site-content">
		<div id="content" role="main">
			
	<?php
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
			// If comments are open or we have at least one comment, load up the comment template
			if ( comments_open() || '0' != get_comments_number() )
				comments_template( '', true );
			
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
	?>
	
		</div> <?php // end #primary ?>
	</div> <?php // end #content
	
	do_action( 'wp_basis_single_after_content' );
	
get_sidebar();
get_footer();

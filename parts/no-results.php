<?php
/**
 * The default content template file
 * 
 * @package    WP Basis
 * @subpackage 
 * @since      05/08/2012  0.0.1
 * @version    05/08/2012
 * @author     fb
 */
?>

<article id="post-0" class="post no-results not-found">
	
	<header>
		<h1><?php _e( 'Nothing found', 'wp_basis' ); ?></h1>
	</header>
	
	<div class="entry-content">
		
		<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'wp_basis' ); ?></p>
		<?php get_search_form(); ?>
		
	</div>
	
</article>

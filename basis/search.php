<?php
/**
 * @package WordPress
 * @subpackage Basis_Theme
 * @template search
 */

get_header(); ?>
	
	<h2><?php echo sprintf( __('Die Suche ergab %s Artikel', FB_BASIS_TEXTDOMAIN), $wp_query->found_posts ); ?></h2>
	
	<?php if ( have_posts() ) {
		while ( have_posts() ) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
		
			<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo sprintf( __( 'Permanent Link to %s', FB_BASIS_TEXTDOMAIN ), get_the_title() ); ?>"><?php the_title(); ?></a></h3>
			<?php the_excerpt(); ?>
		
		</div>
		
	<?php endwhile;
	} else { ?>
	
	<p><?php _e('Nichts gefunden, was den Suchkriterien entspricht.', FB_BASIS_TEXTDOMAIN); ?></p>
	
	<?php } // end else ?>

<?php get_footer(); ?>
<?php
/**
 * @package WordPress
 * @subpackage Basis_Theme xHTML5
 */

get_header();

	if ( have_posts() ) {
		while ( have_posts() ) : the_post(); ?>
		<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
				<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<?php the_content(); ?>
			<section>
				<?php wp_link_pages(); ?>
			</section>
			<section class="info">
				<p><?php _e('Aktualisiert am', FB_BASIS_TEXTDOMAIN); ?> <time datetime="<?php the_modified_date('Y-m-d'); ?>"><?php the_modified_date(); ?></time> <?php edit_post_link(__('Editieren', FB_BASIS_TEXTDOMAIN),' &middot; ', ''); ?></p>
				<p><?php the_tags() ?></p>
			</section>
		</article>
		
		<?php comments_template( '', TRUE );

		endwhile;
	} else {
	?>
	
		<section>
			<p><?php _e('Nichts gefunden, was den Suchkriterien entspricht.', FB_BASIS_TEXTDOMAIN); ?></p>
		</section>
	
	<?php } ?>

<?php get_footer(); ?>

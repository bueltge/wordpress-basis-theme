<?php
/**
 * @package WordPress
 * @subpackage Basis_Theme
 */

get_header();?>

	<?php if (have_posts()) { while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
			<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<p class="attachment">
					<a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php echo wp_get_attachment_image( $post->ID, 'medium' ); ?></a>
				</p>
				<div class="caption">
					<?php if ( !empty($post->post_excerpt) ) the_excerpt(); // this is the "caption" ?>
				</div>
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
			<p class="info"><?php _e('Aktualisiert am', FB_BASIS_TEXTDOMAIN); ?> <?php the_modified_date(); ?> <?php edit_post_link(__('Editieren', FB_BASIS_TEXTDOMAIN),' &middot; ', ''); ?></p>
		</div>
		
		<?php comments_template(); ?>
		
	<?php endwhile; } else { ?>
	
	<p><?php _e('Nichts gefunden, was den Suchkriterien entspricht.', FB_BASIS_TEXTDOMAIN); ?></p>
	
	<?php } ?>

<?php get_footer(); ?>
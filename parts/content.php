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

<?php
/**
 * The <article> element can be used to enclose content 
 * that still makes sense on its own and is therefore "reusable"
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * A <header> element is required 
	 * here is the heading contains often a <h1> element in Loop
	 */
	?>
	<header>
		<h1><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	</header>
	
	<div class="entry-content">
			
			<?php
			the_content(
				the_title( '', '', FALSE ) . ' ' . __( 'read more &raquo;', 'wp_basis' )
			);
			wp_link_pages(
				array(
					'before' => '<nav class="page-link">' . __( '<span>Pages:</span>', 'wp_basis' ),
					'after' => '</nav>',
				)
			);
			
			do_action( 'wp_basis_content_after_content' );
			?>
			
			<footer class="entry-meta">
			<?php if ( 'post' == $post->post_type ) : // Hide category and tag text for pages on Search ?>
				<span class="cat-links"><?php _e( 'Posted in', 'wp_basis' ); ?> <?php the_category( ', ' ); ?></span>
				<?php the_tags( '<span class="sep"> &middot; </span> <span class="tag-links"><span class="entry-utility-prep entry-utility-prep-tag-links">' . __( 'Tagged', 'wp_basis' ) . '</span> ', ', ', '</span>' ); ?>
			<?php endif; ?>
			
			<?php if ( comments_open() ) : ?>
				<span class="sep"> &middot; </span>
				<span class="comments-link"><?php comments_popup_link( __( '<span class="leave-reply">Leave a reply</span>', 'wp_basis' ), __( '<b>1</b> Reply', 'wp_basis' ), __( '<b>%</b> Replies', 'wp_basis' ) ); ?></span>
			<?php endif; ?>
			
			<?php edit_post_link( __( 'Edit', 'wp_basis' ), '<span class="sep"> &middot; </span> <span class="edit-link">', '</span>' ); ?>
			</footer> <?php // end .entry-meta ?>
			
	</div> <?php // end .entry-content ?>
	
</article>

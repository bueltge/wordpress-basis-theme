<?php
/**
 * The content template file
 * 
 * @package    WP Basis
 * @subpackage 
 * @since      0.0.1
 * @author     fb
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header>
			<h1><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		</header>
		
		<div class="entry-content">
			
			<?php
			the_content( the_title( '', '', FALSE ) . ' ' . __( 'read more &raquo;', WP_BASIS_TXTD ) );
			wp_link_pages( array( 'before' => '<nav class="page-link">' . __( '<span>Pages:</span>', WP_BASIS_TXTD ), 'after' => '</nav>' ) );
			
			do_action( 'wp_basis_content_after_content' );
			?>
			
			<footer class="entry-meta">
			<?php if ( 'post' == $post->post_type ) : // Hide category and tag text for pages on Search ?>
				<span class="cat-links"><?php _e( 'Posted in', WP_BASIS_TXTD ); ?> <?php the_category( ', ' ); ?></span>
				<?php the_tags( '<span class="sep"> &middot; </span> <span class="tag-links"><span class="entry-utility-prep entry-utility-prep-tag-links">' . __( 'Tagged', WP_BASIS_TXTD ) . '</span> ', ', ', '</span>' ); ?>
			<?php endif; ?>
			
			<?php if ( comments_open() ) : ?>
				<span class="sep"> &middot; </span>
				<span class="comments-link"><?php comments_popup_link( __( '<span class="leave-reply">Leave a reply</span>', WP_BASIS_TXTD ), __( '<b>1</b> Reply', WP_BASIS_TXTD ), __( '<b>%</b> Replies', WP_BASIS_TXTD ) ); ?></span>
			<?php endif; ?>
			
			<?php edit_post_link( __( 'Edit', WP_BASIS_TXTD ), '<span class="sep"> &middot; </span> <span class="edit-link">', '</span>' ); ?>
			</footer> <!-- .entry-meta -->
			
		</div> <!-- .entry-content" -->
		
	</article>

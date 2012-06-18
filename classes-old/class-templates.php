<?php
/**
 * @package WordPress
 * @subpackage WP-Basis Theme
 * @template class template
 * @since 0.0.1
 */

class Wp_Basis_Templates {
	
	public static function get_text_domain() {
		
		return Wp_Basis_Core :: get_text_domain();
	}
	
	public static function get_css_dir() {
		
		return apply_filters( 'wp_basis_theme_css_dir',  get_stylesheet_directory_uri() . '/css/' );
	}
	
	public static function get_img_dir() {
		
		return apply_filters( 'wp_basis_theme_img_dir',  get_stylesheet_directory_uri() . '/images/' );
	}
	
	public static function get_js_dir() {
		
		return apply_filters( 'wp_basis_theme_js_dir',  get_stylesheet_directory_uri() . '/js/' );
	}
	
	public static function get_page_number( $string	 = FALSE, $hide_first = TRUE ) {
		global $paged;
		
		if ( ! $string )
			$string = ' ' . __( '(Page %d)', self :: get_text_domain() );
		
		if ( empty( $paged ) ) {
			if ( $hide_first ) {
				return NULL;
			}
			
			$paged = 1;
		}
		
		return sprintf( $string, (int) $paged );
	}
	
	public function content_nav( $nav_id, $pag_bar = TRUE ) {
	
		if ( $GLOBALS['wp_query'] -> max_num_pages > 1 ) : ?>
			<nav id="<?php echo $nav_id; ?>">
				<h1 class="assistive-text"><?php _e( 'Post navigation', 'wp_basis' ); ?></h1>
				<?php 
				if ( $pag_bar ) {
					self :: get_paginate_bar();
				} else { ?>
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'wp_basis' ) ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'wp_basis' ) ); ?></div>
				<?php } ?>
			</nav>
		<?php endif;
	}
	
	public function get_paginate_bar( $args = FALSE ) {
		global $wp_rewrite, $wp_query;
		
		$wp_query -> query_vars['paged'] > 1 ? $current = $wp_query -> query_vars['paged'] : $current = 1;
		if ( empty($rules) ) {
			$rulestouse = @add_query_arg( 'paged','%#%' );
		} else {
			$rulestouse = @add_query_arg( 'page','%#%' );
		}
		
		if ( ! $args ) {
			$args = array(
				'base'         => $rulestouse,
				'format'       => '',
				'total'        => $wp_query -> max_num_pages,
				'current'      => $current,
				'show_all'     => TRUE,
				'prev_next'    => TRUE,
				'prev_text'    => __( '« Previous', 'wp_basis' ),
				'next_text'    => __( 'Next »', 'wp_basis' ),
				'end_size'     => 1,
				'mid_size'     => 2,
				'type'         => 'plain',
				'add_args'     => false, // array of query args to add
				'add_fragment' => '',
				'show_total'   => TRUE,
				'display'      => TRUE
			);
		}
		
		if ( $wp_rewrite -> using_permalinks() ) {
			$args['base'] = user_trailingslashit( 
				trailingslashit( remove_query_arg( 's', get_pagenum_link(1) ) ) . 'page/%#%/', 'paged' );
		}
		if ( ! empty( $wp_query -> query_vars['s'] ) ) {
			$args['add_args'] = array( 's' => get_query_var('s') );
		}
		
		$pagination = paginate_links( $args );
		
		if ( $args['show_total'] )
			$pagination .= __( ' (', 'wp_basis' ) . $wp_query -> max_num_pages . __( ')', 'wp_basis' );
		
		if ( $args['display'] )
			echo $pagination;
		else
			return $pagination;
	}
	
	public function get_teaser_pics( $post_id = FALSE, $what = FALSE ) {
		
		$html = NULL;
		if ( ! $post_id )
			$post_id = get_the_ID();
		
		$attachments = get_children( array(
			'post_parent'    => $post_id,
			'post_type'      => 'attachment',
			'numberposts'    => 1, // show all -1
			'post_status'    => 'inherit',
			'post_mime_type' => 'image',
			'order'          => 'ASC',
			'orderby'        => 'menu_order ID',
			'limit'          => 1
		) );
		
		if ( $attachments && 'srcthumbnail' === $what  ) {
			foreach ($attachments as $attachment) {
				$html = wp_get_attachment_thumb_url( $attachment -> ID );
				break;
			}
			return $html;
		}
			
		if ( $attachments && 'src' === $what  ) {
			foreach ($attachments as $attachment) {
				$html = wp_get_attachment_url( $attachment -> ID );
				break;
			}
			return $html;
		}
		
		if ( ! $what && $attachments ) {
			foreach ($attachments as $attachment) {
				$image = wp_get_attachment_image_src( $attachment -> ID, $what );
				if ( $image ) {
					list( $src, $width, $height ) = $image;
					if ( is_array($size) )
						$size = join( 'x', $size);
					$html = '<img src="' . esc_attr($src) . '" width="'. $width . 
						'" height="' . $height .'" class="alignleft ' . $what . 
						' attachment-' . attribute_escape($size) . '" alt="" />';
				}
			}
			return $html;
		}
	}
	
	function is_subcategory() {
			
		$cat = get_query_var('cat');
		$category = get_category($cat);
		$category -> parent;
		return ( '0' == $category -> parent ) ? FALSE : TRUE;
	}
	
} //end class
<?php
/**
 * @package    WordPress
 * @subpackage WP-Basis Theme
 * @template   class hooks
 * @since      0.0.1
 */

class Wp_Basis_Hooks {
	
	protected $default_functions = array();
	
	public function init( $functions = array() ) {
	
		add_filter( 'wp_title', array( __CLASS__, 'get_title_filter' ), 11, 1 );
		// hook in wp_head
		$this->default_functions = array( 
			'get_favicon', 'get_index', 'get_meta_viewport', 
			'get_meta_compatibility',
			'get_meta_keywords', 'get_meta_description'
		);
		// all functions for wp_head Hook
		$functions = array_merge( $functions, $this->default_functions );
		foreach( $functions as $function ) {
			add_action( 'wp_head', array( __CLASS__, $function ) );
		}
		
	}
	
	public function get_text_domain() {
		
		return Wp_Basis_Core :: get_text_domain();
	}
	
	public function get_title_filter( $title ) {
		
		$title       = trim( $title );
		$page_number = Wp_Basis_Templates :: get_page_number();
		$sum_posts   = self :: get_sum_posts();
		
		if ( is_home() || is_front_page() )
			$title = get_bloginfo( 'name' ) . ' - '. get_bloginfo( 'description' );
		
		if ( ! empty ( $title ) )
			return $title . $page_number;
		
		if ( is_singular() ) {
			if ( '' == $title )
				return get_the_time( get_option( 'date_format' ) ) . $page_number;
			
			return $title . $page_number;
		}
		
		return $title . $page_number;
	}
	
	public function get_index( $display ) {
			
		if ( ! $display )
			$display = TRUE;
		
		if ( ( ! is_paged() ) && 
			 ( is_single() || is_page() || is_home() || is_category() )
			)
			$index = 'index, follow, noodp';
		else
			$index = 'noindex, follow';
		
		$index = apply_filters( 'wp_basis_robots_index', '<meta name="robots" content="' . $index . '" />'. "\n" );
		
		if ( $display )
			echo $index;
		else
			return $index;
	}
	
	public function get_favicon( $display ) {
		
		if ( ! $display )
			$display = TRUE;
		
		$url = Wp_Basis_Templates :: get_img_dir() . 'favicon.ico';
		
		$favicon = apply_filters( 'wp_basis_favicon', '<link rel="shortcut icon" href="' . $url . '" />' . "\n" );
		
		if ( $display )
			echo $favicon;
		else
			return $favicon;
	}
	
	/**
	 * Mobile viewport optimized
	 * 
	 * @see  j.mp/bplateviewport
	 */
	public function get_meta_viewport( $display ) {
		
		if ( ! $display )
			$display = TRUE;
		
		$viewport = apply_filters( 'wp_basis_meta_viewport', '<meta name="viewport" content="width=device-width, initial-scale=1.0" />' . "\n" );
		
		if ( $display )
			echo $viewport;
		else
			return $viewport;
	}
	
	/**
	 * @see  http://msdn.microsoft.com/en-us/library/ms533876(v=VS.85).aspx
	 * @see  Use the .htaccess and remove these lines to avoid edge case issues.
	 *       More info: h5bp.com/b/378 -->
	 */
	public function get_meta_compatibility( $value = FALSE, $display = FALSE ) {
		
		if ( ! $display )
			$display = TRUE;
		
		if ( ! $value )
			$value = 'IE=edge,chrome=1';
		
		$compatibility = apply_filters( 
			'wp_basis_meta_compatibility', 
			'<meta http-equiv="X-UA-Compatible" content="' . $value . '" />' . "\n" 
		);
		
		if ( $display )
			echo $compatibility;
		else
			return $compatibility;
	}
	
	public function get_meta_keywords( $display ) {
		
		if ( ! $display )
			$display = TRUE;
		
		$args = array( 'format' => 'array', 'orderby' => 'count', 'order' => 'DESC', 'echo' => 0 );
		$tags = wp_tag_cloud($args);
		if ( $tags ) {
			$meta_keywords = FALSE;
			foreach( (array) $tags as $tag ) {
				$meta_keywords .= strtolower( strip_tags($tag) ) . ', ';
			}
		}
		
		if ( empty( $meta_keywords ) )
			return NULL;
		
		$meta_keywords = esc_attr( strip_tags( stripslashes( rtrim( $meta_keywords, ', ' ) ) ) );
		
		$meta_tags = apply_filters( 
			'wp_basis_meta_keywords', 
			trim( $meta_keywords )
		);
		
		if ( ! empty( $meta_keywords ) && $display ) {
			echo '<meta name="keywords" content="' . $meta_keywords . '" />' . "\n";
		} else
			return $meta_keywords;
	}
	
	public function get_meta_description( $display ) {
		
		if ( get_query_var('paged') && get_query_var('paged') > 1 )
			return;
		
		if ( ! $display )
			$display = TRUE;
		
		$description = '';
		if ( is_singular() ) {
			$description = get_the_excerpt();
			if ( empty( $description ) )
				$description .= ' - ' . get_bloginfo('description');
			$description  = wp_title( '', FALSE ) . $description;
		} elseif ( is_category() ) {
			$description  = wp_title( '', FALSE );
			$description .= category_description();
		} else {
			$description .= get_bloginfo('description');
		}
		
		$description = esc_attr( strip_tags( stripslashes( $description ) ) );
		
		$description = apply_filters( 
			'wp_basis_meta_description', 
			trim( $description )
		);
		
		if ( ! empty( $description ) && $display ) {
			echo '<meta name="description" content="' . $description . '" />' . "\n";
		} else
			return $description;
	}
	
	public function get_sum_posts( $zero = FALSE, $one = FALSE, $many = FALSE ) {
		global $wp_query;
		
		if ( ! $zero )
			$zero = 'Noch kein Beitrag';
		if ( ! $one )
			$one = 'Erst ein Beitrag';
		if ( ! $many )
			$many = '% Beiträge';
			
		$n = $wp_query->found_posts;

		if ( 0 == $n )
			return str_replace('%', $n, $zero);
		
		if ( 1 == $n )
			return str_replace('%', $n, $one);
		
		return str_replace('%', $n, $many);
	}

} // end class

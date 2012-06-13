<?php
/**
 * The Template for displaying the search results.
 * 
 * @package    WP Basis
 * @subpackage 
 * @since      06/06/2012  0.0.1
 * @version    06/06/2012
 * @author     fb
 */

get_header();
	
	do_action( 'wp_basis_search_before_content' );
	?>
	
	<section id="primary" class="site-content">
		<div id="content" role="main">
			
	<?php
	// Whether current WordPress query has results to loop over
	if ( have_posts() ) {
		
		do_action( 'wp_basis_search_query_result' );
		
		$defaults = array(
			'tag'        => 'header',
			'class'      => 'page-header',
			'before_txt' => '<h1 class="page_title">',
			'txt'        => sprintf( 
				__( 'Search Results for: %s', 'wp_basis' ),
				'<span>' . get_search_query() . '</span>'
			),
			'after_txt'  => '</h1>'
		);
		
		$args = wp_parse_args( $args = array(), apply_filters( 'wp_basis_search_title', $defaults ) );
		
		// check for class param
		if ( ! empty( $args['class'] ) )
			$class = ' class="' . $args['class'] . '"';
		
		// check for param tag, if empty, don't generate markup
		if ( ! empty( $args['tag'] ) ) {
			$title = '<' . $args['tag'] . $class . '>' . $args['before_txt'] . $args['txt'] . $args['after_txt'] . '</' . $args['tag'] . '>';
		} else {
			$title = $args['before_txt'] . $args['txt'] . $args['after_txt'];
		}
		
		echo $title;
		
		/* Start the Loop */
		while ( have_posts() ) : 
			the_post();
			
			/**
			 * If you want to overload this in a child theme then include a file
			 * called content-search.php and that will be used instead.
			 */
			get_template_part( 'parts/content', 'search' );
			
		endwhile;
	
	// if the loop has no results
	} else {
		/**
		 * Include the template for the loop dosn't find and result
		 * If you will overwrite this in in a child theme the include a file
		 * called no-results-search.php and that will be used instead.
		 */
		get_template_part( 'parts/no-results', 'search' );
		
	} // endif
	?>
	
		</div> <?php // end #content ?>
	</section> <?php // end section #primary
	
	do_action( 'wp_basis_search_after_content' );
	
get_sidebar();
get_footer();

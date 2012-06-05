<?php
/**
 * The Template for displaying sidebar include main widget area
 * 
 * @package    WP Basis
 * @subpackage 
 * @since      05/08/2012  0.0.1
 * @version    06/05/2012
 * @author     fb
 */

if ( get_theme_support( 'show_sidebar-1' ) && 'on' != get_theme_mod( 'show_sidebar-1' ) )
	return;
?>

	<div id="secondary" class="widget-area" role="complementary">
		
		<?php do_action( 'wp_basis_sidebar_before' );
		
		if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>
		
			<aside id="search" class="widget widget_search">
				<?php get_search_form(); ?>
			</aside>
			
			<aside id="meta" class="widget widget_meta">
				<h1 class="widget-title"><?php _e( 'Meta', 'wp_basis' ); ?></h1>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
			</aside>
			
		<?php endif; // end sidebar widget area
		
		do_action( 'wp_basis_sidebar_after' ); ?>
	
	</div> <?php // end #secondary ?>
	

<?php
/**
 * @package WordPress
 * @subpackage WP-Basis Theme
 * @template sidebar template
 * @since 0.0.1
 */
if ( get_theme_support( 'show_sidebar-1' ) && 'on' != get_theme_mod( 'show_sidebar-1' ) )
	return;
?>

	<aside id="sidebar">
		<h1><?php _e('Sidebar', 'wp_basis'); ?></h1>
		<nav>
			<h2><?php _e('Navigation', 'wp_basis'); ?></h2>
			<ul>
				
				<?php
				/* Widgetized sidebar, if you have the plugin installed. */
				if ( ! dynamic_sidebar() ) : ?>
				
				<li id="posts" class="widget">
					<h3 class="widget-title"><?php _e('Last Posts', 'wp_basis') ?></h3>
					<ul>
						<?php wp_get_archives('type=postbypost&limit=10'); ?>
					</ul>
				</li>
				
				<li id="meta" class="widget">
					<h3 class="widget-title"><?php _e( 'Meta', 'wp_basis' ); ?></h1>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</li>
				
				<?php
				endif; // End Widgets
				?>
			</ul>
		</nav>
	</aside>


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

	<?php
	/*
	 * An <aside> is used to enclose content that is additional to the main content 
	 * but not essential. If it were removed, the meaning of the main content should not be lost, 
	 * but the content of the <aside> also retains its meaning.
	 * NOTE: the aside is placed outside of the <main> element as while its content 
	 * is related to the content that is within the <main> element, it is not part of it
	 * ARIA: the landmark role "complementary" is added here as it contains supporting 
	 * information for the main content that remains meaningful even when separated from it 
	 */
	?>
	<aside id="sidebar" role="complementary">
		<?php
		/**
		 * A <header> element is not required 
		 * here as the heading only contains a single <h1> element
		 */
		?>
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


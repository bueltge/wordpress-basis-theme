<?php
/**
 * @package WordPress
 * @subpackage Basis_Theme
 */
?>

	<div id="sidebar">
		<ul>
			
			<?php
				/* Widgetized sidebar, if you have the plugin installed. */
				if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
			<li id="search">
				<?php include(TEMPLATEPATH . '/searchform.php'); ?>
			</li>
			
			<?php if ( function_exists('wp_page_menu') ) { // erst ab wp 2.7
				echo '<li>';
				wp_page_menu('show_home=1&menu_class=page-navi&title_li=');
				echo '</li>';
			} else {
				wp_list_pages('title_li=<h3>' . __('Seiten', FB_BASIS_TEXTDOMAIN) . '</h3>');
			} ?>
	
			<li><h3><?php _e('Beitr&auml;ge', FB_BASIS_TEXTDOMAIN) ?></h3>
				<ul>
				<?php wp_get_archives('type=postbypost&limit=10'); ?>
				</ul>
			</li>
		
			<?php if (is_archive()) { ?>
			<li><h3><?php _e('Archiv', FB_BASIS_TEXTDOMAIN); ?></h3>
				<ul>
				<?php wp_get_archives('type=monthly&show_post_count=1'); ?>
				</ul>
			</li>
			
			<?php }
			endif; // End Widgets
			?>
		</ul>
	</div>

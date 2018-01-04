<?php
/**
 * @package    WordPress
 * @subpackage WP-Basis Theme
 * @template   sidebar template
 * @since      0.0.1
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}

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
	<aside id="sidebar" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</aside>


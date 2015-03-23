<?php
/**
 * @package WordPress
 * @subpackage Basis_Theme
 */
?>
	</div>
	
	<?php get_sidebar(); ?>
	
	<div id="footer">
		<p>&copy; <?php echo date('Y'); ?> &middot; <a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a> &middot; <small><?php echo $wpdb->num_queries; ?>q, <?php timer_stop(1); ?>s</small></p>
	</div>
	<?php wp_footer(); ?>
</div>
</body>
</html>
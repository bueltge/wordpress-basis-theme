<?php
/**
 * @package WordPress
 * @subpackage Basis_Theme xHTML5
 */
?>
	</div>
	
	<?php get_sidebar(); ?>
	
	<footer>
		<p>&copy; <time datetime="<?php echo date('Y-m-d'); ?>"><?php echo date('Y'); ?></time> &middot; <a href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a> &middot; <small><?php echo $wpdb->num_queries; ?>q, <?php timer_stop(1); ?>s</small></p>
	</footer>
	
	<?php wp_footer(); ?>
	
</div>

</body>
</html>

<?php
/**
 * The Template for displaying the footer.
 * 
 * @package    WP Basis
 * @subpackage 
 * @since      06/12/2012  0.0.1
 * @version    06/12/2012
 * @author     fb
 */
?>
		
		</div> <?php // end #main in header.php ?>
		
		<footer id="colophon" class="site-footer" role="contentinfo">
			<?php do_action( 'wp_basis_footer_credits' );
			printf( __( '<p class="site-info">Theme: %1$s by %2$s.</p>', 'wp_basis' ), 'WP Basis', '<a href="https://github.com/bueltge/WordPress-Basis-Theme/network/members" rel="designer">Contributer Team</a>' );
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG )
				printf( __( '<p>Generate Page with %1$sq in %2$ss.</p>', 'wp_basis' ), $wpdb->num_queries, timer_stop( 0 ) );
			?>
		</footer>
		
	</div> <?php // end #wrap in header.php ?>
	
	<?php wp_footer(); ?>
	
	</body>
</html>

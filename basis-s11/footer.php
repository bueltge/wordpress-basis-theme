	
	</div> <?php // end #wrap in header.php ?>
	
		<footer>
			<p>
				<?php echo $wpdb->num_queries; ?>q, <?php timer_stop(1); ?>s
			</p>
		</footer>
	
	<?php wp_footer(); ?>
	
	</body>
</html>
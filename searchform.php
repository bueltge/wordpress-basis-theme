<?php
/**
 * The template for displaying search forms
 * 
 * @package  WP Basis
 * @since    05/08/2012  0.0.1
 * @version  06/05/2012
 * @author   fb
 */
?>

<form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>" role="search">
	<fieldset>
		<label for="s" class="assistive-text invisible"><?php _e( 'Search', 'wp_basis' ); ?></label>
		<input type="text" class="field" name="s" id="s" placeholder="<?php esc_attr_e( 'Search &hellip;', 'wp_basis' ); ?>" />
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'wp_basis' ); ?>" />
	</fieldset>
</form>

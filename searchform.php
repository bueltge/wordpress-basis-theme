<?php
/**
 * @package WordPress
 * @subpackage Basis_Theme xHTML5
 */
?>

<form method="get" id="searchform" action="<?php echo home_url(); ?>/">
	<fieldset>
		<legend><?php _e('Suche', FB_BASIS_TEXTDOMAIN) ?></legend>
		<label for="s" class="invisible"><?php _e('Suchbegriffe eingeben', FB_BASIS_TEXTDOMAIN) ?></label>
		<input type="search" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="<?php _e('Hier suchen', FB_BASIS_TEXTDOMAIN) ?>" />
		<input type="submit" value="<?php _e('Suche', FB_BASIS_TEXTDOMAIN) ?>" id="searchsubmit" />
	</fieldset>
</form>

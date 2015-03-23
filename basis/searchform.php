<?php
/**
 * @package WordPress
 * @subpackage Basis_Theme
 */
?>

<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<div>
	<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
	<input type="submit" id="searchsubmit" value="<?php _e('Suche', FB_BASIS_TEXTDOMAIN) ?>" />
</div>
</form>

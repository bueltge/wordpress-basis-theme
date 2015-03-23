<?php
/**
 * Template Name: Blog
 *
 * @package WordPress
 * @subpackage Basis_Theme
 */

// Which page of the blog are we on?
$paged = get_query_var('paged');
query_posts('cat=-0&paged=' . $paged);
// make posts print only the first part with a link to rest of the post.
global $more;
$more = 0;
//load index to show blog
load_template(TEMPLATEPATH . '/index.php');
?>
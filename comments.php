<?php
/**
 * @package WordPress
 * @subpackage Basis_Theme xHTML5
 */
?>

<section id="comments">
<?php
if ( !empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) )
	die ( __('Bitte lade diese Datei nicht direkt. Danke!', FB_BASIS_TEXTDOMAIN) );

if ( function_exists('post_password_required') ) {
	if ( post_password_required() ) { ?>
		<section class="nocomments">
			<p><?php _e('Passwort eingeben, um Kommentare zu sehen.', FB_BASIS_TEXTDOMAIN); ?></p>
		</section>
		<?php return;
	}
} else {
	if ( !empty($post->post_password) ) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) { // and it doesn't match the cookie  ?>
			<section class="nocomments">
				<p><?php _e('Passwort eingeben, um Kommentare zu sehen.', FB_BASIS_TEXTDOMAIN); ?></p>
			</section>
			<?php return;
		}
	}
}

	
// WP 2.7 and higher comment Loop
if ( have_comments() ) { ?>
	
	<section class="navigation nav_comments">
		<div class="alignleft"><?php previous_comments_link() ?></div> <div class="alignright"><?php next_comments_link() ?></div>
	</section>
	
	<?php
	$comments_by_type = separate_comments($comments);
	if ( !empty($comments_by_type['comment']) && function_exists( 'fb_comment_type_count' ) ) { ?>
		<header>
			<h2 class="comments"><?php fb_comment_type_count( 'comment' ); ?></h2>
		</header>
		<ol class="commentlist">
			<?php wp_list_comments( 'type=comment&callback=fb_theme_comment' ); ?>
		</ol>
	<?php }
	
	// alternativ type pings fuer trackback + pingback
	if ( !empty($comments_by_type['pingback']) && function_exists( 'fb_comment_type_count' ) ) { ?>
		<h2 class="pingback"><?php fb_comment_type_count( 'pingback', '', __('Ein Pingback', FB_BASIS_TEXTDOMAIN), __('% Pingbacks', FB_BASIS_TEXTDOMAIN) ); ?></h2>
		<ol class="pingbacklist">
			<?php wp_list_comments('type=pingback'); ?>
		</ol>
	<?php }
	
	// alternativ type pings fuer trackback + pingback
	if ( !empty($comments_by_type['trackback']) && function_exists( 'fb_comment_type_count' ) ) { ?>
		<h2 class="trackback"><?php fb_comment_type_count( 'trackback', '', __('Ein Trackback', FB_BASIS_TEXTDOMAIN), __('% Trackbacks', FB_BASIS_TEXTDOMAIN) ); ?></h2>
		<ol class="trackbacklist">
			<?php wp_list_comments('type=trackback'); ?>
		</ol>
	<?php } ?>
	
	<section class="navigation nav_comments">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</section>
		
<?php } else { 
	// this is displayed if there are no comments so far 
?>
	
	<?php if ('open' == $post->comment_status) {
		// If comments are open, but there are no comments.
	} else { ?>
	
	<section class="nocomments close">
		<p><?php _e( 'Die Kommentarfunktion f&uuml;r diesen Beitrag ist geschlossen', FB_BASIS_TEXTDOMAIN ); ?></p>
	</section>
	
	<?php 
	}
} ?>
</section>


<?php if ('open' == $post->comment_status) : ?>
<section id="respond">
	<header>
		<h2 id="postcomment"><?php _e('Schreibe einen Kommentar', FB_BASIS_TEXTDOMAIN); ?></h2>
	</header>
	<?php if ( function_exists('cancel_comment_reply_link') ) { ?>
	<div id="cancel-comment-reply">
		<small><?php cancel_comment_reply_link( __( 'Hier klicken um die Antwort abzubrechen.', FB_BASIS_TEXTDOMAIN ) );?></small>
	</div>
	<?php } ?>
	 
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
	<p><?php _e('Du musst', FB_BASIS_TEXTDOMAIN); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>"><?php _e('eingeloggt</a> sein um diesen Beitrag zu kommentieren.', FB_BASIS_TEXTDOMAIN); ?></p>
	
	<?php else : ?>
	
	<?php comment_form(); ?>
	
</section>
<?php endif; // If registration required and not logged in ?>
 
<?php endif; // if you delete this the sky will fall on your head ?>
<!-- end comments --> 

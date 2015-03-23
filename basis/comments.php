<?php
/**
 * @package WordPress
 * @subpackage Basis_Theme
 */
?>
<!-- start comments -->
<?php
$scriptname = htmlspecialchars( $_SERVER['SCRIPT_FILENAME'] );
if ( !empty($scriptname) && 'comments.php' == basename($scriptname) )
	die( __('Bitte lade diese Datei nicht direkt. Danke!', FB_BASIS_TEXTDOMAIN) );


if ( function_exists('post_password_required') ) {
	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('Passwort eingeben, um Kommentare zu sehen.', FB_BASIS_TEXTDOMAIN); ?></p>
		<?php return;
	}
} else {
	if ( !empty($post->post_password) ) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) { // and it doesn't match the cookie  ?>
			<p class="nocomments"><?php _e('Passwort eingeben, um Kommentare zu sehen.', FB_BASIS_TEXTDOMAIN); ?></p>
			<?php return;
		}
	}
}


if ( function_exists('wp_list_comments') ) {
	
	// WP >=2.7 comment Loop
	if ( have_comments() ) { ?>
		
		<div class="navigation nav_comments">
			<div class="alignleft"><?php previous_comments_link() ?></div>
			<div class="alignright"><?php next_comments_link() ?></div>
		</div>
		
		<?php
		$comments_by_type = separate_comments($comments);
		if ( !empty($comments_by_type['comment']) && function_exists( 'fb_comment_type_count' ) ) { ?>
			<h2 id="comments"><?php fb_comment_type_count( 'comment' ); ?></h2>
			<ol class="commentlist">
				<?php wp_list_comments( 'type=comment&callback=fb_theme_comment' ); ?>
			</ol>
		<?php }
		
		// alternativ type pings fuer trackback + pingback
		if ( !empty($comments_by_type['pingback']) && function_exists( 'fb_comment_type_count' ) ) { ?>
			<h2 id="pingback"><?php fb_comment_type_count( 'pingback', '', __('Ein Pingback', FB_BASIS_TEXTDOMAIN), __('% Pingbacks', FB_BASIS_TEXTDOMAIN) ); ?></h2>
			<ol class="pingbacklist">
				<?php wp_list_comments('type=pingback'); ?>
			</ol>
		<?php }
 		
		// alternativ type pings fuer trackback + pingback
		if ( !empty($comments_by_type['trackback']) && function_exists( 'fb_comment_type_count' ) ) { ?>
			<h2 id="trackback"><?php fb_comment_type_count( 'trackback', '', __('Ein Trackback', FB_BASIS_TEXTDOMAIN), __('% Trackbacks', FB_BASIS_TEXTDOMAIN) ); ?></h2>
			<ol class="trackbacklist">
				<?php wp_list_comments('type=trackback'); ?>
			</ol>
		<?php } ?>
		
		<div class="navigation nav_comments">
			<div class="alignleft"><?php previous_comments_link() ?></div>
			<div class="alignright"><?php next_comments_link() ?></div>
		</div>
			
	<?php } else { 
		// this is displayed if there are no comments so far 
	?>
		
		<?php if ('open' == $post->comment_status) {
			// If comments are open, but there are no comments.
		} else { ?>
		
		<p class="nocomments close"><?php _e( 'Die Kommentarfunktion f&uuml;r diesen Beitrag ist geschlossen', FB_BASIS_TEXTDOMAIN ); ?></p>
		
		<?php 
		}
	}

} else {
	
	// WP 2.6 and older Comment Loop
		$oddcomment = 'alt';
	?>
	 
	<!-- You can start editing here. -->
	<?php if ($comments) { ?>
		<h2 id="comments"><?php comments_number( __('Keine Kommentare', FB_BASIS_TEXTDOMAIN), __('Ein Kommentar', FB_BASIS_TEXTDOMAIN), __('% Kommentare', FB_BASIS_TEXTDOMAIN) );?> to &#8220;<?php the_title(); ?>&#8221;</h2> 
		<ol class="commentlist">
	
		<?php foreach ( $comments as $comment ) { ?>
			<li class="<?php echo $oddcomment; ?>" id="comment-<?php comment_ID() ?>">
				Kommentar von <em><?php comment_author_link() ?></em>:
				<?php if ($comment->comment_approved == '0') { ?>
					<em><?php _e('Dein Kommentar muss erst frei geschaltet werden.', FB_BASIS_TEXTDOMAIN) ?></em>
				<?php } ?>
				<br />
				<small class="commentmetadata"><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('l, F jS Y') ?> at <?php comment_time() ?></a> <?php edit_comment_link( __('Edit', FB_BASIS_TEXTDOMAIN), '', ''); ?></small>
				<?php comment_text() ?>
			</li>
	 
			<?php /* Changes every other comment to a different class */	
			if ('alt' == $oddcomment) $oddcomment = '';
			else $oddcomment = 'alt'; ?>
			
		<?php } /* end for each comment */ ?>
		
		</ol>
	
	<?php } else { // this is displayed if there are no comments so far ?>
	
		<?php if ('open' == $post->comment_status) { ?> 
			<!-- If comments are open, but there are no comments. -->
		<?php } else { // comments are closed ?>
			<!-- If comments are closed. -->
			<p  class="nocomments"><?php _e('Kommentare sind geschlossen.', FB_BASIS_TEXTDOMAIN) ?></p>
		<?php }
	}
	
} // 2.6 and older Comment Loop end 
?>


<?php if ('open' == $post->comment_status) : ?>
<div id="respond">
	<h2 id="postcomment"><?php _e('Schreibe einen Kommentar', FB_BASIS_TEXTDOMAIN); ?></h2>
	<?php if ( function_exists('cancel_comment_reply_link') ) { ?>
	<div id="cancel-comment-reply">
		<small><?php cancel_comment_reply_link( __( 'Hier klicken um die Antwort abzubrechen.', FB_BASIS_TEXTDOMAIN ) );?></small>
	</div>
	<?php } ?>
	 
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
	<p><?php _e('Du musst', FB_BASIS_TEXTDOMAIN); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>"><?php _e('eingeloggt</a> sein um diesen Beitrag zu kommentieren.', FB_BASIS_TEXTDOMAIN); ?></p>
	
	<?php else : ?>

	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
		
		<?php if ( $user_ID ) : ?>
		
		<p><?php _e('Du bist eingeloggt als', FB_BASIS_TEXTDOMAIN); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php _e('Hier kannst Du Dich ausloggen', FB_BASIS_TEXTDOMAIN); ?>"><?php _e('Abmelden', FB_BASIS_TEXTDOMAIN); ?></a></p>
		<?php else : ?>
		
		<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
		<label for="author"><small><?php _e('Name', FB_BASIS_TEXTDOMAIN); ?> <?php if ($req) _e('(Pflichtfeld)', FB_BASIS_TEXTDOMAIN); ?></small></label></p>
		
		<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
		<label for="email"><small><?php _e('E-Mail', FB_BASIS_TEXTDOMAIN); ?> <?php if ($req) _e('(Pflichtfeld)', FB_BASIS_TEXTDOMAIN); ?></small></label></p>
		
		<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
		<label for="url"><small><?php _e('Webseite', FB_BASIS_TEXTDOMAIN); ?></small></label></p>
		
		<?php endif; ?>
		<?php comment_id_fields(); ?>
		
		<!--<p><small><strong>XHTML:</strong><?php _e('Du kannst diese Tags verwenden:', FB_BASIS_TEXTDOMAIN); ?> <?php echo allowed_tags(); ?></small></p>-->
		
		<p><textarea name="comment" id="comment" cols="50" rows="10" tabindex="4"></textarea></p>
		<p><input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Kommentar absenden', FB_BASIS_TEXTDOMAIN); ?>" />
		<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
		</p>
		 
		<?php do_action('comment_form', $post->ID); ?>
	</form>
</div>
<?php endif; // If registration required and not logged in ?>
 
<?php endif; // if you delete this the sky will fall on your head ?>
<!-- end comments -->

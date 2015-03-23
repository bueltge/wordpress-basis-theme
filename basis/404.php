<?php
/**
 * @package WordPress
 * @subpackage Basis_Theme
 */

// Sample Redirecting WordPress 404 Error Page
$redirect_301 = array(
		'/2007/11/02/old-page/' => '/2007/11/01/new-page/',
		'/category/old-post/' => '/test/new-post/',
		'/123/ols-post/' => '/new-post/'
);
$request = htmlspecialchars( $_SERVER['REQUEST_URI'] );

if ( array_key_exists($request, $redirect_301) ) {
		header( "HTTP/1.1 301 Moved Permanently" );
		header( 'Location: ' . bloginfo('url') . $redirect_301[$request] );
		die();
}

get_header(); ?>

		<div <?php if (function_exists('post_class')) { post_class(); } else { echo 'class="post"'; } ?> id="post-<?php the_ID(); ?>">
			<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php _e('Oops... Seite nicht gefunden (Fehler 404)', FB_BASIS_TEXTDOMAIN); ?></a></h2>
			<p><?php _e('Entschuldigung, aber deine angeforderte Seite konnte nicht gefunden werden.<br />Benutze doch', FB_BASIS_TEXTDOMAIN); ?></p>
				<ul>
					<li><?php _e('die Suche mit Stichworten nach dem gew&uuml;nschten Inhalt, in dem du Suchbegriffe in das folgende Suchfeld eingibst und anschlie&szlig;end Suche klickst.', FB_BASIS_TEXTDOMAIN); ?><br /><?php include (TEMPLATEPATH . '/searchform.php'); ?></li>
				</ul>
			<p><?php _e('Solltest du von einer anderen Seite durch einen fehlerhaften Link hierher gekommen sein, so freue ich mich, wenn du mir eine Fehlerbeschreibung sendest. Dazu kannst du das', FB_BASIS_TEXTDOMAIN); ?> <a href="<?php bloginfo('url'); ?>/kontakt/"><?php _e('Kontakt-Formular'); ?></a> <?php _e('nutzen. Ich werde mich dann bem&uuml;hen, den Fehler zu beheben. Vielen Dank schon jetzt!', FB_BASIS_TEXTDOMAIN); ?></p>

			<?php
			function get_totalposts() {
				global $wpdb;
				$ID = (int) 'ID';
				$totalposts = $wpdb->get_var("SELECT COUNT($ID)
																			FROM $wpdb->posts
																			WHERE post_status = 'publish'
																			");
				return $totalposts;
			}
			
			// Get Total Comments
			function get_totalcomments() {
				global $wpdb;
				$comment_ID = (int) 'comment_ID';
				$totalcomments = $wpdb->get_var("SELECT COUNT($comment_ID)
																				FROM $wpdb->comments
																				WHERE comment_approved = '1'
																				");
				return $totalcomments;
			} ?>
			<p><?php _e('Aktuell liegen auf dem Blog folgenden Beitr&auml;ge und Kommentarentr&auml;ge.', FB_BASIS_TEXTDOMAIN); ?></p>
			<ul>
				<li><strong><?php echo get_totalposts(); ?></strong> <?php _e('Beitr&auml;ge', FB_BASIS_TEXTDOMAIN); ?></li>
				<li><strong><?php echo get_totalcomments(); ?></strong> <?php _e('Kommentare', FB_BASIS_TEXTDOMAIN); ?></li>
			</ul>

			<h3><?php _e('Journal'); ?></h3>
			<ul>
				<?php wp_get_archives('type=postbypost&limit=10'); ?>
			</ul>
			<?php
				$HTTP_REFERER    = htmlspecialchars( $_SERVER['HTTP_REFERER'] );
				$HTTP_USER_AGENT = htmlspecialchars( $_SERVER['HTTP_USER_AGENT'] );
				$REMOTE_ADDR     = htmlspecialchars( $_SERVER['REMOTE_ADDR'] );
				$REQUEST_URI     = htmlspecialchars( $_SERVER['REQUEST_URI'] );
				$SERVER_NAME     = htmlspecialchars( $_SERVER['SERVER_NAME'] );
				
				echo '<b>Deine Daten: </b><br />';
				echo 'User Agent: ' . $HTTP_USER_AGENT . '<br />';
				echo 'Adresse: ' . $REMOTE_ADDR . '<br />';
				echo 'URL: ' . $SERVER_NAME . $REQUEST_URI;
				
				$count = 0;
	
				if (($HTTP_REFERER == '') || (strpos($HTTP_REFERER, 'wp-admin') !== false) || (strpos($HTTP_REFERER, 'wp-content') !== false) || (strpos($HTTP_REFERER, 'images') !== false)) {
					$count++;
				}
	
				if ($count == 0) {
					$today = date("j F Y, G:i:s");
				
					$message  = __('Datum und Zeit: ', FB_BASIS_TEXTDOMAIN) . $today . "\n";
					$message .= 'Request URL: http://' . $SERVER_NAME . $REQUEST_URI . "\n";
					$message .= 'Referring page: ' . $HTTP_REFERER . "\n\n";
					$message .= 'Client: ' . $HTTP_USER_AGENT . "\n";
					$message .= 'Remote IP: ' . $REMOTE_ADDR . "\n\n";
					$message .= __('Das ist eine automatische Mitteilung, eine Antwort ist vergeblich. Diese Mitteilung wird jedesmal gesendet, wenn ein nicht existierendes Dokument verlangt wird. Damit k&ouml;nnen wir das Problem eventuell beheben.', FB_BASIS_TEXTDOMAIN) . "\n\n";
					$message .= 'Einen schönen Tag noch.';
					
					// Deaktivate
					//mail("info@$SERVER_NAME", "Error 404", $message, "From: info@$SERVER_NAME");
				}
			?>
		</div>
			
<?php get_footer(); ?>

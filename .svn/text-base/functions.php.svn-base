<?php
/**
 * @package WordPress
 * @subpackage Basis_Theme xHTML5
 */

if ( ! isset( $content_width ) )
	$content_width = 640;

/**
 * Setup for WP Basis xHTML5
 */
if ( ! function_exists( 'wpbasis_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 * 
	 * To override wpbasis_setup() in a child theme, add your own wpbasis_setup to your child theme's
	 * functions.php file.
	 * 
	 * @uses add_theme_support()
	 * @uses load_theme_textdomain()
	 * @uses fb_html5_tag_fixer()
	 * @uses fb_html5_wpautop() optional, is commented
	 * @uses fb_widget_register()
	 */
	function wpbasis_setup() {
	
		// This theme uses post thumbnails
		add_theme_support( 'post-thumbnails' );
		
		// add feed links in head of header.php
		add_theme_support( 'automatic-feed-links' );
		
		// Make theme available for translation
		// Translations can be filed in the /languages/ directory
		// define constant for multilanguage-key
		define( 'FB_BASIS_TEXTDOMAIN', 'basis' );
		if ( 'en_US' !== get_locale() )
			load_theme_textdomain( FB_BASIS_TEXTDOMAIN, TEMPLATEPATH . '/languages' );
		
		$locale = get_locale();
		$locale_file = TEMPLATEPATH . "/languages/$locale.php";
		if ( is_readable( $locale_file ) )
			require_once( $locale_file );
	
		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => __( 'Primary Navigation', FB_BASIS_TEXTDOMAIN ),
		) );
		
		// include function for fox html to html5
		foreach( array('the_content', 'the_excerpt', 'comment_text') as $filter )
			add_filter( $filter, 'fb_html5_tag_fixer', 12 );
		/**
		 * alternative compex solution for html-editor in WP backend for right html5
		// remove the original wpautop function and add the new html5 autop
		foreach( array('the_content', 'the_excerpt', 'comment_text') as $filter ) {
			remove_filter( $filter, 'wpautop' );
			add_filter( $filter, 'fb_html5_wpautop' );
		}
		*/
		
		add_action('widgets_init', 'fb_widget_register', 1);
	}
}
add_action( 'after_setup_theme', 'wpbasis_setup' );


/**
 * fix the string for html5
 * small solution
 */
function fb_html5_tag_fixer($content) {
	$content = preg_replace( '/(<.+)\s\/>/', '$1>', $content );
	
	return $content;
}

/**
 * wpautop for HTML5, allowed: table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|map|area|blockquote|address|math|style|input|p|h[1-6]|hr|fieldset|legend|section|article|aside|header|footer|hgroup|figure|details|figcaption|summary)
 * @link http://nicolasgallagher.com/using-html5-elements-in-wordpress-post-content/
 * @author nicolas@nicolasgallagher.com
 */
function fb_html5_wpautop($pee, $br = 1) {
	if ( '' === trim($pee) )
		return '';
	 
	$pee = $pee . "\n"; // just to make things a little easier, pad the end
	$pee = preg_replace( '|<br />\s*<br />|', "\n\n", $pee );
	// Space things out a little
	// *insertion* of section|article|aside|header|footer|hgroup|figure|details|figcaption|summary
	$allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|map|area|blockquote|address|math|style|input|p|h[1-6]|hr|fieldset|legend|section|article|aside|header|footer|hgroup|figure|details|figcaption|summary)';
	$pee = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);
	$pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);
	$pee = str_replace( array("\r\n", "\r"), "\n", $pee ); // cross-platform newlines
	if ( FALSE !== strpos($pee, '<object') ) {
		$pee = preg_replace('|\s*<param([^>]*)>\s*|', "<param$1>", $pee); // no pee inside object/embed
		$pee = preg_replace('|\s*</embed>\s*|', '</embed>', $pee);
	}
	$pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
	// make paragraphs, including one at the end
	$pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);
	$pee = '';
	foreach ( $pees as $tinkle )
		$pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
	$pee = preg_replace('|<p>\s*</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
	// *insertion* of section|article|aside
	$pee = preg_replace('!<p>([^<]+)</(div|address|form|section|article|aside)>!', "<p>$1</p></$2>", $pee);
	$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
	$pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
	$pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
	$pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
	$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
	$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);
	if ($br) {
		$pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', create_function('$matches', 'return str_replace("\n", "<WPPreserveNewline />", $matches[0]);'), $pee);
		$pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
		$pee = str_replace('<WPPreserveNewline />', "\n", $pee);
	}
	$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
	// *insertion* of img|figcaption|summary
	$pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol|img|figcaption|summary)[^>]*>)!', '$1', $pee);
	if ( FALSE !== strpos($pee, '<pre') )
		$pee = preg_replace_callback('!(<pre[^>]*>)(.*?)</pre>!is', 'clean_pre', $pee );
	$pee = preg_replace( "|\n</p>$|", '</p>', $pee );
	$pee = str_replace( array( '/>', ' checked="checked"', ' selected="selected"', ' >' ), array( '>', 'checked', 'selected', '>' ), $pee);
	
	return $pee;
}


// widget function
if ( function_exists( 'register_sidebars' ) )
	register_sidebars(0);

function fb_widget_register() {
	if ( function_exists( 'register_sidebar' ) ) {
		register_sidebar( array(
			'name' => __( 'Sidebar', FB_BASIS_TEXTDOMAIN ),
			'before_widget' => "\n\t\t" . '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => "\n\t" . '<h3 class="widgettitle">',
			'after_title' => '</h3>',
		) );
	}
}


/**
 * comments for > WP 2.7
 */
if ( ! function_exists( 'fb_theme_comment' ) ) {
	function fb_theme_comment( $comment, $args, $depth ) {
		
		$GLOBALS['comment'] = $comment; ?>
		
		<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
			<article id="div-comment-<?php comment_ID(); ?>">

				<header class="comment-author vcard">
					<?php echo get_avatar( $comment, $size = '40' ); ?>
					<div class="comment-meta commentmetadata">
						<?php printf(__('<p><cite class="fn">%s</cite> <span class="says">schrieb am</span></p>', FB_BASIS_TEXTDOMAIN), get_comment_author_link()) ?>
						<span><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf( __('%1$s um %2$s', FB_BASIS_TEXTDOMAIN), get_comment_date(),  get_comment_time() ) ?></a><?php edit_comment_link( __('(Bearbeiten)', FB_BASIS_TEXTDOMAIN), '  ', '' ) ?></span>
					</div>
				</header>
				
				<div class="comment-content">
					<?php if ($comment->comment_approved == '0') {
						echo '<em>' . __('Dein Kommentar muss erst frei geschaltet werden.', FB_BASIS_TEXTDOMAIN) . '</em><br />';
					}
					comment_text(); ?>
				</div>
				
				<div class="reply">
					<?php comment_reply_link(array_merge( $args, array('reply_text' => __('Antworten', FB_BASIS_TEXTDOMAIN), 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</div>
				
			</article>
	<?php
	}
}


/**
 * count for trackback, pingback, comment, pings
 *
 * embed like this:
 * fb_comment_type_count('pings');
 * fb_comment_type_count('comment');
 */
if ( ! function_exists( 'fb_comment_type_count' ) ) {
	function fb_get_comment_type_count( $type='all', $zero = FALSE, $one = FALSE, $more = FALSE, $post_id = 0 ) {
		global $cjd_comment_count_cache, $id, $post;
 
		if ( ! $post_id )
			$post_id = $post->ID;
		if ( ! $post_id )
			return;
		
		if ( !isset($cjd_comment_count_cache[$post_id]) ) {
			$p = get_post($post_id);
			$p = array($p);
			fb_update_comment_type_cache($p);
		}
		;
		if ( $type == 'pingback' || $type == 'trackback' || $type == 'comment' )
			$count = $cjd_comment_count_cache[$post_id][$type];
		elseif ( $type == 'pings' )
			$count = $cjd_comment_count_cache[$post_id]['pingback'] + $cjd_comment_count_cache[$post_id]['trackback'];
		else
			$count = array_sum((array) $cjd_comment_count_cache[$post_id]);
		
		return apply_filters('fb_get_comment_type_count', $count);
	}
	
	// comment, trackback, pingback, pings, all
	function fb_comment_type_count( $type='all', $zero = FALSE, $one = FALSE, $more = FALSE, $post_id = 0 ) {
	
		$number = fb_get_comment_type_count( $type, $zero, $one, $more, $post_id );
		if ($type == 'all') {
			$type_string_single = __('Kommentar', FB_BASIS_TEXTDOMAIN);
			$type_string_plural = __('Kommentare', FB_BASIS_TEXTDOMAIN);
		} elseif ($type == 'pings') {
			$type_string_single = __('Ping und Trackback', FB_BASIS_TEXTDOMAIN);
			$type_string_plural = __('Pings und Trackbacks', FB_BASIS_TEXTDOMAIN);
		} elseif ($type == 'pingback') {
			$type_string_single = __('Pingback', FB_BASIS_TEXTDOMAIN);
			$type_string_plural = __('Pingbacks', FB_BASIS_TEXTDOMAIN);
		} elseif ($type == 'trackback') {
			$type_string_single = __('Trackback', FB_BASIS_TEXTDOMAIN);
			$type_string_plural = __('Trackbacks', FB_BASIS_TEXTDOMAIN);
		} elseif ($type == 'comment') {
			$type_string_single = __('Kommentar', FB_BASIS_TEXTDOMAIN);
			$type_string_plural = __('Kommentare', FB_BASIS_TEXTDOMAIN);
		}
		
		if ( $number > 1 )
			$output = str_replace('%', number_format_i18n($number), ( FALSE === $more ) ? __('%', FB_BASIS_TEXTDOMAIN) . ' ' . $type_string_plural : $more);
		elseif ( $number == 0 )
			$output = ( FALSE === $zero ) ? __('Keine', FB_BASIS_TEXTDOMAIN) . ' ' . $type_string_plural : $zero;
		else // must be one
			$output = ( FALSE === $one ) ? __('Ein', FB_BASIS_TEXTDOMAIN) . ' ' . $type_string_single : $one;
		
		echo apply_filters('fb_comment_type_count', $output, $number);
	}
}

if ( ! function_exists( 'fb_update_comment_type_cache' ) ) {
	function fb_update_comment_type_cache($queried_posts) {
		global $cjd_comment_count_cache, $wpdb;
		
		if ( !$queried_posts )
			return $queried_posts;
		
		foreach ( (array) $queried_posts as $post )
			if ( ! isset($cjd_comment_count_cache[$post->ID]) )
				$post_id_list[] = $post->ID;
		
		if ( isset($post_id_list) && $post_id_list ) {
			$post_id_list = implode(',', $post_id_list);
		
			foreach ( array('', 'pingback', 'trackback') as $type ) {
				$counts = $wpdb->get_results("SELECT ID, COUNT( comment_ID ) AS ccount
							FROM $wpdb->posts
							LEFT JOIN $wpdb->comments ON ( comment_post_ID = ID AND comment_approved = '1' AND comment_type='$type' )
							WHERE (post_status = 'publish' OR (post_status = 'inherit' AND post_type = 'attachment')) AND ID IN ($post_id_list)
							GROUP BY ID");
			
				if ( $counts ) {
					if ( '' == $type )
						$type = 'comment';
					foreach ( $counts as $count )
						$cjd_comment_count_cache[$count->ID][$type] = $count->ccount;
				}
			}
		}

		return $queried_posts;
	}

	add_filter( 'the_posts', 'fb_update_comment_type_cache' );
}


/**
 * void fb_comment_paging_noindex_meta()
 * add meta noindex rules on singular comment page section
 *
 * @author  Avice D <ck+filter@kaizeku.com>
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @link	 http://blog.kaizeku.com/wordpress/prevent-wordpress-27-duplicate-content/
 *
 * @todo	Check for duplicate meta-robots tag generated by
 *			meta-tag type plugins (SEO plugins)
 *
 * @uses	$wp_query Wp_query object
 * @return string Output HTML meta noindex
 */
if ( ! function_exists( 'fb_comment_paging_noindex_meta' ) ) {
	function fb_comment_paging_noindex_meta() {
		global $wp_query;
	
		if ( version_compare( (float) get_bloginfo('version'), 2.7, '>=') ) {
	
			if ( $wp_query->is_singular && get_option('page_comments') ) { // comments pagination enabled
				if ( isset($wp_query->query['cpage'] ) && absint( $wp_query->query['cpage']) >= 1 ) {
					echo '<meta name="robots" content="noindex" />' . PHP_EOL;
				}
			}
		}
	}
	
	add_action( 'wp_head','fb_comment_paging_noindex_meta' );
}
?>

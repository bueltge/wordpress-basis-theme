<?php
/**
 * @package WordPress
 * @subpackage Basis_Theme
 */

// Pre-2.6 compatibility
if ( !defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( !defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( !defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( !defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
if ( !defined( 'WP_LANG_DIR') )
	define( 'WP_LANG_DIR', WP_CONTENT_DIR . '/languages' );

// define constant for multilanguage-key
define( 'FB_BASIS_TEXTDOMAIN', 'basis' );

/**
 * Translate, if applicable
 */
load_theme_textdomain(FB_BASIS_TEXTDOMAIN);
	

/**
 * count diverent values
 * @param: string $type string to count
 * Example: echo fb_counts('cats');
 */
if ( function_exists('wp_count_posts') ) {
	if (!function_exists('fb_counts')) {
		function fb_counts($type){
			if ($type=="posts") {	
				$num_posts = wp_count_posts( 'post' );
				$num_posts = $num_posts->publish; //publish, draft
				$num_posts = sprintf( _n( '%s Beitrag', '%s Beitr&auml;ge', $num_posts ), number_format_i18n( $num_posts ) );
				return $num_posts;
			} elseif ($type=="pages") {
				$num_pages = wp_count_posts( 'page' );
				$num_pages = $num_pages->publish; //publish
				$num_pages = sprintf( _n( '%s Seite', '%s Seiten', $num_pages ), number_format_i18n( $num_pages ) );
				return $num_pages;
			} elseif ($type=="cats") {
				$num_cats  = wp_count_terms('category');
				return $num_cats;
			} elseif ($type=="tags") {
				$num_tags  = wp_count_terms('post_tag');
				return $num_tags;
			} elseif ($type=="comm") {
				$num_comm  = get_comment_count();
				$num_comm  = $num_comm['approved']; //approved, awaiting_moderation, spam, total_comments
				$num_comm  = sprintf( _n( '%s Kommentar', '%s Kommentare', $num_comm ), number_format_i18n( $num_comm ) );
				return $num_comm;
			} elseif ($type=="comm2") {
				$num_comm2 = wp_count_comments( );
				$num_comm2 = $num_comm2->approved; //approved, moderated, spam, total_comments
				return $num_comm2;
			} else {
				return false;
			}
		}
	}
}


// widget function
if ( function_exists('register_sidebars') )
	register_sidebars(0);

if (!function_exists('fb_widget_register')) {
	function fb_widget_register() {
		if ( function_exists('register_sidebar') ) {
			register_sidebar(array(
				'name' => 'Sidebar',
				'before_widget' => "\n\t\t" . '<li id="%1$s" class="widget %2$s">',
				'after_widget' => '</li>',
				'before_title' => "\n\t" . '<h3 class="widgettitle">',
				'after_title' => '</h3>',
			));
		}
	}
	
	add_action('widgets_init', 'fb_widget_register', 1);
}


/**
 * related post with wordpress-tags
 * wordpress > 2.3
 */
if (!function_exists('fb_related_posts')) {
	function fb_get_related_posts($limit) {
		global $wpdb, $post;
		
		$now      = current_time('mysql', 1);
		$tags     = wp_get_post_tags($post->ID);
		if ( $tags ) {
			$taglist  = "'" . str_replace( "'", '', str_replace( '"', '', urldecode( $tags[0]->term_id ) ) ). "'";
			$tagcount = count($tags);
			
			if ( $tagcount > 1 ) {
				for ($i = 1; $i <= $tagcount; $i++) {
					$taglist = $taglist . ", '" . str_replace( "'", '', str_replace( '"', '', urldecode( $tags[$i]->term_id ) ) ) . "'";
				}
			}
			
			$q = "SELECT DISTINCT p.*, count(t_r.object_id) as cnt FROM $wpdb->term_taxonomy t_t, $wpdb->term_relationships t_r, $wpdb->posts p WHERE t_t.taxonomy ='post_tag' AND t_t.term_taxonomy_id = t_r.term_taxonomy_id AND t_r.object_id  = p.ID AND (t_t.term_id IN ($taglist)) AND p.ID != $post->ID AND p.post_status = 'publish' AND p.post_date_gmt < '$now' GROUP BY t_r.object_id ORDER BY cnt DESC, p.post_date_gmt DESC LIMIT $limit;";
		
			return $wpdb->get_results($q);
		}
	}
	
	function fb_related_posts($limit = 5) {
	
		if ( function_exists('get_the_tags') ) {
			$related_posts = fb_get_related_posts($limit) ;
		}
		
		if ( $related_posts ) {
			foreach ($related_posts as $related_post ) {
				$post_date = preg_replace("/(\d+)\-(\d+)\-(\d+) (\d+)\:(\d+)\:(\d+)/is", "$3.$2.$1" , $related_post->post_date);
				$related_post_output = '<li>';
				$related_post_output = $related_post_output . '<a href="' . get_permalink($related_post->ID) . '" title="' . wptexturize($related_post->post_title) . ' (' . $post_date . ')">' . wptexturize($related_post->post_title);
				$related_post_output = $related_post_output . '</a></li>';
				echo $related_post_output;
			}
		} else {
			echo '<li>' . __('Keine &auml;hnlichen Beitr&auml;ge', FB_BASIS_TEXTDOMAIN) . '</li>';
		}	
	}
}


/**
 * related post with category
 * @param: int $limit limit of posts
 * @param: bool $catName echo category name
 * @param: string $title string before all entries
 * Example: echo fb_cat_related_posts();
 */
if ( !function_exists('fb_get_cat_related_posts') ) {
	function fb_get_cat_related_posts( $limit = 5, $catName = TRUE, $title = '<h3>&Auml;hnliche Beitr&auml;ge</h3>' ) {
		
		if ( !is_single() )
			return;
		
		$limit = (int) $limit;
		$output  = '';
		$output .= $title;
		
		$category = get_the_category();
		$category = (int) $category[0]->cat_ID;
		
		if ( $catName )
			$output .= __( 'Kategorie: ', FB_BASIS_TEXTDOMAIN ) . get_cat_name($category) . ' ';
		
		$output .= '<ul>';
		
		$args = array(
			'numberposts' => $limit,
			'category' => $category,
		); 
		
		$recentposts = get_posts( $args );
		foreach($recentposts as $post) {
			setup_postdata($post);
			$output .= '<li><a href="' . get_permalink($post->ID) . '">' . get_the_title($post->ID) . '</a></li>';
		}
		
		$output .= '</ul>';
		
		return $output;
	}
}


/**
 * returns TRUE, if post is in a sub-category, else returns FALSE
 * Example: if (is_in_category(3, 5, 7))
 */
if ( !function_exists('fb_in_category') ) {
	function fb_in_category() {
		if ( func_num_args() > 0 ) {
			foreach( func_get_args() as $category ) {
				if ( in_category($category) ) {
					return( true );
				}
			}
		}
		return false;
	}
}


/**
 * children for pages
 * work: page active --> list the children of the page
 */
if ( !function_exists('get_children_pages') ) {
	function get_children_pages() {
		global $wp_query;
		
		if ( empty($wp_query->post->post_parent) ) {
			$parent = $wp_query->post->ID;
		} else {
			$parent = $wp_query->post->post_parent;
		}
		
		wp_list_pages( 'title_li=&child_of=' . $parent . '&depth=' );
	}
}


/**
 * custom login for theme
 * directory: themes/theme_name/custom-login/
 */
if ( !function_exists('fb_custom_login') ) {
	function fb_custom_login() {
		echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('template_directory') . '/custom-login/custom-login.css" />';
	}

	//add_action('login_head', 'fb_custom_login');
}


/**
 * kill login-screen CSS
 */
//if ( basename( htmlspecialchars($_SERVER['PHP_SELF']) ) == 'wp-login.php' )
//	add_action( 'style_loader_tag', create_function( '$a', "return null;" ) );


/**
 * breadcrumb navigation
 */
if ( !function_exists('fb_breadcrumb_nav') ) {
	function fb_breadcrumb_nav() {
		if ( !is_home() || !is_front_page() ) {
			echo '<p class="breadcrumb"><a href="';
			echo get_option('home');
			echo '">';
			bloginfo('name') . _e('</a> &raquo; ', FB_BASIS_TEXTDOMAIN);
			if ( is_category() || is_single() ) {
				$category = get_the_category();
				$ID = $category[0]->cat_ID;
				echo get_category_parents($ID, TRUE, __(' &raquo; ', FB_BASIS_TEXTDOMAIN), FALSE );
			} elseif (is_page() && $post->post_parent ) {
				_e( get_the_title($post->post_parent) );
				_e(' &raquo; ');
				_e( the_title() );
			} elseif ( is_search() ) {
				_e('Suche nach: ', FB_BASIS_TEXTDOMAIN) . the_search_query() . _e('</p>');
			}
		}
	}
}


/**
 * add shortcode "werbung" (advertisement) with 3 custom fields
 *
 * embed like this:
 * if ( function_exists('add_shortcode') )
 * add_shortcode('fb_werbung', 'fb_example_link');
 *
 * use the quick-tag directly in your content:
 * [fb_werbung]
 */
if ( !function_exists('fb_example_link') ) {
	function fb_example_link() {
		global $wp_query;
		
		$postID     = $wp_query->post->ID;
		$mylink     = get_post_custom_values('link', $postID);
		$mypic      = get_post_custom_values('bild', $postID);
		$mylinktext = get_post_custom_values('linktext', $postID);
		
		return '<a href="' . $mylink[0] . '"><img src="' . $mypic[0] . '" title="' . $mylinktext[0] . '"></a>';
	}
	//if ( function_exists('add_shortcode') )
	//	add_shortcode('fb_werbung', 'fb_example_link');
}


/**
 * add shortcode for import posts into content
 *
 * embed like this:
 * if ( function_exists('add_shortcode') )
 * 	add_shortcode("posts", "fb_get_posts");
 *
 * use the quick-tag directly in your content:
 * [posts num="5" cat="1"]
 */
if ( !function_exists('fb_get_posts') ) {
	function fb_get_posts($atts, $content = null) {
		extract( shortcode_atts( array(
																	"num" => '5',
																	"cat" => '-1'
																	), $atts) );
		global $post;
		
		$myposts = get_posts('numberposts=' . $num . '&order=DESC&orderby=post_date&category=' . $cat);
		$retour  = '<ul>';
		
		foreach($myposts as $post) {
			setup_postdata($post);
			$retour .= '<li><a href="' . get_permalink() . '">' . the_title("", "", false) . '</a></li>';
		}
		
		$retour .= '</ul> ';
		
		return $retour;
	}
	
	//if ( function_exists('add_shortcode') )
	//	add_shortcode("posts", "fb_get_posts");
}


/**
 * add secure mail in content
 *
 * embed like this
 * if ( function_exists('add_shortcode') )
 * 	add_shortcode("posts", "fb_secure_mail");
 *
 * use the quick-tag directly in your content:
 * [sm mailto="foo@bar.com" txt="here is my mail"]
 */
if ( !function_exists('fb_secure_mail') ) {
	function fb_secure_mail($atts) {
		extract( shortcode_atts(array(
																	"mailto" => '',
																	"txt"    => ''
																	), $atts) );
		$mailto = antispambot($mailto);
		$txt    = antispambot($txt);
		return '<a href="mailto:' . $mailto . '">' . $txt . '</a>';
	}
	
	if ( function_exists('add_shortcode') )
		add_shortcode('sm', 'fb_secure_mail');
}



/**
 * add shortcode to kill all shortcodes on the post or page
 *
 * use the quick-tag directly in your content:
 * [KSC][/KSC]
 */
if ( !function_exists('fb_kill_shortcodes') ) {
	function fb_kill_shortcodes( $attributes, $content=NULL ) {
		return $content;
	}
	
	add_shortcode('KSC', 'fb_kill_shortcodes');
}


/**
 * activate shortcodes within widgets
 */
// add_filter('widget_text', 'do_shortcode');


/**
 * return country depending on the user IP
 * embedding and usage e.g. in the template as follows:
 * echo "Nationality: " . getUserCountry();
 * --> Nationality: DE
 */
if ( !function_exists('fb_getUserCountry') ) {
	function fb_getUserCountry() {
		$url = 'http://api.wipmania.com/' . htmlspecialchars($_SERVER ['REMOTE_ADDR']) . '?' . get_bloginfo('home');
		if ( function_exists('file_get_contents') ) {
			return file_get_contents($url);
		} else {
			$ch = curl_init();
			$timeout = 5; 
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$content = curl_exec($ch);
			curl_close($ch);
			
			return $content;
		}
	}
}


/**
 * Smart cache-busting
 * cacht css des Themes
 */
if ( !function_exists('fb_css_cache_buster') ) {
	function fb_css_cache_buster($info, $show) {
		if ( !isset($pieces[1]) )
			$pieces[1] = '';
		if ( 'stylesheet_url' == $show ) {
			
			// Is there already a querystring? If so, add to the end of that.
			if (strpos($pieces[1], '?') === false) {
				return $info . "?" . filemtime(WP_CONTENT_DIR . $pieces[1]);
			} else {
				$morsels = explode("?", $pieces[1]);
				return $info . "&" . filemtime(WP_CONTENT_DIR . $morsles[1]);
			}
		} else {
			return $info;
		}
	}
	
	add_filter('bloginfo_url', 'fb_css_cache_buster', 9999, 2);
}


/**
 * Tags within the dropdown-menu
 *
 * embed like this:
 * <select name="tag-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
 * 	<option value="#">Liste d'auteurs</option>
 * 	<?php fb_dropdown_tag_cloud('number=0&order=asc'); ?>
 * </select>
 */
if ( !function_exists('fb_dropdown_tag_cloud') ) {
	function fb_dropdown_tag_cloud( $args = '' ) {
		$defaults = array(
			'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => 45,
			'format' => 'flat', 'orderby' => 'name', 'order' => 'ASC',
			'exclude' => '', 'include' => ''
		);
		$args = wp_parse_args( $args, $defaults );
	
		$tags = get_tags( array_merge($args, array('orderby' => 'count', 'order' => 'DESC')) ); // Always query top tags
	
		if ( empty($tags) )
			return;
	
		$return = fb_dropdown_generate_tag_cloud( $tags, $args ); // Here's where those top tags get sorted according to $args
		if ( is_wp_error( $return ) )
			return false;
		else
			echo apply_filters( 'dropdown_tag_cloud', $return, $args );
	}
	
	function fb_dropdown_generate_tag_cloud( $tags, $args = '' ) {
		global $wp_rewrite;
		$defaults = array(
			'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => 45,
			'format' => 'flat', 'orderby' => 'name', 'order' => 'ASC'
		);
		$args = wp_parse_args( $args, $defaults );
		extract($args);
	
		if ( !$tags )
			return;
		$counts = $tag_links = array();
		foreach ( (array) $tags as $tag ) {
			$counts[$tag->name] = $tag->count;
			$tag_links[$tag->name] = get_tag_link( $tag->term_id );
			if ( is_wp_error( $tag_links[$tag->name] ) )
				return $tag_links[$tag->name];
			$tag_ids[$tag->name] = $tag->term_id;
		}
	
		$min_count = min($counts);
		$spread = max($counts) - $min_count;
		if ( $spread <= 0 )
			$spread = 1;
		$font_spread = $largest - $smallest;
		if ( $font_spread <= 0 )
			$font_spread = 1;
		$font_step = $font_spread / $spread;
	
		// SQL cannot save you; this is a second (potentially different) sort on a subset of data.
		if ( 'name' == $orderby )
			uksort($counts, 'strnatcasecmp');
		else
			asort($counts);
	
		if ( 'DESC' == $order )
			$counts = array_reverse( $counts, true );
	
		$a = array();
	
		$rel = ( is_object($wp_rewrite) && $wp_rewrite->using_permalinks() ) ? ' rel="tag"' : '';
	
		foreach ( $counts as $tag => $count ) {
			$tag_id = $tag_ids[$tag];
			$tag_link = clean_url($tag_links[$tag]);
			$tag = str_replace(' ', '&nbsp;', wp_specialchars( $tag ));
			$a[] = "\t<option value='$tag_link'>$tag ($count)</option>";
		}
	
		switch ( $format ) :
		case 'array' :
			$return =& $a;
			break;
		case 'list' :
			$return = "<ul class='wp-tag-cloud'>\n\t<li>";
			$return .= join("</li>\n\t<li>", $a);
			$return .= "</li>\n</ul>\n";
			break;
		default :
			$return = join("\n", $a);
			break;
		endswitch;
	
		return apply_filters( 'dropdown_generate_tag_cloud', $return, $tags, $args );
	}
}


/**
 * exclude categories from the loop
 *
 * embed like this:
 * add_action('pre_get_posts', 'fb_exclude_category');
 */
if ( !function_exists('fb_exclude_category') ) {
	function fb_exclude_category() {
		global $wp_query;
	
		if ( is_home() ) {  // you can choose other conditional tags here
			$wp_query->set( 'category__not_in', array(3, 5) );
		}
	}
	//if (!is_admin))
	//add_action('pre_get_posts', 'exclude_category');
}


/**
 * remove /category/ automatically
 *
 * embed like this:
 * add_filter( 'user_trailingslashit', 'fb_fix_slash', 55, 2 );
 */
if ( !function_exists('fb_fix_slash') ) {
	function fb_fix_slash( $string, $type ) {
		global $wp_rewrite;
		if ( $wp_rewrite->use_trailing_slashes == false ) {
			if ( $type != 'single' && $type != 'category' )
				return trailingslashit( $string );
	
			if ( $type == 'single' && ( strpos( $string, '.html/' ) !== false ) )
				return trailingslashit( $string );
	
			if ( $type == 'category' && ( strpos( $string, 'category' ) !== false ) ) {
				$aa_g = str_replace( "/category/", "/", $string );
				return trailingslashit( $aa_g );
			} 
			if ( $type == 'category' )
				return trailingslashit( $string );
		} 
		return $string;
	}
	//add_filter( 'user_trailingslashit', 'fb_fix_slash', 55, 2 );
}


/**
 * add link attribute
 *
 * embed like this:
 * <?php fb_add_link_attr('function', 'arguments', 'attribute(s)', imgtag); ?>
 * 
 * embed like this:
 * <?php fb_add_link_attr('wp_list_pages', 'title_li=', 'rel="nofollow"'); ?>
 * <?php fb_add_link_attr('get_calendar', '2', 'target="content_frame" style="font-weight: bold;"'); ?>
 * <?php fb_add_link_attr('get_linksbyname', 'Blogs, <li>, </li>, <br />, FALSE, name, TRUE', 'onclick="window.open(this.href,\'_blank\');return false;"'); ?>
 * <?php fb_add_link_attr('the_content', 'Read on...', 'class="post-links"'); ?>
 * <?php fb_add_link_attr('get_links_list', '', 'width="30" height="20"', TRUE); ?>
*/
if ( !function_exists('fb_add_link_attr') ) {
	function fb_add_link_attr($func='', $args='', $attr='', $imgtag=false) {
	
		$tag = ($imgtag) ?  "<img " : "<a ";
		$tag_attr = $tag . $attr . ' ';
	
		$func_args = array();
		if(!is_array($args)) {
			$args_array = preg_split('/[\,]+/',$args);
			foreach ($args_array as $arg_array) {    
				array_push($func_args, $arg_array);
			}
		} else {
			$func_args = $args;
		}
	
		if($func) {
			$func_args = array_map('trim', $func_args);
	
			ob_start();
			call_user_func_array($func, $func_args);
			$input = array(ob_get_contents());
			ob_end_clean();
	
			foreach($input as $line) {
				$output .= preg_replace("/$tag/", $tag_attr, $line);
			}
			echo $output;
		}
	}
}


/*
 * Thickbox scan, add class="thickbox" to img tags
 *
 * embed like this:
 * add_filter('the_content', 'fb_add_thickbox', 2);
 */
if ( !function_exists('fb_add_thickbox') ) {
	function fb_add_thickbox($content){
	
		$content = preg_replace('/<a(.*?)href="(.*?).(jpg|jpeg|png|gif|bmp|ico)"(.*?)><img/U', '<a$1href="$2.$3" $4 class="thickbox"><img', $content);
	
		return $content;
	}
	//add_filter('the_content', 'fb_add_thickbox', 2);
}


/**
 * puplish feed content later
 * $where is default-var in WordPress (wp-includes/query.php)
 * this function SQL-syntax
 *
 * embed like this:
 * add_filter('posts_where', 'fbpulish_later_on_feed');
 */
if ( !function_exists('fb_pulish_later_on_feed') ) {
	function fb_pulish_later_on_feed($where) {
		global $wpdb;
		
		if ( is_feed() && is_category('6') ) {
			// timestamp in WP-formatting
			$now = gmdate('Y-m-d H:i:s');
			
			// value for wait; + device
			$wait = '10'; // integer
			
			// http://dev.mysql.com/doc/refman/5.0/en/date-and-time-functions.html#function_timestampdiff
			$device = 'MINUTE'; //MINUTE, HOUR, DAY, WEEK, MONTH, YEAR
			
			// add SQL-sytax to default $where
			$where .= " AND TIMESTAMPDIFF($device, $wpdb->posts.post_date_gmt, '$now') > $wait ";
		}
		return $where;
	}
	//add_filter('posts_where', 'fbpulish_later_on_feed');
}


/**
 * no pings from your own site
 * also as plugin: http://wordpress.org/extend/plugins/no-self-ping/
 */
if ( !function_exists('fb_noself_ping') ) {
	function fb_noself_ping(&$links) {
		$home = get_option('home');
		foreach($links as $l => $link)
			if ( 0 === strpos($link, $home) )
				unset($links[$l]);
	}
	
	add_action( 'pre_ping', 'fb_noself_ping' );
}


/**
 * comments for > WP 2.7
 */
if ( !function_exists('fb_theme_comment') ) {
	function fb_theme_comment($comment, $args, $depth) {
		
		$GLOBALS['comment'] = $comment; ?>
		
		<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
			<div id="div-comment-<?php comment_ID(); ?>">

				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, $size = '40' ); ?>
					<div class="comment-meta commentmetadata">
						<?php printf(__('<p><cite class="fn">%s</cite> <span class="says">schrieb am</span></p>', FB_BASIS_TEXTDOMAIN), get_comment_author_link()) ?>
						<span><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf( __('%1$s um %2$s', FB_BASIS_TEXTDOMAIN), get_comment_date(),  get_comment_time() ) ?></a><?php edit_comment_link( __('(Bearbeiten)', FB_BASIS_TEXTDOMAIN), '  ', '' ) ?></span>
					</div>
				</div>
				
				<?php if ($comment->comment_approved == '0') {
					echo '<em>' . __('Dein Kommentar muss erst frei geschaltet werden.', FB_BASIS_TEXTDOMAIN) . '</em><br />';
				} ?>
	
				<?php comment_text() ?>
	
				<div class="reply">
					<?php comment_reply_link(array_merge( $args, array('reply_text' => __('Antworten', FB_BASIS_TEXTDOMAIN), 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</div>
				
			</div>
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
if ( !function_exists('fb_comment_type_count') ) {
	function fb_get_comment_type_count( $type='all', $zero = false, $one = false, $more = false, $post_id = 0) {
		global $cjd_comment_count_cache, $id, $post;
 
		if ( !$post_id )
			$post_id = $post->ID;
		if ( !$post_id )
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
	function fb_comment_type_count( $type='all', $zero = false, $one = false, $more = false, $post_id = 0 ) {
	
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
			$output = str_replace('%', number_format_i18n($number), ( false === $more ) ? __('%', FB_BASIS_TEXTDOMAIN) . ' ' . $type_string_plural : $more);
		elseif ( $number == 0 )
			$output = ( false === $zero ) ? __('Keine', FB_BASIS_TEXTDOMAIN) . ' ' . $type_string_plural : $zero;
		else // must be one
			$output = ( false === $one ) ? __('Ein', FB_BASIS_TEXTDOMAIN) . ' ' . $type_string_single : $one;
		
		echo apply_filters('fb_comment_type_count', $output, $number);
	}
}
 
if ( !function_exists('fb_update_comment_type_cache') ) {
	function fb_update_comment_type_cache($queried_posts) {
		global $cjd_comment_count_cache, $wpdb;
 
		if ( !$queried_posts )
			return $queried_posts;
 
		foreach ( (array) $queried_posts as $post )
			if ( !isset($cjd_comment_count_cache[$post->ID]) )
				$post_id_list[] = $post->ID;
 
		if ( $post_id_list ) {
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
 
	add_filter('the_posts', 'fb_update_comment_type_cache');
}


/**
 * void fb_comment_paging_noindex_meta()
 * add meta noindex rules on singular comment page section
 *
 * @author  Avice D <ck+filter@kaizeku.com>
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @link    http://blog.kaizeku.com/wordpress/prevent-wordpress-27-duplicate-content/
 *
 * @todo   Check for duplicate meta-robots tag generated by
 *         meta-tag type plugins (SEO plugins)
 *
 * @uses   $wp_query Wp_query object
 * @return string Output HTML meta noindex
 */
if ( !function_exists('fb_comment_paging_noindex_meta') ) {
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
	
	add_action('wp_head','fb_comment_paging_noindex_meta');
}


/**
 * check for search-engine vistors
 *
 * embed like this:
 * if ( fb_fromasearchengine() ) {  
 * 	ADD YOUR MARKUP HERE, BANNERS, COMMERCIALS ETC.
 * }
 */
if ( !function_exists('fb_fromasearchengine') ) {
	function fb_fromasearchengine(){  
		$ref = htmlspecialchars( $_SERVER['HTTP_REFERER'] );
		$se  = array('/search?', 'images.google.', 'search.', 'del.icio.us/search', '/search/', '.yahoo.');  
		foreach ($se as $source) {
			if ( strpos($ref, $source) !== false ) {
				setcookie( 'sevisitor', 1, time()+3600, '/', str_replace( 'http://', '', get_bloginfo('url') ) );
				$sevisitor = true;
			}
		}
		
		if ( $sevisitor == true || $_COOKIE["sevisitor"] == 1 ) {  
			return true;
		}
		return false;
	}
}


/**
 * remove the more-anchor-tag
 */
if ( !function_exists('fb_remove_more_anchor') ) {
	function fb_remove_more_anchor($content) {
		global $id;
		
		return str_replace('#more-' . $id . '"', '"', $content);
	}
	
	//add_filter('the_content', 'fb_remove_more_anchor');
}

/**
 * delete IP and user-agent identification of all no-spam comments older than 15 days
 */
if ( !function_exists('fb_delete_comments_data') ) {
	function fb_delete_comments_data() {
		global $wpdb;
		
		$r = $wpdb->query ("
		              UPDATE $wpdb->comments
		                 SET comment_author_IP = '',
		                     comment_agent = ''
		               WHERE comment_approved = '1'
		                 AND comment_date < date_sub(now(), interval 15 day)
		                   ");
	}
	
	//add_action('comment_post', 'fb_delete_comments_data');
}


/**
 * automatically generate an excerpt
 */
if ( !function_exists('fb_excerpt') ) {
	function fb_excerpt($text) {
		if ( '' == $text ) {
			$text = get_the_content('');
			$text = strip_shortcodes( $text );
			$text = apply_filters('the_content', $text);
			$text = str_replace(']]>', ']]&gt;', $text);
			$text = strip_tags($text);
			$excerpt_length = 55;
			$words = explode(' ', $text, $excerpt_length + 1);
			if (count($words) > $excerpt_length) {
				array_pop($words);
				array_push($words, __('Weiterlesen' , FB_BASIS_TEXTDOMAIN) );
				$text = implode(' ', $words);
			}
		}
		
		return $text;
	}

	add_filter('get_the_excerpt', 'fb_excerpt', 1, 2);
}


/**
 * alternate doctype
 *
 * embed like this 
 * e.g. in a template or inbetween conditional tags
 * fb_alternate_doctype();
 */
if ( !function_exists('fb_alternate_doctype') ) {
	function fb_alternate_doctype() {
		
		function start_output_collection() {
			global $my_header_output_buffer;
			$my_header_output_buffer = ob_start('plugin_callback');
		}
		
		function send_output() {
			global $my_header_output_buffer;
			
			if ( false !== $my_header_output_buffer )
				$header = ob_get_contents( $my_header_output_buffer );
			if ( false === $header )
				die( 'send_output() failed to retrieve header output buffer. File: ' . __FILE__ . ' on line: ' . __LINE__ );
			if ( false === ob_end_clean( $my_header_output_buffer ) )
				error_log( 'send_output() received a failure when trying to remove an output buffer. File: ' . __FILE__ . ' on line: ' . __LINE__ );
			
			$newdoctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
			//$newdoctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
			//$newdoctype = '<!DOCTYPE html>';
			
			$header = preg_replace( '|<!DOCTYPE[^>]*>|i', $newdoctype, $header );
		
			echo $header;
		}
		
		add_action( 'get_header', 'start_output_collection', 1000 );
		add_action( 'wp_head', 'send_output', 1000 );
	}
}


/**
 * get a short/tiny url
 * @author: René Ade
 * @link: http://www.rene-ade.de/inhalte/php-code-zum-erstellen-einer-tinyurl-ueber-tinyurl-com-api.html
 */
if ( !function_exists('fb_gettinyurl') ) {
	function fb_gettinyurl( $url ) {
	
		$fp = fopen( 'http://tinyurl.com/api-create.php?url=' . $url, 'r' );
		if ( $fp ) {
			$tinyurl = fgets( $fp );
			if( $tinyurl && !empty($tinyurl) )
				$url = $tinyurl;
			fclose( $fp );
		}
	 
		return $url;
	}
}


/**
 * filter rewrite-rule for performance problems on post/pages with permalinks
 */
if ( !function_exists('fb_filter_rewrite_attachment') ) {
	function fb_filter_rewrite_attachment($content) {
		if ( !is_array($content) )
			return $content;
	
		foreach ($content as $key => $val) {
			if (strpos($val, 'attachment') !== false)
				unset($content[$key]);
			}
	
		return $content;
	}
	
	//add_filter('page_rewrite_rules', 'fb_filter_rewrite_attachment');
	//add_filter('post_rewrite_rules', 'fb_filter_rewrite_attachment');
}

/**
 * @return useragent
 * example: iPhone, iPod
 * example code: if ( (fb_agent("iPhone") != FALSE) || (fb_agent("iPod") != FALSE) ) { ... }
 */
if ( !function_exists('fb_agent') ) {
	function fb_agent($browser) {
		$useragent = htmlspecialchars( $_SERVER['HTTP_USER_AGENT'] );

		return strstr($useragent, $browser);
	}
}

/**
 * @echo tags of post
 * example code: <?php if (is_single() && function_exists('fb_meta_tags') ) { fb_meta_tags(); echo "\n"; } ?>
 */
if ( !function_exists('fb_meta_tags') ) {
	function fb_meta_tags() {
		global $post;
		
		$posttags = get_the_tags($post->ID);
		if ( $posttags ) {
			foreach( (array)$posttags as $tag ) {
				$meta_tags .= $tag->name . ',';
			}
			$meta_tags = rtrim($meta_tags , ',');
			
			echo '<meta name="keywords" content="' . $meta_tags . '" />' . PHP_EOL;
		}
	}
}


/**
 * clean excerpt from scripts and styles
 */
if ( !function_exists('fb_clean_excerpt') ) {
	function fb_clean_excerpt($excerpt) {
		$excerpt = preg_replace('#<(s(cript|tyle)).*?</\1>#si', '', $excerpt);
		
		return $excerpt;
	}

	add_filter('the_content', 'fb_clean_excerpt', 10, 1);
}


/**
 * remove [...] from excerpt
 */
if ( !function_exists('fb_trim_excerpt') ) {
	function fb_trim_excerpt($excerpt) {
		$excerpt = rtrim($excerpt, '[...]');
		
		return $excerpt;
	}
	
	//add_filter( 'get_the_excerpt', 'fb_trim_excerpt' );
}


/**
 * remove nofollow from comment-author-link
 */
if ( !function_exists('fb_strip_nofollow') ) {
	function fb_strip_nofollow($ret) {
		
		$ret = str_replace( "rel='external nofollow'", "rel='external'", $ret );
		
		return $ret;
	}
	
	add_filter( 'get_comment_author_link', 'fb_strip_nofollow' );
}


/**
 * check page-ID and child-page-ID
 * @link: http://bueltge.de/wordpress-seiten-unterseiten-abfragen/908/
 */
if ( !function_exists('fb_has_parent') ) {
	function fb_has_parent( $post, $post_id ) {
		if ($post->ID == $post_id)
			return true;
		elseif ($post->post_parent == 0)
			return false;
		else
			return fb_has_parent( get_post($post->post_parent), $post_id );
	}
}


/**
 * disable canonical URL redirection
 * feature of WP 2.3 and higher
 */
//remove_filter( 'template_redirect', 'redirect_canonical' );


/**
 * Add contact fields
 * only at WordPress 2.9 and higher
 * @link http://core.trac.wordpress.org/ticket/10240
 */
function fb_add_twitter_contactmethod( $contactmethods ) {
	// Add Twitter
	$contactmethods['twitter'] = 'Twitter';
	// Remove Yahoo IM
	unset($contactmethods['yim']);
	
	return $contactmethods;
}
//add_filter( 'user_contactmethods', 'fb_add_twitter_contactmethod', 10, 1 );


/**
 * select all users that have a specific role
 * @link: http://www.johnkolbert.com/wordpress/how-to-get-all-users-with-a-specific-role/
 */
if ( !function_exists('fb_getUsersByRole') ) {
	function fb_getUsersByRole( $role ) {
		if ( class_exists( 'WP_User_Search' ) ) {
			$wp_user_search = new WP_User_Search( '', '', $role );
			$userIDs = $wp_user_search->get_results();
		} else {
			global $wpdb;
			$userIDs = $wpdb->get_col('
				SELECT ID
				FROM ' . $wpdb->users . ' INNER JOIN ' . $wpdb->usermeta . '
				ON '.$wpdb->users.' . ID = ' . $wpdb->usermeta . '.user_id
				WHERE ' . $wpdb->usermeta . '.meta_key = \'' . $wpdb->prefix . 'capabilities\'
				AND '.$wpdb->usermeta.'.meta_value LIKE \'%"'.$role.'"%\'
			');
		}
		return $userIDs;
	}
}


/**
 *	remove_widows()
 *	filter the_title() to remove any chance of a typographic widow
 *	typographic widows
 *	@param string $title
 *	@return string $title;
 */
if ( !function_exists('fb_remove_widows') ) {
	function fb_remove_widows($title) {
		preg_replace( "/\s(?=\S+$)/", "&nbsp;", $title );
		
		return $title;
	}
	add_filter( 'the_title', 'fb_remove_widows' );
}
?>

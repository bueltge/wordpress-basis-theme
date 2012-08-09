<?php
/**
 * Custom admin and login branding for WordPress
 * 
 * @package  WP Basis
 * @since    05/31/2012  0.0.1
 * @version  05/31/2012
 * @author   fb
 */

class Wp_Basis_Admin_Branding {
	
	/**
	 * Container for this class' arguments
	 *
	 * @access protected
	 */
	protected $args;

	/**
	 * Constructor.  Takes an array of args that customize various aspects of
	 * the login and admin areas.
	 *
	 * The args
	 * `login_url`       - Where would you like the logo above the login form to link? Defaults to wordpress.org
	 * `login_image`     - What will replace the WordPress logo on the login page.
	 * `login_title`     - The title attribute on the logo link on the login page.
	 * `login_height`    - Height of the login logo image.
	 * `login_width`     - Width of the login login logo image. ~320px is recommended. Defaults to 326px.
	 * `designer_url`    - Used in the credit link on the login and admin pages. Your website!
	 * `designer_anchor` - Anchor text for the credit link.
	 * `favicon_url`     - The favicon to be added on the login and admin pages and on the front end.
	 * `remove_wp`       - Remove the WordPress drop down from the admin menu bar if set to true. The Default is false.
	 * `remove_gravatar` - Remove Gravatar Icon on WordPress Admin Bar
	 *
	 * @param array $args See above
	 */
	public function __construct( $args = array() ) {
		
		$this->args = wp_parse_args(
			$args,
			array(
				'login_url'       => home_url( '/' ),
				'login_image'     => FALSE,
				'login_title'     => get_bloginfo( 'name', 'display' ),
				'login_height'    => '67px',
				'login_width'     => '326px',
				'designer_url'    => 'http://bueltge.de',
				'designer_anchor' => 'Frank Bültge',
				'favicon_url'     => TRUE,
				'remove_wp'       => TRUE,
				'remove_gravatar' => FALSE
			)
		);

		add_filter( 'login_headerurl',   array( $this, 'login_headerurl' ) );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ) );
		add_filter( 'login_headertitle', array( $this, 'login_headertitle' ) );
		add_action( 'login_head',        array( $this, 'login_head' ) );
		add_action( 'login_head',        array( $this, 'add_favicon' ) );
		add_action( 'login_footer',      array( $this, 'login_footer' ) );
		add_action( 'admin_head',        array( $this, 'add_favicon' ) );
		//add_action( 'wp_head',         array( $this, 'add_favicon' ) );
		add_action( 'admin_bar_menu',    array( $this, 'admin_bar_menu' ), 25 );
		// remove gravatar in admin bar
		add_action( 'admin_bar_menu',    array( $this, 'add_gravatar_filter' ), 0 );
		add_action( 'admin_bar_menu',    array( $this, 'remove_gravatar' ),    10 );
	}

	/**
	 * Change the `login_headerurl` to whatever was specified in $args
	 *
	 * @access public
	 */
	public function login_headerurl( $url ) {
		
		return esc_url( $this->args['login_url'] );
	}

	/**
	 * Change `login_headerurl` to what was specified in the $args
	 *
	 * @access public
	 */
	public function login_headertitle( $title ) {
		
		return esc_attr( $this->args['login_title'] );
	}

	/**
	 * Login header.  Adds the favicon and `login_image` specified in the $args
	 *
	 * @access public
	 */
	public function login_head() {
		
		$this->add_favicon();
		
		echo "\t<style type='text/css'>\n";
		
		if ( $this->args['login_image'] ) {
			printf(
				"\t.login h1 a { background: url(%s) no-repeat top center; height: %s; width: %s }\n",
				esc_url( $this->args['login_image'] ),
				$this->args['login_height'],
				$this->args['login_width']
			);
		}
		
		echo "\t" . '.login h1 a { background-image: none; line-height: 1; text-indent: 0; text-align: right; height: auto; width: auto; }';
		echo "\t" . '.custom-login-branding { position: absolute; right: 5px; bottom: 5px; text-align: right; }';
		echo "\t</style>\n";
	}

	/**
	 * Adds the favicon specified in the $args
	 *
	 * @access public
	 */
	public function add_favicon() {
		
		if ( ! $this->args['favicon_url'] )
			return;
		// default in root
		if ( TRUE === $this->args['favicon_url'] )
			$this->args['favicon_url'] = home_url( '/favicon.ico' );
		
		printf(
			"\t<link rel='shortcut icon' href='%s' />\n",
			esc_url( $this->args['favicon_url'] )
		);
	}

	/**
	 * Spit out the designer credits (if present in $args) on the login footer
	 *
	 * @access public
	 */
	public function login_footer() {
		
		if ( $link = $this->designer_link() )
			echo '<div class="custom-login-branding">' . $link . '</div>' . "\n";
	}

	/**
	 * Adds the designer link (`$args['designer_url']` & `args['designer_anchor`])
	 * to the admin footer.
	 *
	 * @access public
	 */
	public function admin_footer_text( $text ) {
		
		$text_arr = explode( ' &bull; ', $text );
		
		if ( $designer = $this->designer_link() )
			array_unshift( $text_arr, $designer );
		
		return implode( ' &bull; ', $text_arr );
	}

	/**
	 * Maybe removes the "W" logo from the admin menu
	 *
	 * @access public
	 */
	public function admin_bar_menu( $admin_bar ) {
		
		if ( ! $this->args['remove_wp'] )
			return;
		
		$admin_bar->remove_node( 'wp-logo' );
	}
	
	/**
	 * Add filter to Remove Gravatar Icon in Admin Bar
	 * 
	 * @return  void
	 */
	public function add_gravatar_filter() {
		
		if ( ! $this->args['remove_gravatar'] )
			return;
		
		add_filter( 'pre_option_show_avatars', '__return_zero' );
	}
	
	/**
	 * Remove Gravatar Icon in Admin Bar
	 * 
	 * @return  void
	 */
	public function remove_gravatar() {
		
		if ( ! $this->args['remove_gravatar'] )
			return;
		
		add_filter( 'pre_option_show_avatars', '__return_zero' );
	}
	
	/**
	 * Make a nice designer credit link
	 *
	 * @access protected
	 */
	protected function designer_link() {
		
		if ( $this->args['designer_url'] && $this->args['designer_anchor'] ) {
			return sprintf(
				'<a href="%1$s" title="%2$s" rel="external">%2$s</a>',
				esc_url( $this->args['designer_url'] ),
				esc_attr( $this->args['designer_anchor'] )
			);
		}
	
	}
	
} // end class

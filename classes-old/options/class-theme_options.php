<?php
/**
 * @package WordPress
 * @subpackage WP-Basis Theme
 * @template class template
 * @since 0.0.1
 */

/**
 * Creates a single theme options page
 *
 * @todo jQuery Color Picker, makes better or easier
 * @todo Fill help tab
 * @todo Reset, Export, Import
 * @todo Implement groups (fieldset)
 *
 * @version 1.0
 * @author Thomas Scholz
 *
 */
class Wp_Basis_Theme_Options {
	/**
	 * For translations
	 *
	 * @var string
	 */
	public $text_domain        = NULL;

	/**
	 * Theme option page
	 *
	 * @var array
	 */
	public $page              = array ();

	/**
	 * Action to perform when the data are saved.
	 *
	 * @var string
	 */
	public $save_action        = '';

	/**
	 * Input fields
	 *
	 * @var array
	 */
	protected $fields             = array ();

	/**
	 * All per default allowed input types. Other fields are set to type 'text'.
	 * @filter 'ttt_theme_options_allowed_input_types'
	 *
	 * @see http://www.whatwg.org/specs/web-apps/current-work/multipage/the-input-element.html
	 * 'reset' and 'button' types are not allowed due to accessibility problems
	 *
	 * @var array
	 */
	protected $allowed_input_types = array (
			'checkbox'
		,	'color'
		,	'date'
		,	'datetime'
		,	'datetime-local'
		,	'email'
		,	'file'
		,	'hidden'
		,	'image'
		,	'month'
		,	'number'
		,	'password'
		,	'radio'
		,	'range'
		,	'search'
		,	'select'
		,	'submit'
		,	'tel'
		,	'textarea'
		,	'time'
		,	'url'
		,	'week'
		)
	;

	/**
	 * Page description. Printed on top.
	 *
	 * @var string
	 */
	protected $page_desc = '';

	/**
	 * Basic setup.
	 *
	 * @since 0.1
	 */
	public function __construct( $page ) {
		
		$this -> text_domain = self :: get_text_domain();
		
		$this->allowed_input_types = apply_filters(
			'ttt_theme_options_allowed_input_types',
			$this->allowed_input_types
		);
		
		if ( current_user_can( 'edit_theme_options' ) ) {
			$this -> page        = $this -> prepare_page_data( $page );
			$this -> save_action = 'save_' . $this -> page['slug'];
		
			add_action(
				'admin_post_' . $this -> save_action,
				array ( $this, 'save_options' )
			);
			add_action( 'admin_menu', array ( $this, 'add_options_menus' ) );
		}
	}

	public static function get_text_domain() {
		
		return Wp_Basis_Core :: get_text_domain();
	}

	/**
	 * Writes the theme option to the db.
	 *
	 * @todo Recreate the stylesheet on save.
	 * @return void
	 */
	public function save_options()
	{
		check_admin_referer( 'theme-settings' );

		! current_user_can( 'edit_theme_options' )
			and wp_die( __( 'You are not authorised to perform this operation.' ) );

		foreach ( $this->fields as $key => $value )
		{
			if ( 'file' == $this->fields[$key]['type'] )
			{
				continue;
			}
			// Upload fields are not in $_POST, just in $_FILES
			if ( isset ( $_POST[ $key ] ) )
			{
				set_theme_mod( $key, $_POST[ $key ] );
			}
			elseif ( 'checkbox' == $this->fields[$key]['type'] and 'on' == $this->fields[$key]['defaultvalue'])
			{
				set_theme_mod( $key, 'off' );
			}
			else
			{
				remove_theme_mod( $key );
			}
		}

		# debugging
		#pre_dump( $_POST ); pre_dump( $_FILES ); exit;

		$this->handle_uploads();

		// We may need this to create files based on this date.
		set_theme_mod( '_last_update', time() );

		wp_redirect(
			admin_url( 'admin.php?page=' . $this->page['slug'] . '&updated=true' )
		);
	}

	/**
	 * Creates the menu entry in main navigation in backend.
	 *
	 * @return void
	 */
	public function add_options_menus()
	{
		add_theme_page(
			$this->page['page_title']
		,	$this->page['menu_title']
		,	$this->page['capability']
		,	$this->page['slug']
		,	$this->page['fields']
		);
	}

	/**
	 * Prints the page.
	 *
	 * @return void
	 */
	public function print_page()
	{
		! current_user_can( 'edit_theme_options' )
			and wp_die( __( 'You are not authorised to perform this operation.' ) );

		$this->option_page_header();

		if ( empty ( $this->fields ) )
		{
			print __( 'Here be dragons.', $this->text_domain );
		}
		else
		{
			foreach ( $this->fields as $key => $field )
			{
				$this->option_page_row( $key, $field );
			}
		}

		$this->option_page_footer();
	}


// ---- Helper -----------------------------------------------------------------


	/**
	 * Saves uploaded files in media library and the corresponding id in option field.
	 *
	 * @return void
	 */
	protected function handle_uploads()
	{
		if ( ! isset ( $_FILES ) or empty ( $_FILES ) )
		{
			return;
		}

		foreach ( $_FILES as $file_key => $file_arr )
		{
			// Some bogus upload.
			if ( ! isset ( $this->fields[$file_key] )
				or empty ( $file_arr['type'] )
			)
			{
				continue;
			}

			if ( ! $this->is_allowed_mime( $file_key, $file_arr ) )
			{
 				set_theme_mod( $file_key . '_error', 'wrong mime type' );
				continue;
			}

			// The file is allowed, no error until now and the type is correct.
			$uploaded_file = wp_handle_upload(
				$file_arr
			,	array( 'test_form' => FALSE )
			);

			// error
			if ( isset ( $uploaded_file['error'] ) )
			{
 				set_theme_mod( $file_key . '_error', $uploaded_file['error'] );
				continue;
			}

			// add the file to the media library

			// Set up options array to add this file as an attachment
			$attachment = array(
				'post_mime_type' => $uploaded_file['type']
			,	'post_title'     => $this->get_media_name(
										$file_key, $uploaded_file['file']
									)
			);

			// Adds the file to the media library and generates the thumbnails.
			$attach_id = wp_insert_attachment(
				$attachment
			,	$uploaded_file['file']
			);

			$this->create_upload_meta( $attach_id, $uploaded_file['file'] );

			// Update the theme mod.
			set_theme_mod( $file_key, $attach_id );
			remove_theme_mod( $file_key . '_error' );
		}
	}

	/**
	 * Detects the name for the media library
	 *
	 * @param  string $key
	 * @param  string $path
	 * @return string
	 */
	protected function get_media_name( $key, $path )
	{
		if ( isset ( $this->fields[$key]['media_lib_title'] ) )
		{
			return addslashes( $this->fields[$key]['media_lib_title'] );
		}

		return basename( $path );
	}

	/**
	 * Checks the MIME type of the uploaded file
	 *
	 * @param  string $file_key
	 * @param  array $file_arr File data
	 * @return bool
	 */
	protected function is_allowed_mime( $file_key, $file_arr )
	{
		// Nothing forbidden. WPâ€™s internal restrictions may still apply.
		if ( ! isset ( $this->fields[$file_key]['mimes'] ) )
		{
			return TRUE;
		}

		$mimes = (array) $this->fields[$file_key]['mimes'];

		if ( ! in_array( $file_arr['type'],	$mimes ) )
		{
 			set_theme_mod( $file_key . '_error', 'wrong file type' );
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Adds meta data to the uploaded file
	 *
	 * @param  int $attach_id
	 * @param  string $file
	 * @return void
	 */
	protected function create_upload_meta( $attach_id, $file )
	{
		// Create meta data from EXIF fields.
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$attach_data = wp_generate_attachment_metadata(
			$attach_id
		,	$file
		);
		wp_update_attachment_metadata($attach_id,  $attach_data);
	}

	/**
	 * Fills missing page data.
	 *
	 * @param  array $page
	 * @return array
	 */
	protected function prepare_page_data( array $page )
	{
		// Titles for the page and the menu entry
		if ( ! isset ( $page['page_title'] ) and ! isset ( $page['menu_title'] ) )
		{
			$page['page_title'] = $page['menu_title'] = __( 'Settings', $this->text_domain );
		}
		elseif ( ! isset ( $page['page_title'] ) )
		{
			$page['page_title'] = $page['menu_title'];
		}
		elseif ( ! isset ( $page['menu_title'] ) )
		{
			$page['menu_title'] = $page['page_title'];
		}

		// Capability
		! isset ( $page['capability'] )
			and $page['capability'] = 'edit_theme_options';

		// Slug
		! isset ( $page['slug'] )
			and $page['slug'] = sanitize_title_with_dashes( $page['page_title'] );

		// The callback is choosen by type.
		$this->fields = $page['fields'];

		$page['fields'] = array ( $this, 'print_page' );

		return $page;
	}

	/**
	 * Prints the header.
	 *
	 * @return void
	 */
	protected function option_page_header()
	{
		print '<div class="wrap">';
		screen_icon( 'themes' );
		print '<h2>' . get_admin_page_title() . '</h2>
		<form id="theme-settings-form" action="'
		. admin_url( 'admin-post.php?action=' . $this->save_action )
		. '" method="post" enctype="multipart/form-data">';

		isset ( $_GET['updated'] ) and print '<div class="updated fade below-h2"><p>'
				. __( 'Settings saved.' ) . '</p></div>';

		print $this->page['description'] . '<table class="form-table">';
	}

	/**
	 * Prints the footer
	 *
	 * @return void
	 */
	protected function option_page_footer()
	{
		// Save button.
		$this->option_page_row(
			''
		,	array (
				'type'         => 'submit'
			,	'label'        => ''
			,	'defaultvalue' => __( 'Save Changes', $this->text_domain )
			,	'input_attrs'  => array ( 'class' => 'button-primary' )
			)
		);

		print '</table>';
		// Used on save to validate the request
		wp_nonce_field( 'theme-settings' );
		print '</form></div>';
	}

	/**
	 * Sanitizes the field type. Defaults to 'text'.
	 *
	 * @param  string $type
	 * @return string
	 */
	protected function get_field_type( $type )
	{
		$type = trim( $type );
		$type = strtolower( $type );

		if ( empty ( $type ) or ! in_array( $type, $this->allowed_input_types ) )
		{
			$type = 'text';
		}

		return $type;
	}

	/**
	 * Returns a complete input element
	 *
	 * @param  array $options
	 * @return string
	 */
	protected function get_input_element( $options = array() ) {
		
		$defaults = array (
			'type'               => 'text'
		,	'val'                => ''
		,	'key'                => ''
		,	'id'                 => 0
		,	'atts'               => array ()
		,	'show_uploaded_file' => FALSE
		);
		$s = array_merge( $defaults, $options );
		extract ( $s );

		if ( 'checkbox' == $type )
		{
			$val = '';

			if ( 'on' == strtolower( get_theme_mod( $key, 'off' ) )	)
			{
				$atts['checked'] = 'checked';
			}
		}

		$atts = $this->html_attributes( $atts );
		$val  = esc_attr( stripslashes( $val ) );

		if ( 'textarea' == $type )
		{
			return "<textarea name='$key' rows=5 cols=34 id='$id' $atts>$val</textarea>";
		}
		
		if ( 'color' == $type )
			$id = 'link-color';
			
		$val   = ( empty ( $val ) or 'file' == $type ) ? '' : "value='$val'";
		$input = "<input type='$type' name='$key' id='$id' $val $atts />";
		$src   = get_theme_mod( $key, FALSE);
		
		if ( 'color' == $type ) {
			$input .= '<a href="#" class="pickcolor hide-if-no-js" id="link-color-example"></a>' . 
				'<input type="button" class="pickcolor button hide-if-no-js" value="' . 
				esc_attr__( 'Select a Color', self :: get_text_domain() ) . '" />' . 
				'<div id="colorPickerDiv" style="z-index: 100; background:#eee; ' . 
				'border:1px solid #ccc; position:absolute; display:none;"></div>';
		}
		
		if ( 'file' == $type and $src and $show_uploaded_file )
		{
			$input .= '<br>' . wp_get_attachment_image( $src, 'thumbnail' );
		}

		// The last error
		$last_error = get_theme_mod( $key . '_error' , FALSE );
		if ( $last_error )
		{
			$input .= '<br>Fehler beim letzten Hochladen: ' . $last_error;
		}

		return $input;
	}

	/**
	 * Form table row
	 *
	 * @param  array $field
	 * @return string
	 */
	protected function option_page_row( $key, $field ) {
		// Prepare defaults
		$type = isset ( $field['type'] )
			? $field['type'] : 'text';

		$atts = isset ( $field['input_attrs'] )
			? $field['input_attrs'] : array ();

		$type = $this->get_field_type( $type );
		
		$id = isset ( $field['id'] )
			? $field['id'] : $key . '_id';
		
		$val  = $this->prepare_value( $key, $field );

		$desc = isset ( $field['description'] )
			? $field['description'] : '';

		$show_uploaded_file = isset ( $field['show_uploaded_file'] )
			? (bool) $field['show_uploaded_file'] : FALSE;

		$out  = '<tr><th scope="row">';

		isset ( $field['label'] )
			and $out .= "<label for='$id'>{$field['label']}</label>";

		$out .= '</th><td>';

		$out .= $this->get_input_element(
			array (
				'type'               => $type,
				'val'                => $val,
				'key'                => $key,
				'id'                 => $id,
				'atts'               => $atts,
				'show_uploaded_file' => $show_uploaded_file
			)
		);

		$out .= "</td><td>$desc</td></tr>";

		print $out;
	}

	/**
	 * Prepares given value
	 *
	 * @param  string $key
	 * @param  array  $field
	 * @return string
	 */
	protected function prepare_value( $key, $field )
	{
		$default  = isset ( $field['defaultvalue'] ) ? $field['defaultvalue'] : '';
		$mod      = get_theme_mod( $key );

		if ( ! $mod )
		{
			set_theme_mod( $key, $default );
			return $default;
		}

		return $mod;
	}
	
	/**
	 * Converts an array into HTML attributes.
	 *
	 * @param  array $atts
	 * @return string
	 */
	public function html_attributes( $atts )
	{
		if ( empty ( $atts ) or ! is_array( $atts ) )
		{
			return '';
		}

		$html = '';

		foreach ( $atts as $key => $value )
		{
			$html .= " $key='" . esc_attr( $value ) . "'";
		}

		return $html;
	}
} // end class
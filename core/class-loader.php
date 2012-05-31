<?php
/**
 * @package     WordPress
 * @subpackage  WP-Basis Theme
 * @template    class core for autoload classes
 * @since       0.0.1
 */

/**
 * Wp_Basis_Loader<br />
 * automatic class loader in multiple directories with cache
 * 
 * Usage: 
 * <code>
 * spl_autoload_register(
 *  array(Wp_Basis_Loader::get_object(
 *   '/classes/', 
 *   '/tmp/',
 *   'load_class'
 *  )
 * );
 * </code>
 */
class Wp_Basis_Loader {

	/** @var Wp_Basis_Loader singleton - instance */
	private static $instance;
	
	/** @var array   list of classes; class -> path */
	private $class_list;
	
	/** @var bool    is a list of class loaded from cache */
	private $is_loaded_from_cache;
	
	/** @var string  tmp file */
	private $temp_file;
	
	/** @var string classes directory */
	private $class_dir;
	
	/** @var string tmp file with the list of classes*/
	const TEMP_FILE = 'Wp_Basis_Loader.tmp';

	/**
	 * Static instance
	 * 
	 * @param   srting $class_dir adresar trid
	 * @param   srting $temp_dir docasny adresar
	 * @return  Wp_Basis_Loader
	 */
	public static function get_object( $class_dir, $temp_dir ) {
		
		if ( ! isset( self::$instance ) )
			self::$instance = new Wp_Basis_Loader( $class_dir, $temp_dir );
		
		return self::$instance;
	}

	/**
	 * Constructor
	 * 
	 * @param  srting  $class_dir classes directory
	 * @param  srting  $temp_dir  temp file
	 */
	public function __construct( $class_dir, $temp_dir) {
		
		$this->class_list = array();
		$this->class_dir = $class_dir;
		$this->temp_file = $temp_dir . self::TEMP_FILE;
		if ( ! $this->open_class_list( $this->temp_file ) ) {
			//vytvoreni seznamu trid, pokud se nepodarilo nacist
			$this->load_class_list( $this->class_dir );
			$this->save_class_list( $this->temp_file );
			$this->is_loaded_from_cache = FALSE;
		} else {
			$this->is_loaded_from_cache = TRUE;
		}
	}

	/**
	 * Nacteni seznamu trid
	 * 
	 * @param string $file
	 * @return bool
	 */
	private function open_class_list( $file ) {
		
		if ( file_exists( $file ) ) {
			if ( FALSE !== ( $tmp = file_get_contents( $file, FILE_TEXT ) ) ) {
				if ( ( FALSE !== ( $this->class_list = @unserialize( $tmp ) ) )
						&& ( is_array( $this->class_list ) ) ) {
					return TRUE; //OK
				} else {
					@unlink($file); //smazat vadny soubor
				}
			}
		}
		return FALSE;
	}

	/**
	 * Ulozeni seznamu trid
	 * 
	 * @param string $file 
	 */
	private function save_class_list($file) {
		if (!is_array($this->class_list)) {
			return;
		}
		//ulozeni do souboru
		$handle = fopen($file, "w");
		if ($handle) {
			fwrite($handle, serialize($this->class_list));
			fclose($handle);
		}
	}

	/**
	 * Nacteni souboru tridy
	 * 
	 * @param string $className 
	 */
	public function load_class($className) {
		
		if ( array_key_exists( $className, $this->class_list ) ) {
			require_once( $this->class_list[$className] );
		} elseif ( TRUE === $this->is_loaded_from_cache ) { // regeneratrd list, if load from cache
			$this->class_list = array();
			$this->load_class_list( $this->class_dir );
			$this->save_class_list( $this->temp_file );
			$this->is_loaded_from_cache = FALSE;
			$this->load_class( $className );
		} else {
			throw new Exception("Class '" . $className . "' couldn't be loaded!");
		}
	}

	/**
	 * Creating a list of classes
	 * 
	 * @param string $class_dir 
	 */
	private function load_class_list( $class_dir ) {
		
		if ( ! is_dir( $class_dir ) )
			throw new Exception("Invalid class directory '" . $class_dir . "'!");
		
		$dir = opendir($class_dir);
		while ( FALSE !== ( $file = readdir($dir) ) )  {
			if ( ( '.' == $file ) || ( '..' == $file ) || ( is_link( $class_dir . $file ) ) )
				continue; //ignorovat
			
			if ( is_file( $class_dir . $file ) ) { //soubor
				if ( FALSE !== ($pp = mb_strripos( $file, '.', 0, 'UTF-8') ) ) {
					$extension = mb_substr( $file, ($pp + 1), ( mb_strlen( $file, 'UTF-8'  ) - $pp - 1 ), 'UTF-8');
					if ( 'php' == mb_strtolower( $extension, 'UTF-8' ) ) { //kontrola pripony
						$name = mb_substr( $file, 0, $pp, 'UTF-8' );
						$this->class_list[$name] = $class_dir . $file;
					}
				}
			} elseif ( is_dir( $class_dir . $file ) ) { //adresar - projde rekurzivne
				$this->load_class_list( $class_dir . $file . '/' );
			}
		}
	}

} // end class

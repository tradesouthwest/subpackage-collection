<?php
/**
 * Main Subpackage_Collection Class
 *
 * @class Subpackage_Collection
 * @version	1.0.0
 * @since 1.0.0
 * @package	Subpackage_Collection
 * @author Larry
 */
class Subpackage_Collection {
	/**
	 * Subpackage_Collection The single instance of Subpackage_Collection.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	/**
	 * The plugin directory URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_url;

	/**
	 * The plugin directory path.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_path;

	// Admin - Start
	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	/**
	 * The public object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $public;

	/**
	 * The settings object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings;
	// Admin - End

	// Post Types - Start
	/**
	 * The post types we're registering.
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $post_types = array();
	// Post Types - End
	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 */
	public function __construct () {
		$this->token       = 'subpackage-collection';
		$this->plugin_url  = plugin_dir_url( __FILE__ );
		$this->plugin_path = plugin_dir_path( __FILE__ );
		$this->version     = '1.0.0';

		// Admin - Start
		require_once 'class-subpackage-collection-settings.php';
			$this->settings = Subpackage_Collection_Settings::instance();

		if ( is_admin() ) {
			require_once 'class-subpackage-collection-admin.php';
			$this->admin = Subpackage_Collection_Admin::instance();
		}
		// Admin - End

		// Post Types - Start
		require_once 'class-subpackage-collection-post-type.php';
		require_once 'class-subpackage-collection-taxonomy.php';
		// Post Types - End
		
		// Register an example post type. To register other post types, duplicate this line.
		$this->post_types['collection'] = new Subpackage_Collection_Post_Type( 'collection', __( 'Collection', 'subpackage-collection' ), 
			__( 'Collection', 'subpackage-collection' ), array( 'menu_icon' => 'dashicons-carrot' ) );
		// Post Types - End

		// Additional class for Public views
		require_once 'class-subpackage-collection-public.php';
		$this->public = Subpackage_Collection_Public::instance();

		add_action( 'init', array( $this, 'flush_rewrite' ) );

		register_activation_hook( __FILE__, array( $this, 'install' ) );
		register_deactivation_hook( __FILE__, array( $this, 'uninstall' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
	}

	/**
	 * Main Subpackage_Collection Instance
	 *
	 * Ensures only one instance of Subpackage_Collection is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Subpackage_Collection()
	 * @return Main Subpackage_Collection instance
	 */
	public static function instance () {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Load the localisation file.
	 * @access  public
	 * @since   1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'subpackage-collection', false, 
		    dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Cloning is forbidden.
	 * @access public
	 * @since 1.0.0
	 */
	public function __clone () {}

	/**
	 * Unserializing instances of this class is forbidden.
	 * @access public
	 * @since 1.0.0
	 */
	public function __wakeup () {}

	/**
	 * Flush CPT. Runs on init.
	 * @access  public
	 * @since   1.0.0
	 * @see https://andrezrv.com/2014/08/12/efficiently-flush-rewrite-rules-plugin-activation/
	 */
	public function flush_rewrite(){
		flush_rewrite_rules();
	}

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 */
	public function install () {
		$this->log_version_number();
	}

	/**
	 * Runs on deactivation.
	 * @access  public
	 * @since   1.0.0
	 */
	public function uninstall () {
		delete_option( $this->token . '-version', $this->version );
	}

	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 */
	private function log_version_number () {
		if ( ! get_option( $this->token . '-version' ) ) {
			add_option( $this->token . '-version', $this->version );
		}
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	}
}

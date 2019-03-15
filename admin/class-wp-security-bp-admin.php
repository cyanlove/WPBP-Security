<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wp_security_bp
 * @subpackage wp_security_bp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    wp_security_bp
 * @subpackage wp_security_bp/admin
 * @author     Cyan Lovers <hello@cyanlove.com>
 */
class WP_Security_BP_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The url of the plugin
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $admin_url    The url of this plugin.
	 */
	private $admin_url;

	/**
	 * The hook suffix
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $hook_suffix    The hook suffix of this plugin.
	 */
	private $hook_suffix;

	/**
	 * JSON class instance to validate, store, and finally send checks data.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $json    JSON class instance.
	 */
	private $json;



	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name       The name of this plugin.
	 * @param    string $version           The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->admin_url   = admin_url( 'options-general.php?page=' . $this->plugin_name );
		$this->json        = new WP_Security_BP_JSON();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook_suffix    The page where it is supposed to be loaded.
	 */
	public function enqueue_styles( $hook_suffix ) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in wp_security_bp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wp_security_bp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( $hook_suffix === $this->hook_suffix ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-security-bp-admin.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook_suffix    The page where it is supposed to be loaded.
	 */
	public function enqueue_scripts( $hook_suffix ) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in wp_security_bp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wp_security_bp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( $hook_suffix === $this->hook_suffix ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-security-bp-admin.min.js', array(), $this->version, true );
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		/*
		* Add a settings page for this plugin to the Settings menu.
		*
		* NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		*
		* Administration Menus: http://codex.wordpress.org/Administration_Menus
		*
		*/
		$this->hook_suffix = add_options_page(
			'WordPress Security Best Practices Dashboard',
			'WP Security BP',
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_plugin_setup_page' )
		);
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 * @param    array $links    The array containing the links.
	 */
	public function add_action_links( $links ) {
		/*
		 * Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		 */
		$settings_link = array(
			'<a href="' . $this->admin_url . '">' . sprintf( __( 'Settings', '%s' ), $this->plugin_name ) . '</a>',
		);
		return array_merge( $settings_link, $links );

	}

	/**
	 * This function calls all checking methods and returns a JSON.
	 *
	 * @since    1.0.0
	 */
	public function check_all() {
		// Class Files calls.
		$files      = new WP_Security_BP_Files( $this->plugin_name, $this->admin_url, $this->json );
		$files->check_wp_config();
		$files->check_debug();
		$files->check_file_edit();
		$files->check_auto_updates();
		// Class Users calls.
		$users      = new WP_Security_BP_Users( $this->plugin_name, $this->json );
		$users->check_users_ids();
		$users->check_admin_name();
		// Class Database checks.
		$db         = new WP_Security_BP_Database( $this->plugin_name, $this->json );
		$db->check_name();

		$this->json->response();
	}

	/**
	 * This function gets the action sent through POST and calls the appropiate method.
	 *
	 * @since    1.0.0
	 */
	public function run_ajax_calls() {
		if ( wp_doing_ajax() ) {

			$action = empty( $_POST['action'] ) ? '' : wp_unslash( $_POST['action'] );

			if ( 'check-all' === $action ) {
				$this->check_all();
			}

			/**
			 * This array matches the 'action' value sent with the ajax request with the
			 * corresponding method that should be fired.
			 *
			 * The key is the action passed from JS. All action names must include the exact
			 * name of the class that should be fired followed by a hyphen '-' and some identitiy name.
			 *
			 * The value is the method name and must be exact excluding the brackets '()'.
			 */
			$actions = array(
<<<<<<< HEAD
=======
<<<<<<< files
>>>>>>> 8c352b8d92c575c0e685274bd129761ecfa1b045
				'class-example-action'   => 'example_class_method', // for example purpose only.
				'files-fix-wp-config'    => 'fix_wp_config',
				'files-fix-debug'        => 'fix_debug',
				'files-fix-file-edit'    => 'fix_file_edit',
				'files-fix-auto-updates' => 'fix_auto_updates',
<<<<<<< HEAD
=======
=======
				'class-example-action' => 'example_class_method', // for example purpose only.
				'files-fix-wp-config'  => 'fix_wp_config',
				'files-fix-debug'      => 'fix_debug',
>>>>>>> master
>>>>>>> 8c352b8d92c575c0e685274bd129761ecfa1b045
			);
			if ( array_key_exists( $action, $actions ) ) {
				$key    = strstr( $action, '-', true );
				$method = $actions[ $action ];
				switch ( $key ) {
					case 'files':
						$class = new WP_Security_BP_Files( $this->plugin_name, $this->admin_url, $this->json );
						break;
					case 'users':
						$class = new WP_Security_BP_Users( $this->plugin_name, $this->json );
						break;
					case 'database':
						$class = new WP_Security_BP_Database( $this->plugin_name, $this->json );
						break;
					default:
						wp_die();
				}

				$class->$method();
				$this->check_all();

			} else {
				// This should be checked before release.
				$this->check_all();
			}
		}

	}
	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_setup_page() {
		include_once 'partials/wp-security-bp-admin-display.php';
	}

}

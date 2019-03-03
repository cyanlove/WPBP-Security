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
	 * The 
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $admin_url    The url of this plugin.
	 */
	private $admin_url;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->admin_url = admin_url( 'options-general.php?page=' . $this->plugin_name );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
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
		if ( $hook_suffix === 'settings_page_wp-security-bp' ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-security-bp-admin.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
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
		if ( $hook_suffix === 'settings_page_wp-security-bp' ) {
			/*wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-security-bp-admin.js', array( 'jquery' ), $this->version, true );*/
			wp_enqueue_script('vue', 'https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js', array('jquery'), '2.5.17', true);
			wp_enqueue_script('axios', 'https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js', array('jquery'), '0.18.0', true);
			wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-security-bp-view.js');
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-security-bp-view.js', [], $this->version, true );
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
		add_options_page( 'WordPress Security Best Practices Dashboard', 'WP Security BP', 'manage_options', $this->plugin_name, array( $this, 'display_plugin_setup_page' )
		);
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */

	public function add_action_links( $links ) {
		/*
		*  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		*/
	$settings_link = array(
		'<a href="' . $this->admin_url . '">' . __( 'Settings', $this->plugin_name ) . '</a>',
	);
	return array_merge( $settings_link, $links );

	}

	/**
	 * This function calls all functions and agroup all return in to one json.
	 *
	 * @since    1.0.0
	 */
	public function final_json(){
		//Class Files calls:
		$files = new WP_Security_BP_Files( $this->plugin_name, $this->admin_url );
		$json_return[] = $files->check_wp_config();
		//Class Users calls:
		$users = new WP_Security_BP_Users( $this->plugin_name, $this->admin_url );
		$json_return[] = $users->check_users_ids();
		
		wp_send_json( $json_return );
		wp_die();
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */

	public function display_plugin_setup_page() {
		include_once( 'partials/wp-security-bp-admin-display.php' );
	}

}

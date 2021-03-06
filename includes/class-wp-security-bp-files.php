<?php
/**
 * The file that defines the Files class
 *
 * This class handles all the files access to read and write.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wp_security_bp
 * @subpackage wp_security_bp/includes
 */

/**
 * The Files class.
 *
 * This class handles all the files access to read and write.
 *
 * @since      1.0.0
 * @package    wp_security_bp
 * @subpackage wp_security_bp/includes
 * @author     Cyan Lovers <hello@cyanlove.com>
 */
class WP_Security_BP_Files {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * Short desc
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $request_uri    The URI where the request is made from
	 */
	protected $request_uri;

	/**
	 * Short desc
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $nonce_action_name    The action for the nonce creation
	 */
	protected $nonce_action_name;

	/**
	 * Short desc
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $wp_config    The name of the file wp-config.php
	 */
	protected $wp_config;

	/**
	 * Short desc
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $htaccess    The name of the file .htaccess
	 */
	protected $htaccess;

	/**
	 * Short desc
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      object    $wp_filesystem    The Object $wp_filesystem
	 */
	protected $wp_filesystem;

	/**
	 * Short desc
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $root    The uri of the installation root
	 */
	protected $root;

	/**
	 * Short desc
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $parent_root    The uri of the parent installation root
	 */
	protected $parent_root;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name       The name of this plugin.
	 * @param    string $request_uri       The URI from the request is made.
	 * @param    string $json              JSON class instance.
	 */
	public function __construct( $plugin_name, $request_uri, $json ) {

		$this->plugin_name       = $plugin_name;
		$this->request_uri       = $request_uri;
		$this->nonce_action_name = 'wp-security-bp-file-access';
		$this->wp_config         = 'wp-config.php';
		$this->response          = $json;

		$access_type = function_exists( 'get_filesystem_method' ) ? get_filesystem_method() : '';
		if ( 'direct' === $access_type ) {

			// request credentials.
			$creds = $this->request_credentials();

			// if user has no permission is asked for ftp credentials.
			if ( false === $creds ) {
				return; // stop processing here.
			}

			// if ftp credentials are not ok user is asked again for ftp credentials.
			if ( false === WP_Filesystem( $creds ) ) {
				$this->request_credentials( true );
				return; // stop processing here.
			}

			// call global $wp_filesystem variable.
			global $wp_filesystem;
			$this->wp_filesystem = $wp_filesystem;

			// define root dir.
			$this->root = $wp_filesystem->abspath();
			// define parent root dir.
			$this->parent_root = trailingslashit( dirname( $this->root ) );

			// get the plugin directory path.
			$plugin_path = trailingslashit( $this->wp_filesystem->wp_plugins_dir() . $this->plugin_name );

			// make a directory.
			// $this->wp_filesystem->mkdir( $plugin_path . 'test-folder' ); // phpcs:ignore
			// make a file and write content.
			// $this->wp_filesystem->put_contents( $plugin_path . 'test-folder/test-file.txt', 'Example contents of a file', FS_CHMOD_FILE // predefined mode settings for WP files ); // phpcs:ignore

		} else {
			/* don't have direct write access. Prompt user with our notice */
			echo "You don't have direct write access :(";
		}

	}

	/**
	 * Short desc
	 *
	 * Long desc
	 *
	 * @since    1.0.0
	 * @param    bool $error       Optional. Defines if an error message should be displayed to the user or not.
	 * @access   private
	 */
	private function request_credentials( $error = false ) {

		$uri = wp_nonce_url( $this->request_uri, $this->nonce_action_name );
		return request_filesystem_credentials( $uri, '', $error, false, null );

	}

	/**
	 * Short desc
	 *
	 * Long desc
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function find_wp_config() {

		return $this->wp_filesystem->exists( $this->root . $this->wp_config );

	}

	/**
	 * Checks if wp-config.php is on default location or not.
	 *
	 * Long desc
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function check_wp_config() {

		$args['short_desc'] = 'Check wp-config.php location';
		$is_in_root         = $this->find_wp_config();

		if ( $is_in_root ) {

			$args['message'] = sprintf(
				/* translators: %s: Name of the wp-config file */
				__( 'The file %s is on default location, it is recommended to store this file on the parent directory', 'wp-security-bp' ),
				$this->wp_config
			);
			$args['action'] = 'files-fix-wp-config';
			$this->response->fail( $args );

		} else {

			$args['message'] = sprintf(
				/* translators: %s: Name of the wp-config file */
				__( 'Good job, %s is not on default location', 'wp-security-bp' ),
				$this->wp_config
			);
			$this->response->pass( $args );

		}

	}

	/**
	 * Short desc
	 *
	 * Long desc
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function fix_wp_config() {

		return $this->wp_filesystem->move(
			$this->root . $this->wp_config,
			$this->parent_root . $this->wp_config,
			false // Don't overwrites if exists.
		);

	}

	/**
	 * Short desc
	 *
	 * Long desc
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function check_debug() {

		$args['short_desc'] = 'Check debug mode';
		$is_defined         = $this->check_constant( 'WP_DEBUG', false );

		if ( ! $is_defined ) {

			$args['message'] = __( 'You have debug mode on, turn it off when you are on production', 'wp-security-bp' );
			$args['action']  = 'files-fix-debug';
			$this->response->fail( $args );

		} else {

			$args['message'] = __( 'Good job, you have debug mode off', 'wp-security-bp' );
			$this->response->pass( $args );

		}

	}

	/**
	 * Short desc
	 *
	 * Long desc
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function check_file_edit() {

		$args['short_desc'] = 'Check file editing mode';
		$is_defined         = $this->check_constant( 'DISALLOW_FILE_EDIT', true );

		if ( ! $is_defined ) {

			$args['message'] = __( 'You have file editing mode on, turn it of when you are on production', 'wp-security-bp' );
			$args['action']  = 'files-fix-file-edit';
			$this->response->fail( $args );

		} else {

			$args['message'] = __( 'Good job, you have file editing mode off', 'wp-security-bp' );
			$this->response->pass( $args );

		}

	}

	/**
	 * Short desc
	 *
	 * Long desc
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function check_auto_updates() {

		// Checks still in development.
		$args['short_desc']   = 'Check auto updates mode';
		$updater_disabled     = $this->check_constant( 'AUTOMATIC_UPDATER_DISABLED', true );
		$update_core_disabled = $this->check_constant( 'WP_AUTO_UPDATE_CORE', false );
		$update_core_enabled  = $this->check_constant( 'WP_AUTO_UPDATE_CORE', true );
		$update_core_minor    = $this->check_constant( 'WP_AUTO_UPDATE_CORE', 'minor' );
		$not_update_core      = defined( 'WP_AUTO_UPDATE_CORE' ) && WP_AUTO_UPDATE_CORE != 'minor';

		// Still have to check whether a filter has been used to block auto updates or not.
		// $check = has_filter( 'automatic_updater_disabled' );

		if ( $updater_disabled || $not_update_core ) {

			$args['message'] = __( 'You have auto updates mode on, turn it of when you are on production', 'wp-security-bp' );
			$args['action']  = 'files-fix-auto-updates';
			$this->response->fail( $args );

		} else {

			$args['message'] = __( 'Good job, you have auto updates mode off', 'wp-security-bp' );
			$this->response->pass( $args );

		}

	}

	/**
	 * Short desc
	 *
	 * Long desc
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function fix_debug() {

		return $this->write_wp_config( 'WP_DEBUG', 'false' );

	}

	/**
	 * Short desc
	 *
	 * Long desc
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function fix_file_edit() {

		return $this->write_wp_config( 'DISALLOW_FILE_EDIT', 'true' );

	}

	/**
	 * Short desc
	 *
	 * Long desc
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function fix_auto_updates() {

		// Still have to fix if there is a filter that overrides this.
		$enable_updater = $this->write_wp_config( 'AUTOMATIC_UPDATER_DISABLED', 'false' );
		$enable_core    = $this->write_wp_config( 'WP_AUTO_UPDATE_CORE', 'minor' );
		return $enable_updater && $enable_core;

	}

	/**
	 * Short desc
	 *
	 * Long desc
	 *
	 * @since    1.0.0
	 * @param    string $constant    The name of the constant to write.
	 * @param    string $value       The value of the constant to write.
	 * @access   private
	 */
	private function write_wp_config( $constant, $value ) {

		$path = $this->find_wp_config() ? $this->root : $this->parent_root;

		if ( $this->wp_filesystem->is_writable( $path . $this->wp_config ) ) {

			$value      = ( 'true' === $value || 'false' === $value ) ? $value : "'$value'";
			$regex      = "/define\s*\(\s*('|\")$constant('|\").*?;/";
			$new_define = "define( '$constant', $value );";
			$content    = $this->wp_filesystem->get_contents( $path . $this->wp_config );

			$is_in_file = preg_match( $regex, $content );

			if ( $is_in_file ) {

				// Constant is already defined in wp-config.php.
				$new_content = preg_replace( $regex, $new_define, $content );

			} else {

				// Constant is not defined in wp-config.php.
				$new_content  = $content;
				$new_content .= "\n/** Added by WordPress Security Best Practices */\n";
				$new_content .= $new_define . "\n";

			}

			return $this->wp_filesystem->put_contents( $path . $this->wp_config, $new_content );

		} else {

			return false;

		}

	}

	/**
	 * The method that checks if a constant with a certain value is defined or not.
	 *
	 * Long desc
	 *
	 * @since    1.0.0
	 * @param    string $constant    The name of the constant to check.
	 * @param    string $value       The value of the constant to check.
	 * @access   private
	 * @return   bool
	 */
	private function check_constant( $constant, $value ) {

		return defined( $constant ) && constant( $constant ) === $value;

	}

}

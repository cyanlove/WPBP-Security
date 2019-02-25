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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name       The name of this plugin.
	 * @param    string    $request_uri       The URI from the request is made.
	 */
	public function __construct( $plugin_name, $request_uri ) {

		$this->plugin_name = $plugin_name;
		$this->request_uri = $request_uri;
		$this->nonce_action_name = 'wp-security-bp-file-access';

		$access_type = get_filesystem_method();
		if ( $access_type === 'direct' ) {

			// request credentials
			$creds = $this->request_credentials();

			// if user has no permission is asked for ftp credentials
			if ( false === $creds ) {
				return; // stop processing here
			}

			// if ftp credentials are not ok user is asked again for ftp credentials
			if ( false === WP_Filesystem( $creds ) ) {
				$this->request_credentials( true );
				return; // stop processing here
			}

			// call global $wp_filesystem variable
			global $wp_filesystem;

			// get the plugin directory path
			$path = trailingslashit( $wp_filesystem->wp_plugins_dir() . $plugin_name );

			// make a directory
			$wp_filesystem->mkdir( $path. 'test-folder' );

			// make a file and write content
			$wp_filesystem->put_contents(
				$path . 'test-folder/test-file.txt',
				'Example contents of a file',
				FS_CHMOD_FILE // predefined mode settings for WP files
			);
		
		}	
		else {
			/* don't have direct write access. Prompt user with our notice */
			echo "don't have direct write access. Prompt user with our notice";
		}


	}

	/**
	 * Short desc
	 *
	 * Long desc
	 *
	 * @since    1.0.0
	 * @param    bool    $error       Optional. Defines if an error message should be displayed to the user or not.
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
	private function load_dependencies() {

		
	}

	

}

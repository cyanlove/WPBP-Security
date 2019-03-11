<?php

/**
 * The file that defines the Users class
 *
 * This class handles all the users requests.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wp_security_bp
 * @subpackage wp_security_bp/includes
 */

/**
 * The Users class.
 *
 * This class handles all the users requests.
 *
 * @since      1.0.0
 * @package    wp_security_bp
 * @subpackage wp_security_bp/includes
 * @author     Cyan Lovers <hello@cyanlove.com>
 */

class WP_Security_BP_Users {
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
	 * @var      string    $users    The array of all users info
	 */
	protected $users;

	/**
	 * Short desc
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      integer    $range_scan_id  The integer to set the range to scan of users id's.
	 */
	protected $range_scan_id;

	/**
	 * Short desc
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $blacklists_admin_names  The array with blacklist admin login names.
	 */

	private $blacklists_admin_names = array(
		'username',
		'user1',
		'admin',
		'Admin',
		'administrator',
		'Administrator',
		'db2admin',
		'demo',
		'alex',
		'sql',
		'pos',
	);

	/**
	 * The final attribute to return in each public function.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $response    The array that will be passed as a JSON file to the admin.
	 */
	protected $response;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name       The name of this plugin.
	 */

	public function __construct( $plugin_name ) {

		$this->plugin_name   = $plugin_name;
		$this->range_scan_id = 10;
		$this->users         = get_users();
		$this->response      = new WP_Security_BP_JSON();

	}

	public function check_users_ids() {

		$check   = false;
		$options = array(
			'short_desc' => 'Check admin id',
		);

		foreach ( $this->users as $user ) {
			if ( $user->ID <= $this->range_scan_id ) {
				$check = true;
			}
		}
		if ( true === $check ) {
				$message           = __( 'You have danger admin ids!', 'wp-security-bp' );
				$options['action'] = 'users-fix-admin-id';

				$this->response->fail( $message, $options );
		} else {
				$message = __( 'Your admin ids are secure!', 'wp-security-bp' );
				$this->response->pass( $message, $options );
		}

		return $this->response->json;
	}

	public function check_admin_name() {

		$check   = false;
		$options = array(
			'short_desc' => 'Check admin user login',
		);

		foreach ( $this->users as $user ) {
			if ( $user->caps['administrator'] && in_array( $user->user_login, $this->blacklists_admin_names, true ) ) {
					$check = true;
			}
		}
		if ( true === $check ) {
				$message           = __( 'You have danger admin user login!', 'wp-security-bp' );
				$options['action'] = 'users-fix-admin-login';
				$this->response->fail( $message, $options );
		} else {
				$message = __( 'Your admin user login are secure!', 'wp-security-bp' );
				$this->response->pass( $message, $options );
		}

		return $this->response->json;
	}

}
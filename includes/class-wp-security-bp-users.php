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
	 * @var      integer    $range_scan_id  The integer to set the range to scan of users id's.
	 */
	private $admin_names_blacklist = array(
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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name       The name of this plugin.
	 */
	public function __construct( $plugin_name ) {

		$this->plugin_name   = $plugin_name;
		$this->range_scan_id = 5;
		$this->users         = get_users();
	}

	public function check_users_ids() {

		$response['short_desc'] = __( 'Check admin id', $this->plugin_name );

		foreach ( $this->users as $user ) {
			if ( $user->ID <= $this->range_scan_id ) {
				$response['status']  = 'fail';
				$response['message'] = __( 'Danger IDS!!!', $this->plugin_name );
				$response['button']  = true;
				$response['uri']     = 'url-to-fix';

			} else {
				$response['status']  = 'passed';
				$response['message'] = __( 'All under control!', $this->plugin_name );
				$response['button']  = false;
				$response['uri']     = 'url-to-fix';
			}
		}
		return $response;
	}

	public function check_admin_name() {

		$response['short_desc'] = __( 'Check admin user login', $this->plugin_name );
		$check                  = false;

		foreach ( $this->users as $user ) {
			if ( $user->caps['administrator'] && in_array( $user->user_login, $this->$admin_names_blacklist, true ) ) {
					$check = true;
			}
		}

		if ( ! $check ) {
			$response['status']  = 'fail';
			$response['message'] = __( 'Admin user login is not secure.', $this->plugin_name );
			$response['button']  = true;
			$response['uri']     = 'url-to-fix';
			$response['action']  = '';
		} else {
			$response['status']  = 'passed';
			$response['message'] = __( 'Admin users login are OK!', $this->plugin_name );
			$response['button']  = false;
			$response['uri']     = 'url-to-fix';
			$response['action']  = '';
		}
			return $response;
	}
}

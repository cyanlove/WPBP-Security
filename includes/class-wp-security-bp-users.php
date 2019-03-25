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
	 * @var      array    $users    The array of all users
	 */
	protected $users;

	/**
	 * Short desc
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $posts    The array of all posts
	 */
	protected $posts;

	/**
	 * Short desc
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $admin_ids    The array of all admin ID
	 */
	protected $admin_ids;

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
	 * @param    string $json              JSON class instance.
	 */

	public function __construct( $plugin_name, $json ) {

		$this->plugin_name   = $plugin_name;
		$this->range_scan_id = 5;
		$this->users         = get_users();
		$this->posts         = get_posts();
		$this->admin_ids     = $this->get_admin_ids();
		$this->response      = $json;

	}

	public function check_users_ids() {

		$args['short_desc'] = 'Check admin id';
		$check              = false;

		foreach ( $this->admin_ids as $id ) {
			if ( $id <= $this->range_scan_id ) {
				$check = true;
			}
		}

		if ( true === $check ) {
				$args['message'] = __( 'You have danger admin ids!', 'wp-security-bp' );
				$args['action']  = 'users-fix-admin-id';
				$this->response->fail( $args );
		} else {
				$args['message'] = __( 'Your admin ids are secure!', 'wp-security-bp' );
				$this->response->pass( $args );
		}

	}

	public function check_admin_name() {

		$args['short_desc'] = 'Check admin user login';
		$check              = false;

		foreach ( $this->users as $user ) {
			if ( $user->caps['administrator'] && in_array( $user->user_login, $this->blacklists_admin_names, true ) ) {
					$check = true;
			}
		}
		if ( true === $check ) {
				$args['message'] = __( 'You have danger admin user login!', 'wp-security-bp' );
				$args['action']  = 'users-fix-admin-login';
				$this->response->fail( $args );
		} else {
				$args['message'] = __( 'Your admin user login are secure!', 'wp-security-bp' );
				$this->response->pass( $args );
		}
	}

	public function check_if_admin_is_author() {

		$args['short_desc'] = 'Check if admin is author of posts';
		$check              = false;

		foreach ( $this->posts as $post ) {
			if ( in_array( $post->post_author, $this->admin_ids ) ) {
					$check = true;
			}
		}
		if ( true === $check ) {
				$args['message'] = __( 'Admin is author of posts. Thats not recomended!', 'wp-security-bp' );
				$args['action']  = 'users-fix-admin-author';
				$this->response->fail( $args );
		} else {
				$args['message'] = __( 'Admin is not author of posts', 'wp-security-bp' );
				$this->response->pass( $args );
		}
	}

	public function get_admin_ids() {

		$ids = array();

		foreach ( $this->users as $user ) {
			if ( $user->caps['administrator'] ) {
				array_push( $ids, $user->ID );
			}
		}

		return $ids;
	}
}

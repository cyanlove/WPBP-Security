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
	 * Short desc
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $default_args    The array that contains the default arguments to pass to get_users()
	 */
	protected $default_args;

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

		$this->plugin_name  = $plugin_name;
		$this->response     = $json;
		$this->default_args = array(
			'fields'      => array(
				'ID',
				'display_name',
				'user_login',
			),
			'count_total' => false,
		);
	}

	/**
	 *
	 *
	 * Check if user admins id are in the range of dangerous id's
	 *
	 * @since    1.0.0
	 * @access   public
	 */

	public function check_users_ids() {
		//short description:
		$args['short_desc'] = 'Check admin id';
		//check:
		$search = array(
			'role__in' => array(
				'Super Admin',
				'Administrator',
			),
			'include'  => range(1, 10),
		);
		$params = array_merge( $this->default_args, $search);
		$query = new WP_User_Query( $params );
		$check = $query->get_results();
		//response:
		if ( ! empty( $check ) ) {

			$args['message'] = __( 'You have danger admin ids!', 'wp-security-bp' );
			$args['action']  = 'users-fix-admin-id';
			$this->response->fail( $args );

		} else {

			$args['message'] = __( 'Your admin ids are secure!', 'wp-security-bp' );
			$this->response->pass( $args );

		}
	}

	/**
	 *
	 *
	 * Check if admin login name is in the blacklist
	 *
	 * @since    1.0.0
	 * @access   public
	 */

	public function check_admin_name() {
		//short description:
		$args['short_desc'] = 'Check admin user login';
		//check:
		$search = array(
			'role__in'  => array(
				'Super Admin',
				'Administrator',
			),
			'login__in' => $this->blacklists_admin_names,
		);
		$params = array_merge( $this->default_args, $search);
		$check = get_users( $params );
		//response:
		if ( ! empty( $check ) ) {

			$args['message'] = __( 'You have danger admin user login!', 'wp-security-bp' );
			$this->response->fail( $args );

		} else {

			$args['message'] = __( 'Your admin user login are secure!', 'wp-security-bp' );
			$this->response->pass( $args );

		}
	}

	/**
	 *
	 *
	 * Check if admins are authors of any post
	 *
	 * @since    1.0.0
	 * @access   public
	 */

	public function check_if_admin_is_author() {
		//short description:
		$args['short_desc'] = 'Check if admin is author of posts';
		//check:
		$search = array(
			'role__in'            => array(
				'Super Admin',
				'Administrator',
			),
			'has_published_posts' => true,
		);
		$params = array_merge( $this->default_args, $search);
		$check = get_users( $params );
		//response:
		if ( ! empty( $check ) ) {

			$args['message'] = __( 'Admin has posts. Thats not recomended man...', 'wp-security-bp' );
			$args['action']  = 'users-fix-admin-author';
			$this->response->fail( $args );

		} else {

			$args['message'] = __( 'Admin is not author of posts. Good!', 'wp-security-bp' );
			$this->response->pass( $args );

		}
	}
}


<?php

/**
 * The file that defines the Database class
 *
 * This class handles all the database requests.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wp_security_bp
 * @subpackage wp_security_bp/includes
 */

/**
 * The Database class.
 *
 * This class handles all the database requests.
 *
 * @since      1.0.0
 * @package    wp_security_bp
 * @subpackage wp_security_bp/includes
 * @author     Cyan Lovers <hello@cyanlove.com>
 */
class WP_Security_BP_Database {
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The object to access database.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      object    $wpdb    Object with preset rules to access database.
	 */
	protected $wpdb;

	/**
	 * The database names blacklist. They shouldn't be used.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $db_names_blacklist    The array of all prohibited database names.
	 */
	private $db_names_blacklist = array(
		'wordpress',
		'database',
		'local',
	);

	/**
	 * The database names blacklist. They shouldn't be used.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $tables_prefix_blacklist    The array of all prohibited prefixes.
	 */
	private $tables_prefix_blacklist = array(
		'wp',
		'db',
		'wordpress',
	);

	/**
	 * The domain of site.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $domain    Domain of the site to compare with other values.
	 */
	private $domain_site;

	/**
	 * The database name in use.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $db_name    From global to local in __construct.
	 */
	private $db_name;

	/**
	 * Database version MySQL.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $db_version    From global to local in __construct.
	 */
	private $db_version;

	/**
	 * Database tables prefix.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $tables_prefix    From global to local in __construct.
	 */
	private $tables_prefix;

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
	 * @param    string    $plugin_name       The name of this plugin.
	 * @param    string $json                 JSON class instance.
	 */
	public function __construct( $plugin_name, $json ) {

		global $wpdb;
		$this->db_name       = $wpdb->dbname;
		$this->db_version    = $wpdb->db_version();
		$this->tables_prefix = $wpdb->prefix;
		$this->plugin_name   = $plugin_name;
		$this->response      = $json;
		$this->domain_site   = get_site_url();
		$this->domain_site   = ( $this->domain_site ) ? $this->domain_site : wp_die();
	}

	/**
	 * Cchecks DB name and decides ig its enough secure or not.
	 *
	 * @since    1.0.0
	 */
	public function check_name() {

		$args['short_desc'] = 'check DB name';
		$args['data']       = $this->db_name;

		//clean domain
		$add_blacklist = preg_replace(
			'#^http(s?):\/\/|(w{3}\.)|(\.[a-z]{2,4}$)|-|_#',
			'',
			$this->domain_site
		);

		//split by '.' and '/'
		$add_blacklist = preg_split(
			'#/|\.#',
			$add_blacklist
		);

		$check = ! in_array(
			preg_replace( '#\.|_|-#', '', $this->db_name ),
			array_merge(
				$this->db_names_blacklist,
				$add_blacklist
			),
			true
		);

		if ( $check ) {
			$args['message'] = __( 'Your database name is fine.', 'wp-security-bp' );
			$this->response->pass( $args );
		} else {
			$args['message'] = __( 'Your database name is not secure enough.', 'wp-security-bp' );
			$args['action']  = 'database-fix-name';
			$this->response->fail( $args );
		}
	}

	public function check_user() {}

	public function check_mysql_version() {}

	public function check_tables_prefix() {}

}

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
	 * @var      string    $domain    Domain to compare with other values.
	 */
	private $domain;

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
	 */
	public function __construct( $plugin_name ) {

		$this->plugin_name = $plugin_name;
		//$this->domain = ***
		global $wpdb;
		$this->db_name       = $wpdb->dbname;
		$this->db_version    = $wpdb->db_version();
		$this->tables_prefix = $wpdb->prefix;
		$this->response      = new WP_Security_BP_JSON();
	}

	public function check_name() {

		$args['short_desc'] = 'check DB name';
		$args['data']       = $this->db_name;
		//push domain to $db_names_blacklist (coming soon)

		/*
		too simple check for the moment.
		This will check removing every _ , - , . (at least)
		and even part of string coincidences.
		*/
		$check = ! in_array( $this->db_name, $this->db_names_blacklist );

		if ( $check ) {
			$args['message'] = __( 'Your database name is fine.', 'wp-security-bp' );
			$this->response->pass( $args );
		} else {
			$args['message'] = __( 'Your database name is not secure enough.', 'wp-security-bp' );
			$args['action']  = 'database-fix-name';
			$this->response->fail( $args );
		}

		return $this->response->json;
	}

	public function check_user() {}

	public function check_mysql_version() {}

	public function check_tables_prefix() {}

}

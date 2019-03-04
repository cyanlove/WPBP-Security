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
	 * @var      array    $json    The array that will be passed as a JSON file to the admin
	 */
	protected $json;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name       The name of this plugin.
	 */
	public function __construct( $plugin_name) {

		$this->plugin_name = $plugin_name;
		$this->range_scan_id = 5;
		$this->users = get_users();
		$this->json = array(
			'id'			=> '',
			'status'   		=> 'fail',
			'short_desc'	=> '',	
			'message'  		=> '',
			'button'   		=> false,
			'uri'      		=> '',
		);
	}

	public function check_users_ids(){

		$this->json['id'] = uniqid();
		$this->json['short_desc'] = __( 'Check admin id' , $this->plugin_name );

		foreach ( $this->users as $user ){
			if ( $user->ID <= $this->range_scan_id){
				$this->json['message'] = __( 'Danger IDS!!!', $this->plugin_name );
				$this->json['button'] = true;
				$this->json['uri'] = 'url-to-fix';
			}
			else {
			$this->json['status'] = 'passed';
			$this->json['message'] = __( 'All under control!', $this->plugin_name );
			}
		}
		
		return $this->json;
	}
}
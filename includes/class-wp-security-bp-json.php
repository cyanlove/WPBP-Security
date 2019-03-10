<?php

/**
 * The file that defines the JSON array class
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
 * The JSON class.
 *
 * This class handles all the information to be send by ajax response in an array.
 *
 * @since      1.0.0
 * @package    wp_security_bp
 * @subpackage wp_security_bp/includes
 * @author     Cyan Lovers <hello@cyanlove.com>
 */

class WP_Security_BP_JSON {
	/**
	 * Final array to return formated for each case.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $json    The array that will be returned to the admin (later transformed to JSON)
	 */
	public $json = array();

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name       The name of this plugin.
	 */
	public function __construct( $plugin_name ) {

		$this->plugin_name = $plugin_name;

	}

	/**
	 * If the test is passed, returns formated information.
	 *
	 * @since    1.0.0
	 * @param    string    $message       The message of OK.
	 */
	public function pass( $short_desc = '', $message ) {

		$this->json['status']     = 'passed';
		$this->json['short_desc'] = $short_desc;
		$this->json['button']     = false;
		$this->json['message']    = sprintf(
			/* translators: %s: message, plugin name */
			__(
				'%s',
				'%s'
			),
			$message,
			$this->plugin_name
		);
	}

	public function fail( $short_desc = '', $message, $action = null ) {

		$this->json['status']     = 'fail';
		$this->json['short_desc'] = $short_desc;
		$this->json['button']     = true;
		$this->json['action']     = $action;
		$this->json['message']    = sprintf(

			/* translators: %s: message, plugin name */
			__(
				'%s',
				'%s'
			),
			$message,
			$this->plugin_name
		);
	}
}

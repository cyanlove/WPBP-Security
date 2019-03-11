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
	 * @var      array $json    The array that will be returned to the admin (later transformed to JSON)
	 */
	public $json = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

	}

	/**
	 * If the test is passed, returns formated information.
	 *
	 * @since    1.0.0
	 * @param    string $message       The message of OK.
	 * @param    string $short_desc    Optional. The description of the check.
	 * @param    string $details       Optional. More params to front end.
	 */
	public function pass( $message, $options = [] ) {

		foreach ( $options as $key => $value ) {
			//variables variables
			$$key = $value;
		}

		$this->json['status']     = 'passed';
		$this->json['short_desc'] = $short_desc || '';
		$this->json['button']     = false;
		$this->json['message']    = $message;
		$this->json['details']    =
			$details
				? is_array( $details )
					? $details
					: array( $details )
				: [];
	}

	/**
	 * If the test is fail, returns formated information.
	 *
	 * @since    1.0.0
	 * @param    string $message       The message of failure.
	 * @param    string $short_desc    Optional. The description of the check.
	 * @param    string $action        Optional. The action that fires the button.
	 * @param    string $details       Optional. More params to front end.
	 */
	public function fail( $message, $options = [] ) {

		foreach ( $options as $key => $value ) {
			//variables variables
			$$key = $value;
		}

		$this->json['status']     = 'fail';
		$this->json['short_desc'] = $short_desc || '';
		$this->json['button']     = true;
		$this->json['action']     = $action || '';
		$this->json['message']    = $message;
		$this->json['details']    =
			$details
				? is_array( $details )
					? $details
					: array( $details )
				: [];
	}
}

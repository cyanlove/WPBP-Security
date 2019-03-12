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
	 * @access   public
	 * @var      array $json    The array that will be returned to the admin (later transformed to JSON)
	 */
	public $json = array();

	/**
	 * The array containing the valid fields and how the should be escaped.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $valid    Valid fields as key and how the should be escaped as value.
	 */
	private $valid = array(
		'short_desc' => 'html',
		'message'    => 'html',
		'action'     => 'html',
		'data'       => 'html',
	);

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

	}

	/**
	 * Validates and escapes the args passed
	 *
	 * @since    1.0.0
	 * @param    array $args       The args to be validated ans escaped.
	 */
	private function validate( $args = array() ) {

		foreach ( $args as $arg => $value ) {

			if ( array_key_exists( $arg, $this->valid ) ) {

				switch ( $this->valid[ $arg ] ) {
					case 'html':
						$value = esc_html( $value );
						break;
					default:
						return;
				}
				$valid[ $arg ] = $value;
			}
		}

		$this->json = array_merge( $this->json, $valid );
	}

	/**
	 * If the test is passed, returns formated information.
	 *
	 * @since    1.0.0
	 * @param    array $args       The array of arguments.
	 */
	public function pass( $args = array() ) {

		$this->validate( $args );

		$this->json['status'] = 'passed';
		$this->json['button'] = false;
	}

	/**
	 * If the test is fail, returns formated information.
	 *
	 * @since    1.0.0
	 * @param    array $args       The array of arguments.
	 */
	public function fail( $args = array() ) {

		$this->validate( $args );

		$this->json['status'] = 'fail';
		$this->json['button'] = true;
	}
}

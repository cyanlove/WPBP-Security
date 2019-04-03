<?php

/**
 * Ddefines the mother class of every check
 *
 * This class sets a model for every check of this plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wp_security_bp
 * @subpackage wp_security_bp/includes
 */

/**
 * The Check class.
 *
 * This class sets a model for every check of this plugin.
 *
 * @since      1.0.0
 * @package    wp_security_bp
 * @subpackage wp_security_bp/includes
 * @author     Cyan Lovers <hello@cyanlove.com>
 */
class WP_Security_BP_Check {
	/**
	 * Default arguments for query_users().
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $query_users_default_params    Default arguments for query_users().
	 */
	private $query_users_default_params = array(
		'roles'   => array(
			'Super Admin',
			'Administrator',
		),
		'fields'  => array(
			'ID',
			'display_name',
			'user_login',
		),
		'include' => array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ),
	);

	protected function __construct() {}

	/**
	 * To fetch users from DB. Returns array of objects.
	 *
	 * @since    1.0.0
	 * @param    array    $new_params       Overwrites default params( $query_users_default_params ).
	 */
	protected function query_users( $new_params = array() ) {

		$params = array_merge( $this->query_users_default_params, $new_params );

		$args = array(
			'fields'   => $params['fields'],
			'role__in' => $params['roles'],
			'include'  => $params['include'],
		);

		return get_users( $args );
	}

	/**
	 * Iterates array of objects (each user fetched) and find a match witch checker.
	 *
	 * @since    1.0.0
	 * @param    array    $params       array of arrays of objects(users fetched).
	 * @param    string   $property     property of each object that needs to be compared with $checker.
	 * @param    string   $checker      parameter to check if its in the object of each user.
	 */
	protected function users_match( $params = array(), $property = 'display_name', $checker ) {

		foreach ( $params as $arr ) {
			foreach ( $arr as $obj ) {
				if ( $this->comparator( $obj->$property, $checker ) ) {

					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Treats and compares 2 parameters to find the match.
	 *
	 * @since    1.0.0
	 * @param    mixed   $matcher      property to be compared with $checker.
	 * @param    mixed   $checker      property to be compared with $matcher.
	 */
	protected function comparator( $matcher, $checker ) {

		$regex_replace = '#\.|_|-#';

		if ( gettype( $matcher ) === 'string' && gettype( $checker ) === 'string' ) {

			$matcher = strtolower( preg_replace( $regex_replace, '', $matcher ) );
			$checker = strtolower( preg_replace( $regex_replace, '', $checker ) );

			if ( $matcher === $checker ) {
				return true;
			} else {
				return false;
			}
		}

		return false;
	}

}

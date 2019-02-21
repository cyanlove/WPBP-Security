<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           wp_security_bp
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Security Best Practices
 * Plugin URI:        http://cyanlove.com/wp-security-bp-uri-pol-jaco/
 * Description:       Secure your WordPress installation by following the best practices.
 * Version:           1.0.0
 * Author:            Cyan Lovers
 * Author URI:        https://cyanlove.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-security-bp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_SECURITY_BP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-security-bp-activator.php
 */
function activate_wp_security_bp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-security-bp-activator.php';
	wp_security_bp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-security-bp-deactivator.php
 */
function deactivate_wp_security_bp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-security-bp-deactivator.php';
	wp_security_bp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_security_bp' );
register_deactivation_hook( __FILE__, 'deactivate_wp_security_bp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-security-bp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_security_bp() {

	$plugin = new WP_Security_BP();
	$plugin->run();

}
run_wp_security_bp();

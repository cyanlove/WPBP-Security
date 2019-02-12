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
 * @package           wpbp_security
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Best Practices Security
 * Plugin URI:        http://example.com/wpbp-security-uri/
 * Description:       Secure your WordPress installation by following the best practices.
 * Version:           1.0.0
 * Author:            Cyan Lovers
 * Author URI:        https://cyanlove.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpbp-security
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
define( 'WPBP_SECURITY_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpbp-security-activator.php
 */
function activate_wpbp_security() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpbp-security-activator.php';
	wpbp_security_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpbp-security-deactivator.php
 */
function deactivate_wpbp_security() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpbp-security-deactivator.php';
	wpbp_security_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpbp_security' );
register_deactivation_hook( __FILE__, 'deactivate_wpbp_security' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpbp-security.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpbp_security() {

	$plugin = new WPBP_Security();
	$plugin->run();

}
run_wpbp_security();

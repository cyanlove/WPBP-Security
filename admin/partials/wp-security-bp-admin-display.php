<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wp_security_bp
 * @subpackage wp_security_bp/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<h1>WordPress Security Best Practices</h1>
<?php

$plugin_name = 'wp-security-bp';
$files = new WP_Security_BP_Files( $plugin_name, admin_url( 'options-general.php?page=' . $plugin_name ) );
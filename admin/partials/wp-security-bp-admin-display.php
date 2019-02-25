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

$users = get_users( array( 'include' => array( 2, 3 ) ) );
foreach ( $users as $user ) {
    if ( true ) {
        //var_dump( $user );
    }
}

add_action( 'admin_notices', 'caca');

function caca() {
    $class = 'notice notice-error';
    $message = __( 'Irks! An error has occurred.', 'sample-text-domain' );

    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
}

$plugin_name = 'wp-security-bp';
$files = new WP_Security_BP_Files( $plugin_name, admin_url( 'options-general.php?page=' . $plugin_name ) );
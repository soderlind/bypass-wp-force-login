<?php
/**
 * Remove options when the plugin is uninstalled.
 *
 * @author: Per Soderlind
 * @since: 20.03.2021
 *
 * @package Soderlind\Plugin\Bypass
 */

declare( strict_types = 1 );

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'bypass_force_login_option_name' );

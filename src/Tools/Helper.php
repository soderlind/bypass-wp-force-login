<?php declare( strict_types = 1 );

namespace Soderlind\Plugin\Bypass\Tools;

class Helper {


	public static function CheckInstallation() {
		// register_activation_hook(
		// 	__FILE__,
		// 	function () {
		// 		if ( ! is_plugin_active( 'wp-force-login/wp-force-login.php' ) ) {
		// 			set_transient( 'bypass-wp-force-login-activate', true, 5 );
		// 		}
		// 	}
		// );

		if ( ! is_plugin_active( 'wp-force-login/wp-force-login.php' ) ) {
			set_transient( 'bypass-wp-force-login-deactivate', true, 5 );
		}

		add_action(
			'admin_notices',
			function () : void {
				if ( get_transient( 'bypass-wp-force-login-deactivate' ) ) {

					$msg[] = '<div class="notice notice-error is-dismissible ">';
					$msg[] = '<p><strong>Bypass Force Login</strong></p>';
					$msg[] = '<p>Please install and activate <a href="https://wordpress.org/plugins/wp-force-login/">Force Login</a> before you activate Bypass Force Login</p>';
					$msg[] = '</div>';
					echo implode( PHP_EOL, $msg );

					delete_transient( 'bypass-wp-force-login-activate' );
					deactivate_plugins( BYPASS_BASENAME );

				}
			}
		);
	}

	public static function write_log( $log ) {
		if ( true === WP_DEBUG ) {
			if ( is_scalar( $log ) ) {
				error_log( (string) $log );
			} else {
				error_log( print_r( $log, true ) );
			}
		}
	}
}

<?php declare( strict_types = 1 );
/**
 * Bypass Force Login
 *
 * @package     Bypass Force Login
 * @author      Per Soderlind
 * @copyright   2020 Per Soderlind
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Bypass Force Login
 * Plugin URI: https://github.com/soderlind/bypass-wp-force-login
 * GitHub Plugin URI: https://github.com/soderlind/bypass-wp-force-login
 * Description: This is an add-on to <a href="https://wordpress.org/plugins/wp-force-login/">Force Login</a>. It allows to to add bypass rules.
 * Version:     0.0.1
 * Author:      Per Soderlind
 * Author URI:  https://soderlind.no
 * Text Domain: bypass-wp-force-login
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
namespace Soderlind\Plugin\Bypass;

use Helper;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die();
}

define( 'BYPASS_ROOT', plugin_dir_path( __FILE__ ) );
define( 'BYPASS_BASENAME', plugin_basename( __FILE__ ) );

require_once BYPASS_ROOT . 'vendor/autoload.php';

Tools\Helper::CheckInstallation();

if ( is_admin() ) {
	$settings = new Settings();
} else {
	$options = new Options();
	$bypass = new Bypass( $options );
	$bypass->init();
}
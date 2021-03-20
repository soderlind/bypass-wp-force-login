<?php
/**
 * Apply Get options..
 *
 * @author: Per Soderlind
 * @since: 20.03.2021
 *
 * @package Soderlind\Plugin\Bypass
 */

declare( strict_types = 1 );

namespace Soderlind\Plugin\Bypass;

/**
 * Option class.
 */
class Options {

	/**
	 * Empty constructor.
	 */
	public function __construct() {}

	/**
	 * Retrieve an option from the database.
	 * Returns default setting if nothing found.
	 *
	 * @param string  $key Options key.
	 * @param boolean $default  Default option value.
	 * @return mixed
	 */
	public function get_option( string $key = '', $default = false ) {
		$bypass_options = $this->get_settings();
		$value          = ! empty( $bypass_options[ $key ] ) ? $bypass_options[ $key ] : $default;
		$value          = apply_filters( 'bypass_get_option', $value, $key, $default );

		return apply_filters( 'bypass_get_option_' . $key, $value, $key, $default );
	}

	/**
	 * Get value from textarea option
	 *
	 * @param string $key Option key.
	 *
	 * @return array
	 */
	public function get_textarea( string $key = '' ) : array {
		return ( false !== $this->get_option( $key ) ) ? explode( "\n", (string) $this->get_option( $key ) ) : [];
	}

	/**
	 * Retrieve all options.
	 *
	 * @return mixed
	 */
	public function get_settings() {
		$settings = \get_option( 'bypass_force_login_option_name' );
		return apply_filters( 'bypass_get_settings', $settings );
	}
}

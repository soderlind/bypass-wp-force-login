<?php
/**
 * Get options..
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
	 * Option name.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Object constructor.
	 *
	 * @param string $name Option name.
	 */
	public function __construct( string $name ) {
		$this->name = $name;
	}

	/**
	 * Retrieve an option from the database.
	 * Returns default setting if nothing found.
	 *
	 * @param string  $key Options key.
	 * @param boolean $default  Default option value.
	 * @return mixed
	 */
	public function get_option( string $key = '', $default = false ) {
		$all_options = $this->get_settings();
		return ! empty( $all_options[ $key ] ) ? $all_options[ $key ] : $default;
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
		return \get_option( $this->name );
	}
}

<?php
/**
 * Apply exceptions.
 *
 * @author: Per Soderlind
 * @since: 20.03.2021
 *
 * @package Soderlind\Plugin\Bypass
 */

declare( strict_types = 1 );

namespace Soderlind\Plugin\Bypass;

/**
 * Bypass class.
 */
class Bypass {
	/**
	 * Options.
	 *
	 * @var object
	 */
	private $options;

	/**
	 * Constructor.
	 *
	 * @param Options $options Option object.
	 */
	public function __construct( Options $options ) {
		$this->options = $options;
	}

	/**
	 * Apply exceptions.
	 *
	 * @return void
	 */
	public function init() {
		add_filter(
			'v_forcelogin_bypass',
			function( $bypass, $visited_url ) {
				$bypass_urls = $this->options->get_textarea( 'urls' );
				foreach ( (array) $bypass_urls as $bypass_url ) {
					if ( false !== strpos( $visited_url, $bypass_url ) ) {
						return true;
					}
				}

				$bypass_patterns = $this->options->get_textarea( 'patterns' );
				foreach ( (array) $bypass_patterns as $bypass_pattern ) {
					preg_match( $bypass_pattern, $visited_url, $matches, PREG_OFFSET_CAPTURE );
					if ( isset( $matches[0], $matches[0][1] ) ) {
						return true;
					}
				}

				$bypass_querystrings = $this->options->get_textarea( 'querystringparameters' );
				foreach ( (array) $bypass_querystrings as $bypass_querystring ) {
					if ( isset( $_GET[ $bypass_querystring ] ) ) {
						$bypass = true;
					}
				}

				return $bypass;
			},
			10,
			2
		);
	}

}

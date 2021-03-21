<?php
/**
 * Admin interface for options.
 *
 * @author: Per Soderlind
 * @since: 20.03.2021
 *
 * @package Soderlind\Plugin\Bypass
 */

declare( strict_types = 1 );

namespace Soderlind\Plugin\Bypass;

/**
 * Admin class.
 */
class Admin {

	/**
	 * Options array.
	 *
	 * @var array
	 */
	private $options;


	/**
	 * Admin menu  slug.
	 *
	 * @var string
	 */
	private $menu_slug = 'bypass-force-login';

	/**
	 * Admin page slug.
	 *
	 * @var string
	 */
	private $slug = 'bypass-force-login-admin';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_plugin_page' ] );
		add_action( 'admin_init', [ $this, 'page_init' ] );
		add_filter( 'plugin_action_links_' . BYPASS_BASENAME, [ $this, 'settings_link' ] );
	}

	/**
	 * Add plugin page.
	 *
	 * @return void
	 */
	public function add_plugin_page() {
		add_options_page(
			__( 'Bypass Force Login', 'bypass-wp-force-login' ),
			__( 'Bypass Force Login', 'bypass-wp-force-login' ),
			'manage_options',
			$this->menu_slug,
			[ $this, 'create_admin_page' ]
		);
	}

	/**
	 * Create admin page.
	 *
	 * @return void
	 */
	public function create_admin_page() {
		$this->options = get_option( 'bypass_force_login_option_name' ); ?>

		<div class="wrap">
			<h2>Bypass Force Login</h2>
			<p></p>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'bypass_force_login_option_group' );
					do_settings_sections( $this->slug );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register settings, sections and fields.
	 *
	 * @return void
	 */
	public function page_init() {
		register_setting(
			'bypass_force_login_option_group',
			'bypass_force_login_option_name',
			[ $this, 'sanitize' ]
		);

		add_settings_section(
			'bypass_force_login_setting_section',
			__( 'Exceptions', 'bypass-wp-force-login' ),
			[ $this, 'section_info' ],
			$this->slug
		);

		add_settings_field(
			'urls',
			__( 'URL', 'bypass-wp-force-login' ),
			[ $this, 'textarea' ],
			$this->slug,
			'bypass_force_login_setting_section',
			[
				'id'          => 'urls',
				'label_for'   => 'urls',
				'description' => __( 'Add the exact URL, one per line, that you want to bypass', 'bypass-wp-force-login' ),
				'placeholder' => home_url( '/page-name/' ),
			]
		);

		add_settings_field(
			'patterns',
			__( 'Pattern', 'bypass-wp-force-login' ),
			[ $this, 'textarea' ],
			$this->slug,
			'bypass_force_login_setting_section',
			[
				'id'          => 'patterns',
				'label_for'   => 'patterns',
				'description' => __( 'Add the <a href="https://tutorials.supunkavinda.blog/php/regex-syntax">regexp pattern</a>, one per line, for the URL that you want to bypass', 'bypass-wp-force-login' ),
				'placeholder' => '/string/',
			]
		);

		add_settings_field(
			'querystringparameters',
			__( 'Query String Parameter', 'bypass-wp-force-login' ),
			[ $this, 'textarea' ],
			$this->slug,
			'bypass_force_login_setting_section',
			[
				'id'          => 'querystringparameters',
				'label_for'   => 'querystringparameters',
				/* translators: Home URL as string input.*/
				'description' => sprintf( __( 'Bypass Page URL based on, one per line, the Query String Parameter, Eg: <b>parameter</b> as in %s?parameter=xyz', 'bypass-wp-force-login' ), home_url() ),
				'placeholder' => 'parameter',
			]
		);
	}


	/**
	 * Sanitize fields.
	 *
	 * @param array $input Input fields.
	 *
	 * @return array
	 */
	public function sanitize( array $input ) : array {
		$sanitary_values = [];
		if ( isset( $input['urls'] ) ) {
			$sanitary_values['urls'] = esc_textarea( $input['urls'] );
		}

		if ( isset( $input['patterns'] ) ) {
			$sanitary_values['patterns'] = esc_textarea( $input['patterns'] );
		}

		if ( isset( $input['querystringparameters'] ) ) {
			$sanitary_values['querystringparameters'] = esc_textarea( $input['querystringparameters'] );
		}

		return $sanitary_values;
	}

	/**
	 * Add section nfo.
	 *
	 * @return void
	 */
	public function section_info() {

	}


	/**
	 * Textarea.
	 *
	 * @param array $args Field arguments.
	 *
	 * @return void
	 */
	public function textarea( array $args ) : void {
		printf(
			'<textarea class="large-text" rows="5" name="bypass_force_login_option_name[%1$s]" id="%1$s" placeholder="%2$s">%3$s</textarea>',
			$args['id'],
			isset( $args['placeholder'] ) ? esc_attr( $args['placeholder'] ) : '',
			isset( $this->options[ $args['id'] ] ) ? esc_attr( $this->options[ $args['id'] ] ) : ''
		);
		if ( isset( $args['description'] ) ) {
			printf( '<p class="description">%s</p>', $args['description'] );
		}
	}

	/**
	 * Add settings link on plugin page.
	 *
	 * @param array $links Links.
	 *
	 * @return array
	 */
	public function settings_link( array $links ) : array {

		$settings_url = add_query_arg( 'page', $this->menu_slug, get_admin_url() . 'options-general.php' );
		$setting_link = '<a href="' . esc_url( $settings_url ) . '">' . __( 'Add exceptions', 'bypass-wp-force-login' ) . '</a>';

		// Adds the link to the end of the array.
		array_push( $links, $setting_link );

		return $links;
	}
}

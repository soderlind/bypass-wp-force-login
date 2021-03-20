<?php declare( strict_types = 1 );
namespace Soderlind\Plugin\Bypass;

class Admin {

	protected $panel;

	public function __construct() {
		$prefix = 'bypass';
		$this->panel  = new \TDP\OptionsKit( $prefix );
		$this->panel->set_page_title( __( 'Bypass Force Login' ) );

		// $this->register_action_buttons();

		add_filter( $prefix . '_menu', [ $this, 'menu' ] );
		add_filter( $prefix . '_settings_tabs', [ $this, 'tabs' ] );
		add_filter( $prefix . '_labels', [ $this, 'labels' ] );
		add_filter( $prefix . '_registered_settings_sections', [ $this, 'subsections' ] );
		add_filter( $prefix . '_registered_settings', [ $this, 'settings' ] );
	}

	/**
	 * Setup the menu for the options panel.
	 *
	 * @param array $menu
	 *
	 * @return array
	 */
	public function menu( $menu ) {
		// These defaults can be customized
		// $menu['parent'] = 'options-general.php';
		// $menu['menu_title'] = 'Settings Panel';
		// $menu['capability'] = 'manage_options';

		$menu['page_title'] = __( 'Bypass Force Login' );
		$menu['menu_title'] = $menu['page_title'];
		$menu['capability'] = 'manage_options';

		return $menu;
	}

	/**
	 * Register settings tabs.
	 *
	 * @param array $tabs
	 *
	 * @return array
	 */
	public function tabs( $tabs ) {
		return [
			'general' => __( 'General' ),
		];
	}

	/**
	 * Register settings subsections (optional)
	 *
	 * @param array $subsections
	 *
	 * @return array
	 */
	public function subsections( $subsections ) {
		return $subsections;
	}

	/**
	 * Register labels for the options panel.
	 *
	 * @param array $labels
	 *
	 * @return array
	 */
	public function labels( $labels ) {
		$labels = [
			'save'         => __( 'Save Me', 'bypass-wp-force-login' ),
			'success'      => __( 'Settings successfully saved.', 'bypass-wp-force-login' ),
			'upload'       => __( 'Select file', 'bypass-wp-force-login' ),
			'upload-title' => __( 'Insert file', 'bypass-wp-force-login' ),
			'multiselect'  => [
				'selectLabel'   => __( 'Press enter to select', 'bypass-wp-force-login' ),
				'SelectedLabel' => __( 'Selected', 'bypass-wp-force-login' ),
				'deselectLabel' => __( 'Press enter to remove', 'bypass-wp-force-login' ),
				'placeholder'   => __( 'Select option (type to search)', 'bypass-wp-force-login' ),
			],
			'error'        => __( 'Whoops! Something went wrong. Please check the following fields for more info:', 'bypass-wp-force-login' ),
		];

		return $labels;
	}

	/**
	 * Register settings fields for the options panel.
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	public function settings( $settings ) {
		$settings = [
			'general' => [
				[
					'id'   => 'urls',
					'name' => __( 'URL', 'bypass-wp-force-login' ),
					'desc' => __( 'Add the exact URL, one per line, that you want to bypass', 'bypass-wp-force-login' ),
					'type' => 'textarea',
				],
				[
					'id'   => 'patterns',
					'name' => __( 'Pattern', 'bypass-wp-force-login' ),
					'desc' => __( 'Add the <a href="https://tutorials.supunkavinda.blog/php/regex-syntax">regexp pattern</a>, one per line, for the URL that you want to bypass', 'bypass-wp-force-login' ),
					'type' => 'textarea',
				],
				[
					'id'   => 'querystring',
					'name' => __( 'Query String', 'bypass-wp-force-login' ),
					'desc' => __( 'Bypass Page URL based on, one per line, the Query String Parameter, Eg: <b>parameter</b> as in http://mydomani.com?parameter=xyz', 'bypass-wp-force-login' ),
					'type' => 'textarea',
				],
				// [
				// 	'id'       => 'wp_admin_roles',
				// 	'name'     => __( 'Restrict Dashboard Access', 'bypass-wp-force-login' ),
				// 	'desc'     => __( 'Restrict access to the WordPress dashboard for specific user roles. Administrators will always have access.', 'bypass-wp-force-login' ),
				// 	'type'     => 'multiselect',
				// 	'labels'   => [ 'placeholder' => __( 'Select one or more user roles from the list.', 'bypass-wp-force-login' ) ],
				// 	'multiple' => true,
				// 	'options'  => $this->get_roles(),
				// ],
			],
		];

		return $settings;
	}

	/**
	 * Register action buttons for the options panel.
	 */
	private function register_action_buttons() {
		$this->panel->add_action_button(
			array(
				'title' => __( 'View Addons', 'wp-user-manager' ),
				'url'   => 'https://wpusermanager.com/addons/?utm_source=WP%20User%20Manager&utm_medium=insideplugin&utm_campaign=WP%20User%20Manager&utm_content=settings-header',
			)
		);

		$this->panel->add_action_button(
			array(
				'title' => __( 'Read documentation', 'wp-user-manager' ),
				'url'   => 'https://docs.wpusermanager.com/?utm_source=WP%20User%20Manager&utm_medium=insideplugin&utm_campaign=WP%20User%20Manager&utm_content=settings-header',
			)
		);
	}


	/**
	 * @param bool $force
	 * @param bool $admin
	 * @return array
	 */
	private function get_roles( $force = true, $admin = false ) : array {
		$roles = [];

		$transient = get_transient( 'wpum_get_roles' );

		if ( $transient && ! $force ) {
			$roles = $transient;
		} else {

			global $wp_roles;
			$available_roles = $wp_roles->get_names();

			foreach ( $available_roles as $role_id => $role ) {
				if ( $role_id == 'administrator' && ! $admin ) {
					continue;
				}
				$roles[] = [
					'value' => esc_attr( $role_id ),
					'label' => esc_html( $role ),
				];
			}
			set_transient( 'wpum_get_roles', $roles, DAY_IN_SECONDS );

		}

		return $roles;
	}

}

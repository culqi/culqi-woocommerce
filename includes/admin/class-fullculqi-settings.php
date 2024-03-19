<?php
/**
 * This file Welcome Class
 *
 * PHP version 7.4
 *
 * @category Class
 * @package  FullCulqi/Welcome
 * @author   FullCulqi <username@example.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     culqi
 * @since    1.0.0
 */

/**
 * Settings Class
 *
 * @package Includes / Admin / Settings
 */

#[\AllowDynamicProperties]
class FullCulqi_Settings {

	/**
	 * Construct
	 */
	public function __construct() {
		$settings = fullculqi_get_settings();
		/**
		 * Error Log comentado
		 * error_log( print_r( $settings, true ) );
		 */
		$username_bd = $settings['username'];
		if ( $username_bd === '' || $username_bd === null ) {
			$GLOBALS['username'] = bin2hex( random_bytes( 5 ) );
		} else {
			$GLOBALS['username'] = $username_bd;
		}

		$settings = fullculqi_get_settings();
		$password_bd = $settings['password'];
		if ( $password_bd === '' || $password_bd === null ) {
			$GLOBALS['password'] = bin2hex( random_bytes( 10 ) );
		} else {
			$GLOBALS['password'] = $password_bd;
		}

		// Script JS & CSS.
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		// Menu.
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );

		// Register Form.
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}
	/**
	 * CSS & JS
	 *
	 * @return mixed
	 */
	public function enqueue_scripts() {

		$screen = get_current_screen();

		if ( isset( $screen->base ) && (
			$screen->base === 'culqi-integracion_page_fullculqi_addons' ||
			$screen->base === 'culqi-full-integration_page_fullculqi_addons'
		) ) {
			wp_enqueue_style(
				'fullculqi-css-addons',
				MPCULQI_URL . 'resources/assets/css/admin-addons.css',
				[],
				'1.0.0'
			);
		}

		if ( isset( $screen->base ) &&
			( $screen->base === 'culqi-integracion_page_fullculqi_settings' ||
				$screen->base === 'culqi-full-integration_page_fullculqi_settings' ||
				$screen->base === 'dashboard_page_fullculqi-welcome' )
		) {
			wp_enqueue_script(
				'fullculqi-js-settings',
				MPCULQI_URL . 'resources/assets/js/admin-settings.js',
				[ 'jquery' ],
				'1.0.0', // false.
				true
			);

			// Loading Gif.
			$img_loading = sprintf(
				'<img src="%s" style="width: auto;" />',
				admin_url( 'images/spinner.gif' )
			);

			// Success Icon.
			$img_success = sprintf(
				'<img src="%s" style="width: auto;" />',
				admin_url( 'images/yes.png' )
			);

			// Failure Icon.
			$img_failure = sprintf(
				'<img src="%s" style="width: auto;" />',
				admin_url( 'images/no.png' )
			);

			wp_localize_script(
				'fullculqi-js-settings',
				'fullculqi_vars',
				apply_filters(
					'fullculqi/settings/localize',
					[
						'url_ajax'       => admin_url( 'admin-ajax.php' ),
						'img_loading'    => $img_loading,
						'img_success'    => $img_success,
						'img_failure'    => $img_failure,
						// translators: %s represents the name of the posts.
						'delete_loading' => esc_html__( 'Deleting posts from %s.', 'fullculqi' ),
						'delete_error'   => esc_html__( 'Error deleting a post', 'fullculqi' ),
						// translators: %s represents the name of the posts.
						'delete_success' => esc_html__( '%s : Posts deleted.', 'fullculqi' ),
						'delete_cpts'    => array_keys( fullculqi_get_cpts() ),
						'text_confirm'   => sprintf(
							// translators: %s represents the name of the posts.
							esc_html__( 'If you continue, you will delete all the posts in %s', 'fullculqi' ),
							implode( ',', fullculqi_get_cpts() )
						),
						'is_welcome'     => $screen->base === 'dashboard_page_fullculqi-welcome' ? true : false,
						'nonce'          => wp_create_nonce( 'fullculqi-wpnonce' ),
					]
				)
			);
		}
	}


	/**
	 * Add to menu
	 *
	 * @return mixed
	 */
	public function admin_menu() {
		/*
			Se comentó el siguiente código para no mostrar el menú de Culqi Full Integration

			add_menu_page(
				esc_html__( 'Culqi Full Integration', 'fullculqi' ),
				esc_html__( 'Culqi Full Integration', 'fullculqi' ),
				'manage_options',
				'fullculqi_menu',
				'', //function
				'dashicons-cart',
				54.1
			);

			do_action('fullculqi/settings/before_menu');
		*/

		add_submenu_page(
			'woocommerce',
			esc_html__( 'Culqi', 'fullculqi' ),
			esc_html__( 'Culqi', 'fullculqi' ),
			'manage_options',
			'fullculqi_settings',
			[ $this, 'settings_page' ]
		);

		add_submenu_page(
			'fullculqi_menu',
			esc_html__( 'Webhooks', 'fullculqi' ),
			esc_html__( 'Webhooks', 'fullculqi' ),
			'manage_options',
			'fullculqi_webhooks',
			[ $this, 'webhooks_page' ]
		);

		do_action( 'fullculqi/settings/after_menu' );

		/*
			Se comento el siguiente codigo para no mostrar la pagina de addons

			add_submenu_page(
				'fullculqi_menu',
				esc_html__( 'Add-ons', 'fullculqi' ),
				esc_html__( 'Add-ons', 'fullculqi' ),
				'manage_options',
				'fullculqi_addons',
				[ $this, 'addons_page' ]
			);
		*/
	}

	/**
	 * Addons Page
	 *
	 * @return mixed
	 */
	public function addons_page() {

		$args = [
			'banner_1'        => MPCULQI_URL . 'resources/assets/images/letsgo_1.png',
			'banner_2'        => MPCULQI_URL . 'resources/assets/images/letsgo_2.png',
			'banner_3'        => MPCULQI_URL . 'resources/assets/images/letsgo_3.png',
			'banner_4'        => MPCULQI_URL . 'resources/assets/images/letsgo_4.png',
			'icon_wc'         => MPCULQI_URL . 'resources/assets/images/icon_woo.png',
			'icon_wp'         => MPCULQI_URL . 'resources/assets/images/icon_wp.png',
			'has_subscribers' => class_exists( 'FullCulqi_Subscription' ),
			'has_oneclick'    => class_exists( 'FullCulqi_CardCredit' ),
			'has_button'      => class_exists( 'FullCulqi_Button' ),
			'has_deferred'    => class_exists( 'FullCulqi_DF' ),
		];

		fullculqi_get_template( 'resources/layouts/admin/addons_page.php', $args );
	}


	/**
	 * Settings Page
	 *
	 * @return mixed
	 */
	public function settings_page() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(
				esc_html__(
					'You do not have sufficient permissions to access this page.',
					'fullculqi'
				)
			);
		}

		wp_enqueue_style(
			'fullculqi-modal-css',
			plugin_dir_url( __FILE__ ) . '../../admin/assets/css/modal.css?_=' . time(),
			[],
			'1.0.0'
		);

		wp_enqueue_style(
			'fullculqi-form-css',
			plugin_dir_url( __FILE__ ) . '../../admin/assets/css/form.css?_=' . time(),
			[],
			'1.0.0'
		);

		wp_enqueue_style(
			'fullculqi-btn-css',
			plugin_dir_url( __FILE__ ) . '../../admin/assets/css/btn.css?_=' . time(),
			[],
			'1.0.0'
		);

		wp_enqueue_style(
			'fullculqi-btncustom-css',
			plugin_dir_url( __FILE__ ) . '../../admin/assets/css/btncustom.css?_=' . time(),
			[],
			'1.0.0'
		);

		wp_enqueue_script(
			'fullculqi-bootstrap-js',
			plugin_dir_url( __FILE__ ) . '../../admin/assets/js/bootstrap.min.js?_=' . time(),
			[ 'jquery' ],
			'1.0.0', // false.
			true
		);

		wp_enqueue_script(
			'fullculqi-login-js',
			plugin_dir_url( __FILE__ ) . '../../admin/assets/js/fullculqi_login.js?_=' . time(),
			[ 'jquery', 'fullculqi-bootstrap-js' ],
			'1.0.0', // false.
			true
		);

		wp_enqueue_script(
			'fullculqi-btncustom-js',
			plugin_dir_url( __FILE__ ) . '../../admin/assets/js/fullculqi_btncustom.js?_=' . time(),
			[ 'jquery' ],
			'1.0.0', // false.
			true
		);

		wp_localize_script(
			'fullculqi-login-js',
			'fullculqi_merchants',
			[
				'url_merchants' => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( 'url-merc-wpnonce' ),
			]
		);

		fullculqi_get_template( 'resources/layouts/admin/settings_page.php' );
	}

	/**
	 * Webhooks Page
	 */
	public function webhooks_page() {

		$args = [
			'webhook_url'  => site_url( 'fullculqi-api/webhooks' ),
			'webhook_list' => get_option( 'fullculqi_webhooks' ),
		];

		fullculqi_get_template(
			'resources/layouts/admin/webhooks-page.php',
			$args
		);
	}
	/**
	 * Register Settings
	 *
	 * @return mixed
	 */
	public function register_settings() {
		// OLANDA INGRESO INPUTS FORM SETTINGS.
		do_action( 'fullculqi/settings/before_fields' );

		register_setting(
			'fullculqi_group', // Option group.
			'fullculqi_options', // Option name.
			[ $this, 'settings_sanitize' ] // Sanitize.
		);

		add_settings_section(
			'fullculqi_section', // ID.
			false, // Title.
			false, // Callback [ $ this, 'print_section_info' ].
			'fullculqi_page' // Page.
		);

		/*
			Se comento el siguiente codigo.

			add_settings_field(
				'fullculqi_commerce', // ID
				esc_html__( 'Commerce name', 'fullculqi' ), // Commerce Name
				[ $this, 'input_commerce' ], // Callback
				'fullculqi_page', // Page
				'fullculqi_section' // Section
			);
		*/

		add_settings_field(
			'fullculqi_enviroment',
			esc_html__( 'Elige el entorno donde activarás tu checkout', 'fullculqi' ),
			[ $this, 'input_enviroment' ],
			'fullculqi_page',
			'fullculqi_section'
		);

		add_settings_field(
			'fullculqi_pubkey', // ID.
			esc_html__( 'Llave Pública', 'fullculqi' ) .
				'<span class="tool" data-tip="Encuentra tu llave pública ingresando a la sección Desarrollo en tu Culqipanel, en la pestaña de API Keys" tabindex="2">' .
				'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
				<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247zm2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/> </svg>' .
				'</span> ', // Public Key.
			[ $this, 'input_pubkey' ], // Callback.
			'fullculqi_page', // Page.
			'fullculqi_section' // Section.
		);

		add_settings_field(
			'fullculqi_seckey', // ID.
			esc_html__( 'Llave Privada', 'fullculqi' ) .
				'<span class="tool" data-tip="Encuentra tu llave privada ingresando a la sección Desarrollo en tu Culqipanel, en la pestaña de API Keys" tabindex="2"> ' .
				'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
				<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247zm2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/> </svg>' .
				'</span> ', // Secret Key.
			[ $this, 'input_seckey' ], // Callback.
			'fullculqi_page', // Page.
			'fullculqi_section' // Section.
		);

		add_settings_field(
			'fullculqi_methods', // ID.
			esc_html__( 'Métodos de pago', 'fullculqi' ), // Logo.
			[ $this, 'input_methods' ], // Callback.
			'fullculqi_page', // Page.
			'fullculqi_section' // Section.
		);

		add_settings_field(
			'fullculqi_timexp', // ID.
			esc_html__( 'Tiempo de expiración de pago', 'fullculqi' ),
			[ $this, 'input_timexp' ],
			'fullculqi_page',
			'fullculqi_section'
		);

		add_settings_field(
			'fullculqi_notpay', // ID.
			esc_html__( 'Notificaciones de pagos', 'fullculqi' ) .
				'<span class="tool" data-tip="Ingresa a tu Culqipanel en la sección de eventos, hacer clic a +Añadir. Se abrirá un popup, en donde deberás escoger order.status.changed y pegar la siguiente URL" tabindex="2"> ' .
				'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
				<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247zm2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/> </svg>' .
				'</span> ', // Notify Pay.
			[ $this, 'input_notpay' ], // Callback.
			'fullculqi_page', // Page.
			'fullculqi_section' // Section.
		);

		add_settings_field(
			'fullculqi_username', // ID.
			esc_html__( 'Nombre usuario', 'fullculqi' ), // Username. Jose puso name  ''.
			[ $this, 'input_username' ], // Callback.
			'fullculqi_page', // Page.
			'fullculqi_section', // Section.
			array( 'class' => 'fullculqi_username' )
		);

		add_settings_field(
			'fullculqi_password', // ID.
			esc_html__( 'Password', 'fullculqi' ), // Username. Jose puso name  ''.
			[ $this, 'input_password' ], // Callback.
			'fullculqi_page', // Page.
			'fullculqi_section',
			array( 'class' => 'fullculqi_password' )
		);

		add_settings_field(
			'fullculqi_estado_pedido', // ID.
			esc_html__( 'Estado final del pedido', 'fullculqi' ), // Estado complete.
			[ $this, 'input_estado_pedido' ], // Callback.
			'fullculqi_page', // Page.
			'fullculqi_section', // Section.
			array( 'class' => 'fullculqi_estado_pedido' )
		);

		add_settings_field(
			'fullculqi_rsa_id', // ID.
			esc_html__( 'RSA Id', 'fullculqi' ) .
				'<span class="tool custom-ml" data-tip="Genera el id de tu llave RSA ingresando a la sección Desarrollo de tu CulqiPanel, en la pestaña de RSA Keys" tabindex="2"> ' .
				'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
				<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247zm2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/> </svg>' .
				'</span> ',
			[ $this, 'input_rsa_id' ],
			'fullculqi_page',
			'fullculqi_section'
		);

		add_settings_field(
			'fullculqi_rsa_pk', // ID.
			esc_html__( 'RSA Publickey', 'fullculqi' ) .
				'<span class="tool" data-tip="Genera tu llave RSA ingresando a la sección Desarrollo de tu CulqiPanel, en la pestaña de RSA Keys" tabindex="2"> ' .
				'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
				<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247zm2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/> </svg>' .
				'</span> ',
			[ $this, 'input_rsa_pk' ],
			'fullculqi_page',
			'fullculqi_section'
		);

		add_settings_field(
			'fullculqi_buttoncustom', // ID.
			esc_html__( 'Personalizar formulario de checkout', 'fullculqi' ), // Logo.
			[ $this, 'input_buttoncustom' ], // Callback.
			'fullculqi_page', // Page.
			'fullculqi_section' // Section.
		);

		add_settings_field(
			'fullculqi_logo', // ID.
			esc_html__( 'Logo URL', 'fullculqi' ), // Logo.
			[ $this, 'input_logo' ], // Callback.
			'fullculqi_page', // Page.
			'fullculqi_section' // Section.
		);

		add_settings_field(
			'fullculqi_colorpalette', // ID.
			esc_html__( 'Tema elegido', 'fullculqi' ), // Logo.
			[ $this, 'input_colorpalette' ], // Callback.
			'fullculqi_page', // Page.
			'fullculqi_section' // Section.
		);

		/*
			Se comento el siguiente codigo

			add_settings_field(
				'fullculqi_button_clear', // ID
				esc_html__( 'Delete all the entities', 'fullculqi' ), // Simple Payment
				[ $this, 'input_delete_all' ], // Callback
				'fullculqi_page', // Page
				'fullculqi_section' // Section
			);
		*/

		do_action( 'fullculqi/settings/after_fields' );
	}


	/**
	 * Sanitize fields
	 *
	 * @param  array $inputs Inputs Array.
	 * @return array
	 */
	public function settings_sanitize( $inputs = [] ) {

		$default = fullculqi_get_default();

		foreach ( $default as $key => $value ) {
			if ( ! isset( $inputs[ $key ] )
				|| empty( $inputs[ $key ] ) ) {
				$settings[ $key ] = $default[ $key ];

			} else {
				$settings[ $key ] = is_array( $inputs[ $key ] )
					? array_map( 'sanitize_text_field', $inputs[ $key ] )
					: sanitize_text_field( $inputs[ $key ] );
			}
		}

		return $settings;
	}


	/**
	 * Input Commerce
	 *
	 * @return void html
	 */
	public function input_commerce() {
		$settings = fullculqi_get_settings();

		fullculqi_get_template(
			'resources/layouts/admin/settings/input_commerce.php',
			$settings
		);
	}

	/**
	 * Input Publick Key
	 *
	 * @return void  html
	 */
	public function input_pubkey() {
		$settings = fullculqi_get_settings();

		fullculqi_get_template(
			'resources/layouts/admin/settings/input_pubkey.php',
			$settings
		);
	}

	/**
	 * Input Secret Key
	 *
	 * @return void  html
	 */
	public function input_seckey() {
		$settings = fullculqi_get_settings();

		fullculqi_get_template(
			'resources/layouts/admin/settings/input_seckey.php',
			$settings
		);
	}

	/**
	 * Input URL logo
	 *
	 * @return void  html
	 */
	public function input_methods() {
		$settings = fullculqi_get_settings();

		fullculqi_get_template(
			'resources/layouts/admin/settings/input_methods.php',
			$settings
		);
	}
	/**
	 * Input enviroment as.
	 */
	public function input_enviroment() {
		$settings = fullculqi_get_settings();

		fullculqi_get_template(
			'resources/layouts/admin/settings/input_enviroment.php',
			$settings
		);
	}

	/**
	 * Input URL time expiration
	 *
	 * @return void html
	 */
	public function input_timexp() {
		$settings = fullculqi_get_settings();

		fullculqi_get_template(
			'resources/layouts/admin/settings/input_timexp.php',
			$settings
		);
	}

	/**
	 * Input URL notify payment
	 *
	 * @return void html
	 */
	public function input_notpay() {
		$settings = fullculqi_get_settings();

		fullculqi_get_template(
			'resources/layouts/admin/settings/input_notpay.php',
			$settings
		);
	}

	/**
	 * Input URL notify payment
	 *
	 * @return void html
	 */
	public function input_username() {
		$settings = fullculqi_get_settings();

		fullculqi_get_template(
			'resources/layouts/admin/settings/input_username.php',
			$settings
		);
	}

	/**
	 * Input URL notify payment
	 *
	 * @return void html
	 */
	public function input_password() {
		$settings = fullculqi_get_settings();

		fullculqi_get_template(
			'resources/layouts/admin/settings/input_password.php',
			$settings
		);
	}

	/**
	 * Input Estado Pedido.
	 */
	public function input_estado_pedido() {
		$settings = fullculqi_get_settings();

		fullculqi_get_template(
			'resources/layouts/admin/settings/input_estado_pedido.php',
			$settings
		);
	}
	/**
	 * Input URL logo
	 *
	 * @return void html
	 */
	public function input_logo() {
		$settings = fullculqi_get_settings();

		fullculqi_get_template(
			'resources/layouts/admin/settings/input_logo.php',
			$settings
		);
	}
	/**
	 * Input Color.
	 */
	public function input_colorpalette() {
		$settings = fullculqi_get_settings();

		fullculqi_get_template(
			'resources/layouts/admin/settings/input_colorpalette.php',
			$settings
		);
	}
	/**
	 * Input Buton custom.
	 */
	public function input_buttoncustom() {
		$settings = fullculqi_get_settings();

		fullculqi_get_template(
			'resources/layouts/admin/settings/input_buttoncustom.php',
			$settings
		);
	}
	/**
	 * Input rsa id.
	 */
	public function input_rsa_id() {
		$settings = fullculqi_get_settings();

		fullculqi_get_template(
			'resources/layouts/admin/settings/input_rsa_id.php',
			$settings
		);
	}
	/**
	 * Input rsa pk.
	 */
	public function input_rsa_pk() {
		$settings = fullculqi_get_settings();

		fullculqi_get_template(
			'resources/layouts/admin/settings/input_rsa_pk.php',
			$settings
		);
	}

	/**
	 * Input Button Delete All
	 *
	 * @return void html
	 */
	public function input_delete_all() {
		fullculqi_get_template(
			'resources/layouts/admin/settings/input_delete_all.php'
		);
	}
}

new FullCulqi_Settings();

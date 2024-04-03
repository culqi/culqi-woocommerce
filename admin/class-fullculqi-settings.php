<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
#[\AllowDynamicProperties]
class FullCulqi_Settings {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}


	public function enqueue_scripts() {
		$screen = get_current_screen();

		if( isset($screen->base) && (
			$screen->base == 'culqi-integracion_page_fullculqi_addons' ||
			$screen->base == 'culqi-full-integration_page_fullculqi_addons'
		) ) {
			wp_enqueue_style(
				'fullculqi-css',
				MPCULQI_PLUGIN_URL . 'admin/assets/css/fullculqi_addons.css?_='.time()
			);
		}

		if( isset($screen->base) &&
			( $screen->base == 'culqi-integracion_page_fullculqi_settings' ||
				$screen->base == 'culqi-full-integration_page_fullculqi_settings' ||
				$screen->base == 'dashboard_page_fullculqi-welcome' )
		) {
			wp_enqueue_script( 'fullculqi-js', MPCULQI_PLUGIN_URL . 'admin/assets/js/fullculqi_admin.js?_='.time(), [ 'jquery' ], false, true );

			wp_localize_script( 'fullculqi-js', 'fullculqi',
				[
					'url_ajax'			=> admin_url('admin-ajax.php'),
					'url_loading'		=> admin_url('images/spinner.gif'),
					'url_success'		=> admin_url('images/yes.png'),
					'url_failure'		=> admin_url('images/no.png'),
					'sync_loading'		=> esc_html__('Synchronizing. It may take several minutes.','culqi'),
					'sync_success'		=> esc_html__('Complete synchronization.','culqi'),
					'delete_loading'	=> esc_html__('Deleting post from %s.','culqi'),
					'delete_success'	=> esc_html__('%s : Posts deleted.','culqi'),
					'delete_cpts'		=> fullculqi_get_cpts(),
					'text_confirm'		=> esc_html__('if you continue, you will delete all fullculqi posts','culqi'),
					'is_welcome'		=> $screen->base == 'dashboard_page_fullculqi-welcome' ? true : false,
					'nonce'				=> wp_create_nonce( 'fullculqi-wpnonce' ),
				]
			);
		}
	}


	public function add_admin_menu() {
/*
		add_menu_page(
			esc_html__('Culqi Full Integration','culqi'),
			esc_html__('Culqi Full Integration','culqi'),
			'manage_options',
			'fullculqi_menu',
			'', //function
			'dashicons-cart',
			54.1
		);

		do_action('fullculqi/settings/before_menu');
*/

		add_submenu_page(
			'fullculqi_menu',
			esc_html__('Settings','culqi'),
			esc_html__('Settings','culqi'),
			'manage_options',
			'fullculqi_settings',
			[ $this, 'menu_settings' ]
		);


		do_action('fullculqi/settings/after_menu');

		add_submenu_page(
			'fullculqi_menu',
			esc_html__('Add-ons','culqi'),
			esc_html__('Add-ons','culqi'),
			'manage_options',
			'fullculqi_addons',
			[ $this, 'menu_addons' ]
		);
	}

	public function menu_addons() {
		include_once MPCULQI_PLUGIN_DIR . 'admin/layouts/addons_options.php';
	}

	public function menu_settings() {

		if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__('You do not have sufficient permissions to access this page.','culqi') );
        }

		include_once MPCULQI_PLUGIN_DIR . 'admin/layouts/settings_options.php';
	}


	public function register_settings() {

		$settings = fullculqi_get_settings();

		do_action('fullculqi/settings/before_fields', $settings, $this);

		register_setting(
			'fullculqi_group', // Option group
			'fullculqi_options', // Option name
			[ $this, 'settings_sanitize' ] // Sanitize
		);

		add_settings_section(
			'fullculqi_section', // ID
			esc_html__('Culqi Full Integration Settings','culqi'), // Title
			false, // Callback [ $this, 'print_section_info' ]
			'fullculqi_page' // Page
		);

		add_settings_field(
			'fullculqi_commerce', // ID
			esc_html__('Commerce name','culqi'), // Commerce Name
			[ $this, 'input_commerce' ], // Callback
			'fullculqi_page', // Page
			'fullculqi_section' // Section
		);

		add_settings_field(
			'fullculqi_pubkey', // ID
			esc_html__('Public Key','culqi'), // Public Key
			[ $this, 'input_pubkey' ], // Callback
			'fullculqi_page', // Page
			'fullculqi_section' // Section
		);

		add_settings_field(
			'fullculqi_seckey', // ID
			esc_html__('Secret Key','culqi'), // Secret Key
			[ $this, 'input_seckey' ], // Callback
			'fullculqi_page', // Page
			'fullculqi_section' // Section
		);

		add_settings_field(
			'fullculqi_logo', // ID
			esc_html__('Logo URL','culqi'), // Logo
			[ $this, 'input_logo' ], // Callback
			'fullculqi_page', // Page
			'fullculqi_section' // Section
		);

		add_settings_field(
			'fullculqi_methods', // ID
			esc_html__('Metodos de pago','culqi'), // Logo
			[ $this, 'input_methods' ], // Callback
			'fullculqi_page', // Page
			'fullculqi_section' // Section
		);

		add_settings_field(
			'fullculqi_sync_payments', // ID
			__('Synchronize Payments','culqi'), // Button
			[ $this, 'button_sync_payments' ], // Callback
			'fullculqi_page', // Page
			'fullculqi_section' // Section
		);

		add_settings_field(
			'fullculqi_estado_pedido', // ID
			esc_html__( 'Estado final del pedido', 'fullculqi' )  , // Estado complete
			[ $this, 'input_estado_pedido' ], // Callback
			'fullculqi_page', // Page
			'fullculqi_section', // Section
		);

		do_action('fullculqi/settings/sync_fields', $settings, $this);

		add_settings_field(
			'fullculqi_woo_payment', // ID
			esc_html__('Activate Payment Method in Woocommerce','culqi'), // Simple Payment
			[ $this, 'input_woo_payment' ], // Callback
			'fullculqi_page', // Page
			'fullculqi_section' // Section
		);

		add_settings_field(
			'fullculqi_button_clear', // ID
			esc_html__('Delete all','culqi'), // Simple Payment
			[ $this, 'input_delete_all' ], // Callback
			'fullculqi_page', // Page
			'fullculqi_section' // Section
		);

		do_action('fullculqi/settings/after_fields', $settings, $this);
	}


	public function settings_sanitize($inputs) {

		$default = fullculqi_get_default();
		$settings = array_map('sanitize_text_field', $inputs);

		foreach( $default as $key => $value) {
			if( !isset($settings[$key]) || empty($settings[$key]) )
				$settings[$key] = $default[$key];
		}

		return $settings;
	}

	public function print_section_info() {
		echo '<div class="fullculqi_section">'.esc_html__('Options','culqi').'</div>';
	}

	public function input_commerce() {
		$settings = fullculqi_get_settings();

		echo '<label for="fullculqi_commerce">
				<input type="text" id="fullculqi_commerce" class="regular-text" name="fullculqi_options[commerce]" value="' . esc_html( $settings['commerce'] ) . '"/>
			</label>';
	}

	public function input_pubkey() {
		$settings = fullculqi_get_settings();

		echo '<label for="fullculqi_pubkey">
				<input type="text" id="fullculqi_pubkey" class="regular-text" name="fullculqi_options[public_key]" value="' . esc_html( $settings['public_key'] ) . '"/>
			</label>';
	}

	public function input_seckey() {
		$settings = fullculqi_get_settings();

		echo '<label for="fullculqi_seckey">
				<input type="text" id="fullculqi_seckey" class="regular-text" name="fullculqi_options[secret_key]" value="' . esc_html( $settings['secret_key'] ) . '"/>
			</label>';
	}

	public function input_methods() {
		$settings = fullculqi_get_settings();

		echo '<label for="fullculqi_logo">
				<input type="text" id="fullculqi_logo" class="regular-text" name="fullculqi_options[logo_url]" value="' . esc_html( $settings['creditcard'] ) . '"/>
				<p class="help">'.esc_html__('This logo will appear in the Culqi Modal/Popup','culqi').'</p>
			</label>';
	}

	public function input_logo() {
		$settings = fullculqi_get_settings();

		echo '<label for="fullculqi_logo">
				<input type="text" id="fullculqi_logo" class="regular-text" name="fullculqi_options[logo_url]" value="' . esc_html( $settings['logo_url'] ) . '"/>
				<p class="help">'.esc_html__('This logo will appear in the Culqi Modal/Popup','culqi').'</p>
			</label>';
	}

	public function button_sync_payments() {
		echo '<label for="fullculqi_sync_payments">
				'.esc_html__('Last','culqi').' <input type="number" id="fullculqi_sync_payments_records" step="1" id="" value="100" style="width:55px;" /> '.esc_html__('records','culqi').'
				<button id="fullculqi_sync_payments" class="fullculqi_sync_button" data-action="payments">'.esc_html__('Synchronize Now','culqi').'</button>
				<span id="fullculqi_sync_payments_loading"></span>
			</label>';
	}

	public function input_woo_payment() {
		$settings = fullculqi_get_settings();
		$disabled = false;

		if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
			$disabled = true;
			echo '<div style="color: red;">';
			esc_html_e( 'You do not have Woocommerce activated', 'culqi' );
			echo '</div>';
		}

		echo '<label for="fullculqi_woo_payment">
				<input type="checkbox" id="fullculqi_woo_payment" name="fullculqi_options[woo_payment]" value="yes" '.checked($settings['woo_payment'], 'yes', false).' '.disabled($disabled, true, false).' />
				<p class="help">'.esc_html__('If checked, the Culqi payment method will appear in Woocommerce.', 'culqi').'</p>
			</label>';

		if( $settings['woo_payment'] == 'yes' ) {
			echo '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=fullculqi' ) ) . '">' . esc_html__( 'Customize Culqi Payment Gateway', 'culqi' ) . '</a>';
		}
	}

	public function input_delete_all() {
		echo '<label for="fullculqi_delete_all">
				<button id="fullculqi_delete_all" class="fullculqi_delete_all button button-secondary button-hero">'.esc_html__('Clear all','culqi').'</button>
				<div id="fullculqi_delete_all_loading"></div>
			</label>';
	}

	public function input_estado_pedido() {
		$settings = fullculqi_get_settings();

		echo '<label for="fullculqi_estado_pedido">
				<input type="text" id="fullculqi_estado_pedido" class="regular-text" name="fullculqi_options[estado_pedido]" value="' . esc_html( $settings['estado_pedido'] ) . '"/>
			</label>';
	}

}
?>

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
 * Welcome Class
 *
 * @package Includes / Admin / Welcome
 */

#[\AllowDynamicProperties]
class FullCulqi_Welcome {

	/**
	 * Construct
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'welcome_panel' ] );
		add_action( 'admin_menu', [ $this, 'welcome_menu' ] );
		add_action( 'admin_head', [ $this, 'welcome_remove' ] );
	}

	/**
	 * Func El
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
	}

	/**
	 * Welcome Panel.
	 *
	 * @return mixed
	 */
	public function welcome_panel() {
		if ( isset( $_POST['fullculqi_install'] ) &&
			isset( $_POST['fullculqi_options']['commerce'] ) &&
			! empty( $_POST['fullculqi_options']['commerce'] ) &&
			isset( $_POST['fullculqi_options']['public_key'] ) &&
			! empty( $_POST['fullculqi_options']['public_key'] ) &&
			isset( $_POST['fullculqi_options']['secret_key'] ) &&
			! empty( $_POST['fullculqi_options']['secret_key'] ) &&
			wp_verify_nonce( $_POST['fullculqi_install'], 'fullculqi_wpnonce' )
		) {
			$fullculqi_options = array_map( 'esc_html', $_POST['fullculqi_options'] );

			// var _ dump ( $ fullculqi_options ) ; exit ( 1 ) ;.
			update_option( 'fullculqi_options', $fullculqi_options );

			wp_safe_redirect(
				add_query_arg(
					[ 'page' => 'fullculqi_settings' ],
					admin_url( 'admin.php' )
				)
			);
		}

		if ( isset( $_POST['fullculqi_options'] ) ) {
			$options = is_array($_POST['fullculqi_options']) ? $_POST['fullculqi_options'] : [];

			$settings = new WC_Gateway_FullCulqi();
			if ( isset( $options['enabled'] ) ) {
				$settings->settings['enabled'] = $options['enabled'];
			} else {
				$settings->settings['enabled'] = 'no';
			}
			update_option( 'woocommerce_fullculqi_settings', $settings->settings );
		}

		if ( ! get_transient( 'fullculqi_activator' ) ) {
			return;
		}

		// Delete the redirect transient.
		delete_transient( 'fullculqi_activator' );

		// Bail if activating from network, or bulk.
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		// Redirect to panel welcome.
		wp_safe_redirect(
			add_query_arg(
				[ 'page' => 'fullculqi_settings' ],
				admin_url( 'admin.php' )
			)
		);
	}


	/**
	 * Welcome Menu
	 *
	 * @return mixed
	 */
	public function welcome_menu() {

		add_dashboard_page(
			esc_html__( 'Welcome to FullCulqi Integration', 'fullculqi' ),
			esc_html__( 'FullCulqi Integration', 'fullculqi' ),
			'manage_options',
			'fullculqi-welcome',
			[ $this, 'welcome_content' ]
		);
	}

	/**
	 * Welcome Content
	 *
	 * @return mixed
	 */
	public function welcome_content() {
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

		wp_localize_script(
			'fullculqi-login-js',
			'fullculqi_merchants',
			[
				'url_merchants' => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( 'url-merc-wpnonce' ),
			]
		);

		fullculqi_get_template( 'resources/layouts/admin/welcome-panel.php' );
	}

	/**
	 * Remove Welcome Panel
	 *
	 * @return mixed
	 */
	public function welcome_remove() {
		remove_submenu_page( 'index.php', 'fullculqi-welcome' );
	}
}

new FullCulqi_Welcome();

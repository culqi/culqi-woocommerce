<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
#[\AllowDynamicProperties]
class FullCulqi_Admin {

	public function __construct() {
		add_action( 'admin_init', [ $this, 'welcome_panel' ] );
		add_action( 'admin_menu', [ $this, 'welcome_menu' ] );
		add_action( 'admin_head', [ $this, 'welcome_remove' ] );

		//Order detail
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes'] );
	}


	public function add_meta_boxes() {

		$method_array = fullculqi_get_woo_settings();

		if( isset($method_array['enabled']) && $method_array['enabled'] == 'yes' ) {
			add_meta_box(
				'fullculqi_payment_log',
				__( 'FullCulqi payment transactions log', 'culqi' ),
				[ $this, 'payment_log' ],
				'shop_order',
				'normal',
				'core'
			);
		}
	}


	public function payment_log($post, $metabox) {

		$args = array( 'payment_log' => get_post_meta($post->ID, 'culqi_log', true) );
		wc_get_template('admin/layouts/metaboxes/metabox_order_log.php', $args, false, MPCULQI_PLUGIN_DIR );
	}


	public function welcome_panel() {

		if( isset($_POST['fullculqi_install']) &&
			isset($_POST['fullculqi_options']['commerce']) &&
			!empty($_POST['fullculqi_options']['commerce']) &&
			isset($_POST['fullculqi_options']['public_key']) &&
			!empty($_POST['fullculqi_options']['public_key']) &&
			isset($_POST['fullculqi_options']['secret_key']) &&
			!empty($_POST['fullculqi_options']['secret_key']) &&
			wp_verify_nonce( $_POST['fullculqi_install'], 'fullculqi_wpnonce' )
		) {
			//var_dump($_POST['fullculqi_options']); exit(1);
			$fullculqi_options = array_map( 'sanitize_text_field', $_POST['fullculqi_options'] );

			update_option('fullculqi_options', $fullculqi_options);

			wp_safe_redirect(
				add_query_arg(
					array( 'page' => 'fullculqi_settings' ),
					admin_url( 'admin.php' )
				)
			);
		}

		if ( ! get_transient( 'fullculqi_activator' ) ) {
			return;
		}

		// Delete the redirect transient
		delete_transient( 'fullculqi_activator' );

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		// Redirect to panel welcome
		wp_safe_redirect(
			add_query_arg(
				array( 'page' => 'fullculqi_settings' ),
				admin_url( 'index.php' )
			)
		);
	}


	public function welcome_menu() {
		add_dashboard_page(
			__('Welcome to FullCulqi Integration','culqi'),
			__('FullCulqi Integration','culqi'),
			'manage_options',
			'fullculqi-welcome',
			[$this, 'welcome_content']
		);
	}

	public function welcome_content() {
		fullculqi_get_template( 'admin/layouts/welcome_panel.php', false);
	}

	public function welcome_remove() {
		remove_submenu_page( 'index.php', 'fullculqi-welcome' );
	}

}
?>

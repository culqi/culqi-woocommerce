<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * FullCulqi Class
 * @since  1.0.0
 * @package Includes / FullCulqi
 */
#[\AllowDynamicProperties]
class FullCulqi {

	/**
	 * Plugin Instance
	 */
	protected static $_instance = null;

	/**
	 * Settings Instance
	 */
	protected $settings;

	/**
	 * Admin Instance
	 */
	protected $admin;

	/**
	 * Payment Instance
	 */
	protected $payment;

	/**
	 * Checkout Instance
	 */
	protected $checkout;

	/**
	 * Ajax Instance
	 */
	protected $ajax;

	/**
	 * License Instance
	 */
	protected $license;

	/**
	 * Ensures only one instance is loaded or can be loaded.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'fullculqi' ), '2.1' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'fullculqi' ), '2.1' );
	}


	/**
	 * Construct
	 * @return mixed
	 */
	function __construct() {

		$this->load_dependencies();
		$this->admin_js();

		add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ], 0 );
	}

	/**
	 * Load Dependencies
	 * @return mixed
	 */
	private function load_dependencies() {

		require_once MPCULQI_DIR . 'vendor/autoload.php';
		require_once MPCULQI_DIR . 'includes/functions.php';
		require_once MPCULQI_DIR . 'includes/class-fullculqi-i18n.php';
		require_once MPCULQI_DIR . 'includes/class-fullculqi-cpt.php';
		require_once MPCULQI_DIR . 'includes/class-fullculqi-logs.php';
		require_once MPCULQI_DIR . 'includes/class-fullculqi-ajax.php';

		// Endpoint
		require_once MPCULQI_DIR . 'includes/class-fullculqi-endpoints.php';
		require_once MPCULQI_DIR . 'includes/class-fullculqi-webhooks.php';

		// Syncs
		require_once MPCULQI_DIR . 'includes/syncs/class-fullculqi-cards.php';
		require_once MPCULQI_DIR . 'includes/syncs/class-fullculqi-charges.php';
		require_once MPCULQI_DIR . 'includes/syncs/class-fullculqi-login.php';
		require_once MPCULQI_DIR . 'includes/syncs/class-fullculqi-refunds.php';
		require_once MPCULQI_DIR . 'includes/syncs/class-fullculqi-orders.php';
		require_once MPCULQI_DIR . 'includes/syncs/class-fullculqi-customers.php';

		// 3rd-party
		require_once MPCULQI_DIR . 'includes/3rd-party/plugins/woocommerce/class-fullculqi-wc.php';

		if( is_admin() ) {
			require_once MPCULQI_DIR . 'includes/admin/class-fullculqi-updater.php';
			require_once MPCULQI_DIR . 'includes/admin/class-fullculqi-upgrader.php';
			require_once MPCULQI_DIR . 'includes/admin/class-fullculqi-settings.php';
			require_once MPCULQI_DIR . 'includes/admin/class-fullculqi-welcome.php';

			// Metaboxes
			require_once MPCULQI_DIR . 'includes/admin/metaboxes/class-fullculqi-metaboxes.php';
			require_once MPCULQI_DIR . 'includes/admin/metaboxes/class-fullculqi-orders.php';
			require_once MPCULQI_DIR . 'includes/admin/metaboxes/class-fullculqi-charges.php';
			require_once MPCULQI_DIR . 'includes/admin/metaboxes/class-fullculqi-customers.php';
		}
	}


	/**
	 * Plugins Loaded
	 * @return mixed
	 */
	public function plugins_loaded() {
		global $culqi, $culqi_token;

		$settings = fullculqi_get_settings();

		// Culqi Global
		if( isset( $settings['secret_key'] ) && ! empty( $settings['secret_key'] ) ) {
			$culqi = new Culqi\Culqi( [ 'api_key' => $settings['secret_key'] ] );
		}

		// Culqi Token
		if( isset( $settings['public_key'] ) && ! empty( $settings['public_key'] ) ) {
			$culqi_token = new Culqi\Culqi( [ 'api_key' => $settings['public_key'] ] );
		}
	}

	private function admin_js() {
		function enqueue_woocommerce_admin_custom_js($hook) {
				wp_enqueue_script('woocommerce-admin-custom', MPCULQI_URL . 'admin/assets/js/woocommerce-admin-custom.js', array('jquery'), '1.0', true);
		}
		
		add_action('admin_enqueue_scripts', 'enqueue_woocommerce_admin_custom_js');
		
	}

}
?>

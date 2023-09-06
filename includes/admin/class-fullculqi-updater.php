<?php
/**
 * Updater Class
 * @since  1.0.0
 * @package Includes / Updater
 */

#[\AllowDynamicProperties]
class FullCulqi_Updater {

	/**
	 * Construct
	 */
	public function __construct() {

		// Error messages
		add_action( 'admin_notices', [ $this, 'updater_notices' ] );
	}


	/**
	 * Deactivate plugins
	 * @return mixed
	 */
	public function updater_notices() {

		// Check if the Culqi CC plugin is activated
		$is_cc_activated = is_plugin_active( 'wp-culqi-integration-creditcard/index.php' );
		$is_cc_version = defined( 'MPCULQI_CC_VERSION' ) && version_compare( MPCULQI_CC_VERSION, '2.0.0', '<' );

		if( $is_cc_activated && $is_cc_version ) {
			$args_cc = [
				'title'		=> esc_html__( 'Culqi One Click update required', 'fullculqi' ),
				'content'	=> esc_html__( 'Currently, the Culqi One Click plugin is activated, please update it to 2.0.0 version', 'fullculqi' ),
				'class_title'	=> 'notice-title',
				'class_box'		=> 'notice notice-error',
			];

			fullculqi_get_template( 'resources/layouts/admin/notice-box.php', $args_cc );
		}


		// Check if the Culqi Subscription plugin is activated
		$is_subs_activated = is_plugin_active( 'wp-culqi-integration-subscription/index.php' );
		$is_subs_version = defined( 'MPCULQI_VERSION' ) && version_compare( MPCULQI_VERSION, '2.0.0', '<' );

		if( $is_subs_activated && $is_subs_version ) {
			$args_subs = [
				'title'		=> esc_html__( 'Culqi Subscription update required', 'fullculqi' ),
				'content'	=> esc_html__( 'Currently, the Culqi Subscription plugin is activated, please update it to 2.0.0 version', 'fullculqi' ),
				'class_title'	=> 'notice-title',
				'class_box'		=> 'notice notice-error',
			];

			fullculqi_get_template( 'resources/layouts/admin/notice-box.php', $args_subs );
		}


		// Check if the Culqi Button Subs plugin is activated
		$is_bt_activated = is_plugin_active( 'wp-culqi-integration-button/index.php' );
		$is_bt_version = defined( 'MPCULQI_BT_VERSION' ) && version_compare( MPCULQI_BT_VERSION, '2.0.0', '<' );

		if( $is_bt_activated && $is_bt_version ) {
			$args_bt = [
				'title'		=> esc_html__( 'Culqi Button update required', 'fullculqi' ),
				'content'	=> esc_html__( 'Currently, the Culqi Button plugin is activated, please update it to 2.0.0 version', 'fullculqi' ),
				'class_title'	=> 'notice-title',
				'class_box'		=> 'notice notice-error',
			];

			fullculqi_get_template( 'resources/layouts/admin/notice-box.php', $args_bt );
		}


		// Check if the Culqi Deferred Payment plugin is activated
		$is_df_activated = is_plugin_active( 'wp-culqi-integration-capture/index.php' );
		$is_df_version = defined( 'MPCULQI_DF_VERSION' ) && version_compare( MPCULQI_DF_VERSION, '2.0.0', '<' );

		if( $is_df_activated && $is_df_version ) {
			$args_bt = [
				'title'		=> esc_html__( 'Culqi Deferred Payment update required', 'fullculqi' ),
				'content'	=> esc_html__( 'Currently, the Culqi Deferred Payment plugin is activated, please update it to 2.0.0 version', 'fullculqi' ),
				'class_title'	=> 'notice-title',
				'class_box'		=> 'notice notice-error',
			];

			fullculqi_get_template( 'resources/layouts/admin/notice-box.php', $args_bt );
		}
	}
}

new FullCulqi_Updater();

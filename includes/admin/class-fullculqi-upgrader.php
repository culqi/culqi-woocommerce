<?php
/**
 * Upgrader Class
 * @since  1.0.0
 * @package Includes / Upgrader
 */

#[\AllowDynamicProperties]
class FullCulqi_Upgrader {

	/**
	 * Construct
	 */
	public function __construct() {

		// Check available updates
		add_action( 'admin_notices', [ $this, 'check_available_upgrades' ] );

		// Process available updates
		add_action( 'wp_ajax_upgrade_2_0_0', [ $this, 'process_upgrade_2_0_0' ] );
	}

	/**
	 * Chec Available Updates
	 * @return mixed
	 */
	public function check_available_upgrades() {
		$plugin = get_file_data( MPCULQI_FILE, [ 'Version' => 'Version' ] );

		// Compare version 2.0.0
		if( version_compare( $plugin['Version'], '2.0.0', '>=' ) &&
			! get_option( 'fullculqi_2_0_0_upgraded', false ) ) {

			// Check if it has payments
			$count_posts = wp_count_posts( 'culqi_payments' );

			if( isset( $count_posts->publish ) && $count_posts->publish > 0 )
				$this->screen_upgrade_2_0_0();
			else
				update_option( 'fullculqi_2_0_0_upgraded', true );
		}

	}

	/**
	 * Screen box 2.0.0
	 * @return html
	 */
	public function screen_upgrade_2_0_0() {

		$args = [
			'title'		=> esc_html__( 'Culqi Integration update required', 'fullculqi' ),
			'content'	=> esc_html__( 'Culqi Integration plugin has been updated to 2.x.x version! To keep things running smoothly, we have to update your database to the newest version', 'fullculqi' ),
			'text_button'	=> esc_html__( 'Continue', 'fullculqi' ),
			'link_button'	=> add_query_arg([
					'action'	=> 'upgrade_2_0_0',
					'wpnonce'	=> wp_create_nonce( 'fullculqi-wpnonce' ),
					'return'	=> urlencode( fullculqi_get_current_admin_url() ),
				],
				admin_url( 'admin-ajax.php' )
			),
			'class_title'	=> 'notice-title',
			'class_box'		=> 'notice notice-warning notice-large',
			'class_button'	=> 'button button-primary',
			'version'		=> '2.0.0',
		];

		fullculqi_get_template( 'resources/layouts/admin/notice-box.php', $args );
	}



	/**
	 * Upgrade to 2.0.0 or higher
	 * @return mixed
	 */
	public function process_upgrade_2_0_0() {

		// Run a security check.
		check_ajax_referer( 'fullculqi-wpnonce', 'wpnonce' );

		// Check the permissions
		if( ! current_user_can( 'manage_options' ) )
			return;

		// Chek if this version was updated
		if( get_option( 'fullculqi_2_0_0_upgraded', false ) )
			return;

		// Set permission
		$this->set_capabilities();

		// Return to URL
		$return = isset( $_GET['return'] ) ? urldecode( $_GET['return'] ) : admin_url();

		// Charges
		$args = [
			'post_type'		=> 'culqi_payments',
			'numberposts'	=> -1,
		];

		$posts = get_posts( $args );

		if( $posts ) {

			foreach( $posts as $post ) {

				// Get
				$basic = get_post_meta( $post->ID, 'culqi_basic', true );

				// Update
				update_post_meta( $post->ID, 'culqi_creation_date', $basic['culqi_creation'] );

				// Process
				unset( $basic['culqi_creation'] );
				unset( $basic['culqi_card_brand'] );
				unset( $basic['culqi_card_type'] );
				unset( $basic['culqi_card_number'] );

				// Delete || New values
				update_post_meta( $post->ID, 'culqi_basic', $basic );

				// Status
				$status = get_post_meta( $post->ID, 'culqi_status', true );
				if( empty( $status ) )
					update_post_meta( $post->ID, 'culqi_status', 'captured' );

				// Change the CPT
				wp_update_post( [ 'ID' => $post->ID, 'post_type' => 'culqi_charges' ] );

				// Modify by 3rd parties
				do_action( 'fullculqi/upgrader/2_0_0/charges', $post->ID );
			}
		}

		do_action( 'fullculqi/upgrader/2_0_0/after' );

		update_option( 'fullculqi_2_0_0_upgraded', true );

		wp_redirect( $return );
		die();
	}


	/**
	 * Set Permission to Admin
	 */
	private function set_capabilities() {
		$administrator = get_role( 'administrator' );

		$admin_caps = apply_filters( 'fullculqi/set_capabilities', [
			'edit_others_charges',
			'edit_charges',
			'edit_published_charges',
			'publish_charges',

			'edit_others_orders',
			'edit_orders',
			'edit_published_orders',
			'publish_orders',

			'edit_others_customers',
			'edit_customers',
			'edit_published_customers',
			'publish_customers',
		] );

		foreach( $admin_caps as $cap )
			$administrator->add_cap( $cap );

		return true;
	}
}

new FullCulqi_Upgrader();

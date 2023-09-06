<?php

/**
 * Activator plugin
 */

#[\AllowDynamicProperties]
class fullculqi_Activator {
	
	/**
	 * Activate
	 * @return mixed
	 */
	static public function activate() {

		$settings = fullculqi_get_settings();

		if( !isset($settings['commerce']) || empty($settings['commerce']) ||
			!isset($settings['public_key']) || empty($settings['public_key']) ||
			!isset($settings['secret_key']) || empty($settings['secret_key'])
		) {
			set_transient( 'fullculqi_activator', true, 30 );
		}

		// Permissions
		self::set_capabilities();

		// Refresh Permalinks
		flush_rewrite_rules();
	}


	/**
	 * Set Permission to Admin
	 */
	public static function set_capabilities() {
		$administrator = get_role( 'administrator' );
	
		$admin_caps = apply_filters( 'fullculqi/set_capabilities', [
			//'delete_charges',
			//'delete_others_charges',
			//'delete_published_charges',
			
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
	}
}
?>
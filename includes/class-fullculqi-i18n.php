<?php
/**
 * i18n Class
 * @since  1.0.0
 * @package Includes / i18n
 */
#[\AllowDynamicProperties]
class FullCulqi_i18n {

	/**
	 * The domain specified for this plugin.
	 */
	private $domain = 'fullculqi';

	/**
	 * Construct
	 * Define the locale for this plugin for internationalization.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'load_plugin_textdomain' ] );
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			$this->domain,
			false,
			dirname( MPCULQI_BASE ) . '/languages/'
		);

	}
}

new FullCulqi_i18n();

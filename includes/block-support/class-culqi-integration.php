<?php

namespace Culqi;

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Culqi_Integration implements IntegrationInterface {

    /**
     * Returns the name of the integration.
     *
     * @return string
     */
    public function get_name() {
        return 'culqi';
    }

    /**
     * Initializes the integration.
     *
     * This is where you can register hooks or perform actions needed for the integration.
     */
    public function initialize() {
       
    }

    /**
     * Registers the payment method with WooCommerce Blocks.
     *
     * @param \Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry
     */

    /**
     * Returns the script handles to be enqueued in the editor.
     *
     * @return array
     */
    public function get_editor_script_handles() {
        return [ 'culqi-block' ];
    }

    /**
     * Returns the script handles to be enqueued on the frontend.
     *
     * @return array
     */
    public function get_script_handles() {
        return [ 'culqi-block' ];
    }

    /**
     * Returns additional data to be passed to the scripts.
     *
     * @return array
     */
    public function get_script_data() {
        return [];
    }

    public function is_active() {
        return (bool) get_option( 'culqi_enabled', false );
    }
}


<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use Culqi\Culqi_Integration;
use Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry;
class Culqi_Block {
    public function __construct() {
        add_action( 'woocommerce_blocks_payment_method_type_registration', [ $this, 'register_payment_method' ] );
    }

    public function register_payment_method( PaymentMethodRegistry $payment_method_registry ) {
        $payment_method_registry->register(new Culqi_Integration() );
    }
}

add_action( 'woocommerce_blocks_loaded', function() {
    $integration = new Culqi_Integration();
    $integration->initialize();
} );

new Culqi_Block();

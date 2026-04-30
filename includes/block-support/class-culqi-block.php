<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use Culqi\Culqi_Integration;
use Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry;
class Culqi_Block {
    private static $initialized = false;

    public function __construct() {
        add_action('woocommerce_blocks_payment_method_type_registration', [ $this, 'register_payment_method' ]);
        add_action('woocommerce_blocks_loaded', [ $this, 'init_integration' ]);
    }
    public function register_payment_method(PaymentMethodRegistry $payment_method_registry) {
        $payment_method_registry->register(new Culqi_Integration());
    }

    public function init_integration() {
        if (!self::$initialized) {
            $integration = new Culqi_Integration();
            $integration->initialize();
            self::$initialized = true;
        }
    }
}

new Culqi_Block();

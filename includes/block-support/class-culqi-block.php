<?php
/**
 * Registra Culqi_Integration en el PaymentMethodRegistry de WooCommerce Blocks.
 *
 * La clase Culqi_Block anterior tenía lógica duplicada: instanciaba
 * Culqi_Integration dos veces (en register_payment_method y en init_integration)
 * y usaba dos hooks distintos para lo mismo. Este archivo hace solo lo necesario.
 *
 * @package Culqi
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Culqi\Culqi_Integration;
use Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry;

add_action(
    'woocommerce_blocks_payment_method_type_registration',
    function ( PaymentMethodRegistry $payment_method_registry ) {
        $payment_method_registry->register( new Culqi_Integration() );
    }
);

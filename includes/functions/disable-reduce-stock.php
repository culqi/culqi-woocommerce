<?php

add_filter( 'woocommerce_can_reduce_order_stock', 'wcs_do_not_reduce_onhold_stock', 10, 2 );
function wcs_do_not_reduce_onhold_stock( $reduce_stock, $order ) {
    if ( $order->has_status( 'on-hold' ) && $order->get_payment_method() == 'culqi' ) {
        $reduce_stock = false;
    }
    return $reduce_stock;
}
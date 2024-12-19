<?php

function get_payment_type($id) {
    $type = (substr( $id, 0, 4 ) === "ord_") ? "order" : "charge";
    return $type;
}
function culqi_update_order(WP_REST_Request $request) {
    $authorization = $request->get_header('authorization');
    $token = explode(' ', $authorization)[1];
    $is_verified = verify_jwt_token($token);
    if($is_verified) {
        $data = json_decode($request->get_body(), true);
        $order_id = sanitize_text_field($data['orderId']);
        $status = sanitize_text_field($data['status']);
        $transaction_id = sanitize_text_field($data['transactionId']);
        $note_order_text = "order";
        if (is_numeric($order_id) && $order_id > 0) {
            $order = wc_get_order($order_id);
            
            if ($order) {
                $order->update_status($status, 'Order status updated.', true);
                $order->add_order_note('Order status changed to ' . $status);
                if(get_payment_type($transaction_id) == "charge") {
                    $card_number = sanitize_text_field($data['cardNumber']) ?? '';
                    $card_brand = sanitize_text_field($data['cardBrand']) ?? '';
                    $reference_code = sanitize_text_field($data['referenceCode']) ?? '';
                    $note_order_text = "charge";
                    wc_reduce_stock_levels($order_id);
                    $note_order_text = 'Culqi Charge Created:' . "\n" .
                        'Id: ' . $transaction_id . "\n" .
                        'Tarjeta: ' . $card_number . "\n" .
                        'Marca: ' . $card_brand . "\n" .
                        'Cod. Referencia: ' . $reference_code;

                    // Add the order note
                    $order->add_order_note($note_order_text);
                } else {
                    $order->add_order_note('Culqi '. $note_order_text .' created: '. $transaction_id);
                }
                
                return new WP_REST_Response(['message' => 'Order status updated successfully.'], 200);
            }
        }
    }
    return new WP_REST_Response(['message' => 'Error on update order status.'], 400);
}
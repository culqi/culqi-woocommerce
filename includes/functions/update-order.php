<?php
if (!defined('ABSPATH')) {
    exit;
}

function culqi_log($level, $message, $context = []) {
    static $logger = null;

    if ($logger === null) {
        $logger = Culqi_Logger::get_instance();
    }

    $valid_levels = ['debug', 'info', 'warning', 'error'];

    if (!in_array($level, $valid_levels, true)) {
        $level = 'error';
    }

    $base_context = [
        'action' => 'update_order',
    ];

    $logger->$level('Webhook', $message, array_merge($base_context, $context));
}

function culqi_get_payment_type($id) {
    $type = (substr( $id, 0, 4 ) === "ord_") ? "order" : "charge";
    return $type;
}
function culqi_update_order(WP_REST_Request $request) {
    culqi_log('info', 'Webhook received');

    $authorization = $request->get_header('authorization');

    if (empty($authorization)) {
        culqi_log('warning', 'Webhook missing authorization header');
        return new WP_REST_Response(['message' => 'Error on update order status.'], 400);
    }

    $token = explode(' ', $authorization)[1];
    $is_verified = culqi_verify_jwt_token($token);

    if (!$is_verified) {
        culqi_log('warning', 'Webhook token verification failed');
        return new WP_REST_Response(['message' => 'Error on update order status.'], 400);
    }

    if($is_verified) {
        culqi_log('debug', 'Processing webhook payload', [
            'raw_body' => $request->get_body(),
        ]);
        $data = json_decode($request->get_body(), true);
        $order_id = sanitize_text_field($data['orderId']);
        $status = sanitize_text_field($data['status']);
        $transaction_id = sanitize_text_field($data['transactionId']);
        $note_order_text = "order";
        if (is_numeric($order_id) && $order_id > 0) {
            $order = wc_get_order($order_id);

            if ($order) {
                culqi_log('info', 'Order found, updating status', [
                    'order_id' => $order_id,
                    'new_status' => $status,
                ]);
                $order->update_status($status, 'Order status updated.', true);
                $order->add_order_note('Order status changed to ' . $status);
                if(culqi_get_payment_type($transaction_id) == "charge") {
                    if ($status !== "refunded"){
                        $card_number = sanitize_text_field($data['cardNumber']) ?? '';
                        $card_brand = sanitize_text_field($data['cardBrand']) ?? '';
                        $reference_code = sanitize_text_field($data['referenceCode']) ?? '';
                        $note_order_text = "charge";
                        wc_reduce_stock_levels($order_id);

                        culqi_log('info', 'Stock reduced for charge order', [
                            'order_id' => $order_id,
                            'transaction_id' => $transaction_id,
                        ]);
                        $note_order_text = 'Culqi Charge Created:' . "\n" .
                            'Id: ' . $transaction_id . "\n" .
                            'Tarjeta: ' . $card_number . "\n" .
                            'Marca: ' . $card_brand . "\n" .
                            'Cod. Referencia: ' . $reference_code;

                        // Add the order note
                        $order->add_order_note($note_order_text);
                    }
                } else {
                    culqi_log('info', 'Processing order payment', [
                        'transaction_id' => $transaction_id,
                        'order_id' => $order_id,
                    ]);
                    if ($status === "pending") {
                        //$order->add_order_note('Culqi '. $note_order_text .' created: '. $transaction_id);
                        $cip = sanitize_text_field($data['cip']) ?? '';
                        $orderNumber = sanitize_text_field($data['orderNumber']) ?? '';

                        culqi_log('info', 'CIP order created', [
                            'order_id' => $order_id,
                            'order_number' => $orderNumber,
                            'cip' => $cip,
                            'transaction_id' => $transaction_id,
                        ]);

                        $note_order_text = 'Culqi Order Created:' . "\n" .
                            'Id: ' . $transaction_id . "\n" .
                            'CIP: ' . $cip . "\n" .
                            'Order Number: ' . $orderNumber;

                        $order->add_order_note($note_order_text);
                    }
                    if ($status === "processing" || $status === "completed") {
                        wc_reduce_stock_levels($order_id);
                        culqi_log('info', 'Stock reduced for order', [
                            'order_id' => $order_id,
                        ]);
                    }
                }

                culqi_log('info', 'Webhook processed successfully', [
                    'order_id' => $order_id,
                    'final_status' => $status,
                ]);
                return new WP_REST_Response(['message' => 'Order status updated successfully.'], 200);
            }
        } else {
            culqi_log('warning', 'Order not found for webhook update', [
                'order_id' => $order_id,
            ]);
        }
    }

    culqi_log('error', 'Webhook processing failed', [
        'order_id' => $order_id ?? 'unknown',
    ]);
    return new WP_REST_Response(['message' => 'Error on update order status.'], 400);
}

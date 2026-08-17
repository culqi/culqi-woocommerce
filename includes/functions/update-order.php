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

        $valid_statuses = ['pending', 'processing', 'completed', 'on-hold', 'cancelled', 'refunded', 'failed'];
        if (!in_array($status, $valid_statuses, true)) {
            culqi_log('warning', 'Invalid status from webhook', [
                'status' => $status,
                'order_id' => $order_id,
            ]);
            return new WP_REST_Response(['message' => 'Invalid status.'], 400);
        }

        if (is_numeric($order_id) && $order_id > 0) {
            $order = wc_get_order($order_id);

            if ($order) {
                // Idempotencia: evitar reprocesar el mismo pago confirmado
                if (in_array($status, ['processing', 'completed'], true)
                    && $order->get_transaction_id() === $transaction_id
                    && $order->is_paid()
                ) {
                    culqi_log('info', 'Order already paid with same transaction_id, skipping', [
                        'order_id' => $order_id,
                        'transaction_id' => $transaction_id,
                    ]);
                    return new WP_REST_Response(['message' => 'Order already processed.'], 200);
                }

                if (in_array($status, ['processing', 'completed'], true)) {
                    // Pago confirmado: usar payment_complete() para setear
                    // transaction_id, date_paid, hooks nativos y nota estándar.
                    // payment_complete() requiere que la orden ya esté en
                    // processing/completed. Como venimos de 'pending',
                    // primero transicionamos el estado y persistimos.
                    culqi_log('info', 'Payment confirmed, using payment_complete()', [
                        'order_id' => $order_id,
                        'transaction_id' => $transaction_id,
                        'status' => $status,
                    ]);

                    $order->set_status($status);
                    $order->save();
                    $order->payment_complete($transaction_id);

                    // Notas de auditoría de Culqi (payment_complete no las escribe)
                    if (culqi_get_payment_type($transaction_id) === 'charge') {
                        $card_number = sanitize_text_field($data['cardNumber'] ?? '');
                        $card_brand = sanitize_text_field($data['cardBrand'] ?? '');
                        $reference_code = sanitize_text_field($data['referenceCode'] ?? '');
                        $order->add_order_note(
                            'Culqi Charge:' . "\n" .
                            'Id: ' . $transaction_id . "\n" .
                            'Tarjeta: ' . $card_number . "\n" .
                            'Marca: ' . $card_brand . "\n" .
                            'Cod. Referencia: ' . $reference_code
                        );
                    }

                } elseif ($status === 'pending') {
                    culqi_log('info', 'Pending payment, using update_status()', [
                        'order_id' => $order_id,
                        'transaction_id' => $transaction_id,
                    ]);
                    $order->update_status('pending', 'Culqi order created.', true);

                    $cip = sanitize_text_field($data['cip'] ?? '');
                    $orderNumber = sanitize_text_field($data['orderNumber'] ?? '');
                    $order->add_order_note(
                        'Culqi Order Created:' . "\n" .
                        'Id: ' . $transaction_id . "\n" .
                        'CIP: ' . $cip . "\n" .
                        'Order Number: ' . $orderNumber
                    );

                } elseif ($status === 'refunded') {
                    culqi_log('info', 'Refund processed, using update_status()', [
                        'order_id' => $order_id,
                        'transaction_id' => $transaction_id,
                    ]);
                    $order->update_status('refunded', 'Refund processed by Culqi.', true);

                } else {
                    culqi_log('info', 'Status change via update_status()', [
                        'order_id' => $order_id,
                        'status' => $status,
                    ]);
                    $order->update_status($status, 'Order status updated by Culqi.', true);
                }

                culqi_log('info', 'Webhook processed successfully', [
                    'order_id' => $order_id,
                    'final_status' => $status,
                ]);
                return new WP_REST_Response(['message' => 'Order status updated successfully.'], 200);
            } else {
                culqi_log('warning', 'Order not found for webhook update', [
                    'order_id' => $order_id,
                ]);
            }
        } else {
            culqi_log('warning', 'Invalid order_id from webhook', [
                'order_id' => $order_id,
            ]);
        }
    }

    culqi_log('error', 'Webhook processing failed', [
        'order_id' => $order_id,
    ]);
    return new WP_REST_Response(['message' => 'Error on update order status.'], 400);
}

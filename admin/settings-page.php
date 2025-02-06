<?php if (!defined('ABSPATH')) exit; 

$config = culqi_get_config();
$status = $config->plugin_status ?? '';
$pk = $config->public_key ?? '';
$merchant = $config->merchant ?? '';
$payment_methods = $config->payment_methods ?? '';
$token = generate_token(true);
?>

<style>
    .iframe-container {
        display: flex;
        flex-direction: column;
        position: absolute;
        inset: 0px;
        width: calc(100% + 20px);
        height: 100svh;
        left: -20px;
        right: -20px;
        z-index: 100;
    }
    .iframe-container iframe {
        position: relative;
        border: none;
        width: 100%;
        flex: 1 1 0%;
        display: flex;
    }
</style>

<div class="wrap">
    <div class="iframe-container">
        <iframe 
            src="<?php echo esc_url( CULQI_CONFIG_URL . '?platform=woocommerce&status=' . urlencode( $status ) . '&pk=' . urlencode( $pk ) . '&merchant=' . urlencode( $merchant ) . '&activePaymentMethods=' . urlencode( $payment_methods ) . '&shop=' . get_site_url() . '&token=' . urlencode($token) ); ?>" 
            width="100%">
        </iframe>
    </div>
</div>

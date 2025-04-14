<?php if (!defined('ABSPATH')) exit; 

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
            src="<?php echo esc_url( CULQI_CONFIG_URL . '?platform=' . PLATFORM . '&shop=' . get_site_url() . '&token=' . urlencode($token) ); ?>" 
            width="100%">
        </iframe>
    </div>
</div>

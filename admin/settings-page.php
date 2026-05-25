<?php if (!defined('ABSPATH')) exit;

$culqi_config_url = culqi_get_config_url();
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
            src="<?php echo esc_url($culqi_config_url); ?>"
            width="100%">
        </iframe>
    </div>
</div>

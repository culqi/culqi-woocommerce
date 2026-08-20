<?php
/**
 * Modal iframe de Culqi — compatible con Classic y Block Checkout.
 *
 * El hook woocommerce_before_checkout_form solo se dispara en el checkout
 * clásico (shortcode). Para el Block Checkout se necesita un hook diferente.
 *
 * @package Culqi
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Markup del modal. Reutilizado por ambos hooks.
 * El iframe arranca con src="#" y gateway.js / culqi-block.js asignan la URL real.
 */
function culqi_custom_order_created_modal() {
    static $rendered = false;
    if ($rendered) { return; }
    $rendered = true;
    ?>
    <div id="order-created-modal" style="display:none;">
        <div class="modal-content">
            <iframe allowtransparency="true" style="background: transparent" src="#"></iframe>
        </div>
    </div>
    <?php
}

add_action('woocommerce_before_checkout_form', 'culqi_custom_order_created_modal');

// Block Checkout
// render_block_core_post_content se dispara al renderizar cualquier bloque en el contenido del post.
add_action('wp_footer', 'culqi_inject_modal_for_blocks');
function culqi_inject_modal_for_blocks() {
    // Solo en páginas de checkout
    if ( ! is_checkout() ) {
        return;
    }
    if ( has_shortcode( get_post()->post_content ?? '', 'woocommerce_checkout' ) ) {
        return;
    }
    culqi_custom_order_created_modal();
}

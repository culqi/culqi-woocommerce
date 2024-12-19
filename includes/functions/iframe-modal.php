<?php

add_action('woocommerce_before_checkout_form', 'custom_order_created_modal');
function custom_order_created_modal() {
    ?>
    <div id="order-created-modal" style="display:none;">
        <div class="modal-content">
            <iframe allowtransparency="true" style="background: transparent" src="#"></iframe>
        </div>
    </div>
    <?php
}

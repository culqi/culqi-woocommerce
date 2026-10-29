/**
 * Culqi — gateway.js
 *
 * Maneja el flujo de pago del checkout CLÁSICO (shortcode).
 * También contiene el listener de postMessage del iframe de Culqi,
 * que funciona para AMBOS checkouts (clásico y block) porque este
 * script se carga en todas las páginas de checkout via gateway-scripts.php.
 *
 * NO usar jQuery para el block checkout — ese flujo lo maneja culqi-block.js.
 *
 * @package Culqi
 */

/**
 * Crea el modal #order-created-modal dinámicamente si no existe en el DOM.
 * Acts as fallback para themes (ej: Woodmart/WPBakery) que no ejecutan
 * woocommerce_before_checkout_form, evitando que el modal no esté presente.
 *
 * Es idempotente: si el PHP ya lo insertó via hook, no duplica.
 *
 * @param {string} iframeSrc URL inicial del iframe (default "#")
 */
window.culqiEnsureModal = function culqiEnsureModal(iframeSrc) {
    if (document.getElementById('order-created-modal')) {
        return;
    }
    var modal = document.createElement('div');
    modal.id = 'order-created-modal';
    modal.style.cssText = [
        'position:fixed',
        'top:0',
        'left:0',
        'width:100%',
        'height:100%',
        'background:rgba(0,0,0,0.6)',
        'display:flex',
        'justify-content:center',
        'align-items:center',
        'z-index:999999'
    ].join(';');
    modal.innerHTML = '<div class="modal-content" style="width:90%;height:90%;background:#fff;border-radius:8px;overflow:hidden;">'
        + '<iframe allowtransparency="true" style="background:transparent;width:100%;height:100%;border:none;border-radius:8px;" src="'
        + (iframeSrc || '#')
        + '"></iframe></div>';
    document.body.appendChild(modal);
}

jQuery(function ($) {
    $('form.checkout').on('checkout_place_order', function (e) {
        const paymentGateway = jQuery('input[name="payment_method"]:checked').val();

        if (paymentGateway !== 'culqi') {
            return true; // dejar que otros gateways funcionen normal
        }

        $('.woocommerce-loader').addClass('flex');
        jQuery('#place_order').attr('disabled', true);
        e.preventDefault();

        $.ajax({
            type:    'POST',
            url:     wc_checkout_params.checkout_url,
            data:    $('form.checkout').serialize(),
            success: function (response) {
                if (response.result === 'success') {
                    if (response.show_modal) {
                        // Fallback: crear modal dinámicamente si no existe (theme conflict)
                        culqiEnsureModal('#');
                        $('#order-created-modal iframe').attr('src', response.redirect);
                        $('#order-created-modal').fadeIn();
                        $('body').addClass('no-scroll');
                    } else {
                        window.location.href = response.redirect;
                    }
                    jQuery('#place_order').attr('disabled', false);
                } else {
                    if (response.messages) {
                        showWooCommerceError(response.messages);
                    } else {
                        showWooCommerceError('Order creation failed. Please try again.');
                    }
                    $('.woocommerce-loader').fadeOut();
                    $('.woocommerce-loader').removeClass('flex');
                    jQuery('#place_order').attr('disabled', false);
                }
            },
            error: function (err) {
                showWooCommerceError('Error while creating order. Please try again.');
                console.log(err);
                $('.woocommerce-loader').fadeOut();
                $('.woocommerce-loader').removeClass('flex');
                jQuery('#place_order').attr('disabled', false);
            },
        });

        return false;
    });
});

// Habilitar botón cuando el checkout clásico termina de cargar
jQuery(function ($) {
    $(document.body).on('updated_checkout', function () {
        const checkoutIsReady = function () {
            return (
                $('#place_order').length &&
                $('.woocommerce-checkout').length &&
                typeof wc_checkout_params !== 'undefined'
            );
        };

        const maxAttempts = 10;
        let attempts     = 0;

        const checkReadyState = setInterval(function () {
            attempts++;

            if (checkoutIsReady()) {
                clearInterval(checkReadyState);
                enableButton();
            } else if (attempts >= maxAttempts) {
                clearInterval(checkReadyState);
                console.warn('Checkout elements not fully loaded after attempts');
                $('#place_order').prop('disabled', false);
            }
        }, 500);
    });
});

function enableButton() {
    jQuery('#place_order').prop('disabled', false);
}

function culqiIsBlockCheckout() {
    return !!(
        document.querySelector('.wc-block-checkout') ||
        document.querySelector('.wc-block-checkout__actions_row')
    );
}

function culqiResetBlockCheckout() {
    const wpData = window.wp && window.wp.data;

    if (!wpData || typeof wpData.dispatch !== 'function') {
        return false;
    }

    const blocksData = window.wc && window.wc.wcBlocksData;
    const storeKey =
        (blocksData && (blocksData.checkoutStore || blocksData.CHECKOUT_STORE_KEY)) ||
        'wc/store/checkout';

    try {
        const store = wpData.dispatch(storeKey);

        if (!store) {
            return false;
        }

        const setIdle =
            typeof store.__internalSetIdle === 'function'
                ? store.__internalSetIdle
                : store.setIdle;

        if (typeof setIdle !== 'function') {
            return false;
        }

        setIdle.call(store);
        return true;
    } catch (error) {
        console.warn('[Culqi] No se pudo reiniciar el checkout Block:', error);
        return false;
    }
}

// postMessage listener — compartido por Classic y Block Checkout
//
// El iframe de Culqi envía mensajes via postMessage para comunicar eventos:
//   - appCulqiStoreLoaded: el iframe cargó, ocultar el loader
//   - redirectUrl:         URL a la que redirigir después del pago
//   - operationType=processing: el pago está en procesamiento, redirigir
//   - action=closeModal:   el usuario cerró el modal de Culqi
//
// Este listener funciona en ambos checkouts porque gateway.js se carga en
// todas las páginas de checkout (wp_enqueue_scripts + is_checkout()).
window.addEventListener(
    'message',
    function (event) {
        if (event.data.object === 'appCulqiStoreLoaded') {
            jQuery('.woocommerce-loader').removeClass('flex');
        }

        if (event.data.redirectUrl) {
            window.redirectUrl = event.data.redirectUrl;
        }

        if (event.data.operationType === 'processing') {
            if (window.redirectUrl) {
                customRedirect();
            }
        }

        if (event.data.action === 'closeModal') {
            const modal = document.getElementById('order-created-modal');
            if (modal) {
                jQuery(modal).fadeOut();
            }
            jQuery('body').removeClass('no-scroll');
            jQuery('.woocommerce-loader').removeClass('flex');
            if (window.redirectUrl) {
                customRedirect();
                return;
            }
            if (culqiIsBlockCheckout() && !culqiResetBlockCheckout()) {
                window.location.reload();
            }
        }
    },
    false
);

function customRedirect() {
    const redirectUrl = window.redirectUrl;
    delete window.redirectUrl;
    window.location.href = redirectUrl;
}

function showWooCommerceError(htmlContent) {
    jQuery('.wc-block-components-notice-banner').remove();
    jQuery('#culqi-checkout-error').remove();

    const errorContainer = jQuery('<div id="culqi-checkout-error"></div>');
    errorContainer.html(htmlContent);

    // Funciona en classic checkout. En block checkout los errores
    // los maneja el propio block via emitResponse.responseTypes.ERROR.
    jQuery('form.checkout').prepend(errorContainer);

    jQuery('html, body').animate(
        { scrollTop: errorContainer.offset().top - 100 },
        300
    );

    setTimeout(function () {
        errorContainer.fadeOut(300, function () {
            jQuery(this).remove();
        });
    }, 10000);
}

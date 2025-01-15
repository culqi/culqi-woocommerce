jQuery(function($) {
    $('form.checkout').on('checkout_place_order', function(e) {
        const paymentGateway = jQuery('input[name="payment_method"]:checked').val();
        if(paymentGateway === 'culqi') {
            $('.woocommerce-loader').addClass('flex');
            jQuery('#place_order').attr('disabled', true);
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: wc_checkout_params.checkout_url,
                data: $('form.checkout').serialize(),
                success: function(response) {
                    if (response.result === 'success') {
                        if(response.show_modal) {
                            $('#order-created-modal').fadeIn();
                            $('#order-created-modal iframe').attr('src', response.redirect);
                            $('body').addClass('no-scroll');
                            // $('.woocommerce-loader').removeClass('flex');
                        } else {
                            window.location.href = response.redirect;
                        }
                        jQuery('#place_order').attr('disabled', false);
                    } else {
                        alert('Order creation failed. Please try again.');
                        $('.woocommerce-loader').fadeOut();
                        $('.woocommerce-loader').removeClass('flex');
                        jQuery('#place_order').attr('disabled', false);
                    }
                },
                error: function(err) {
                    alert('Error while creating order. Please try again.');
                    console.log(err);
                    $('.woocommerce-loader').fadeOut();
                    $('.woocommerce-loader').removeClass('flex');
                    jQuery('#place_order').attr('disabled', false);
                }
            });
    
            return false;
        }
    });

    window.addEventListener('message', function(event) {
        console.log(event.data);
        if (event.data.object == "appCulqiStoreLoaded") {
            $('.woocommerce-loader').removeClass('flex');
        }
        if (event.data.redirectUrl) {
            window.redirectUrl = event.data.redirectUrl;
        }
        if (event.data.action === 'closeModal') {
            $('#order-created-modal').fadeOut();
            $('body').removeClass('no-scroll');
            $('.woocommerce-loader').removeClass('flex');
            if (window.redirectUrl) {
                const redirectUrl = window.redirectUrl;
                delete window.redirectUrl;
                window.location.href = redirectUrl;
            }
        }
    }, false);
});
document.addEventListener('DOMContentLoaded', function () {
    // Check if WooCommerce Blocks is initialized
    if (typeof wp !== 'undefined' && wp.data && wp.data.dispatch) {
        // Check if wc/store is available in wp.data
        const store = wp.data.dispatch('wc/store');

        if (store) {
            console.log('WooCommerce Blocks is initialized and wc/store is available.');
        } else {
            console.error('WooCommerce Blocks is not initialized or wc/store is not available.');
        }
    } else {
        console.error('WooCommerce Blocks or wp.data is not loaded.');
    }
});

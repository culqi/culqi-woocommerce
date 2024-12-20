jQuery(function($) {
    $('form.checkout').on('checkout_place_order', function(e){
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
                    } else {
                        window.location.href = response.redirect;
                    }
                } else {
                    alert('Order creation failed. Please try again.');
                }
            },
            error: function(err) {
                alert('Error while creating order. Please try again.');
            }
        });

        return false;
    });

    window.addEventListener('message', function(event) {
        if (event.data.action === 'closeModal') {
            $('#order-created-modal').fadeOut();
            $('body').removeClass('no-scroll');
        }

        if (event.data.redirectUrl) {
            window.location.href = event.data.redirectUrl;
        }
    }, false);

    //


        
});
document.addEventListener('DOMContentLoaded', function () {
    console.log(2345);
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

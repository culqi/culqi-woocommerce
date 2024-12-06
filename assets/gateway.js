jQuery(function($) {
    $('form.checkout').on('checkout_place_order', function(e){
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: wc_checkout_params.checkout_url,
            data: $('form.checkout').serialize(),
            success: function(response) {
                if (response.result === 'success') {
                    console.log(response);
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
        // Ensure the message is coming from a trusted iframe origin
        /*if (event.origin !== 'https://your-iframe-origin.com') {
            return;
        }*/
    
        if (event.data.action === 'closeModal') {
            $('#order-created-modal').fadeOut();
            $('body').removeClass('no-scroll');
        }

        if (event.data.redirectUrl) {
            window.location.href = event.data.redirectUrl;
        }
    }, false);
});

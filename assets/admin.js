jQuery(document).ready(function($) {
    jQuery('tr[data-gateway_id="culqi"] .wc-payment-gateway-method-toggle-enabled').click(function(e) {
        e.preventDefault();
        var isEnabled = 'no';
        const toggleClassList = $(this).find('.woocommerce-input-toggle').attr('class').split(/\s+/);
        $.each(toggleClassList, function(index, item) {
            if (item === 'woocommerce-input-toggle--disabled') {
                console.log(item);
                isEnabled = 'yes';
            }
        });
        setTimeout(function() {
            $.ajax({
                url: culqiGatewayAjax.ajax_url,
                type: 'POST',
                data: {
                    action: 'culqi_gateway_toggle',
                    enabled: isEnabled,
                    nonce: culqiGatewayAjax.nonce,
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + error);
                }
            });
        }, 500)
       
    });
    //
    window.addEventListener('message', function(event) {
        console.log(event);
        // Ensure the message is coming from a trusted iframe origin
        /*if (event.origin !== 'https://your-iframe-origin.com') {
            return;
        }*/
    
        if (event.data.action === 'saveConfig') {
            const data = event.data.data;

            $.ajax({
                url: culqiGatewayAjax.ajax_url,
                type: 'POST',
                data: {
                    action: 'culqi_save_config',
                    pluginStatus: data.pluginStatus,
                    publicKey: data.publicKey,
                    merchant: data.merchant,
                    rsa_pk: data.rsaPkPlugin,
                    payment_methods: data.paymentMethods,
                    nonce: culqiGatewayAjax.nonce,
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + error);
                }
            });
        }
    }, false);
});


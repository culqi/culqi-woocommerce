jQuery(document).ready(function($) {
    jQuery('tr[data-gateway_id="culqi"] .wc-payment-gateway-method-toggle-enabled').click(function(e) {
        e.preventDefault();
        var isEnabled = 'no';
        const toggleClassList = $(this).find('.woocommerce-input-toggle').attr('class').split(/\s+/);
        $.each(toggleClassList, function(index, item) {
            if (item === 'woocommerce-input-toggle--disabled') {
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
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + error);
                }
            });
        }, 500)
       
    });
    //
    window.addEventListener('message', function(event) {
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
                    rsa_pk_culqi: data.rsaPkCulqi,
                    rsa_sk_plugin: data.rsaSkPlugin,
                    payment_methods: data.paymentMethods,
                    nonce: culqiGatewayAjax.nonce,
                },
                success: function(response) {
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + error);
                }
            });
        }
        //if (event.origin !== 'http://localhost:5173') return;
        if (event.data.action === 'reload') {
            location.reload();
        }
    }, false);
});


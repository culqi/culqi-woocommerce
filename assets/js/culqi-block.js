/**
 * Culqi — integración con el WooCommerce Block Checkout.
 *
 * @package Culqi
 */

(function () {
    'use strict';

    const registerPaymentMethod = window.wc.wcBlocksRegistry.registerPaymentMethod;
    const getSetting            = window.wc.wcSettings.getSetting;
    const createElement         = window.wp.element.createElement;
    const useEffect             = window.wp.element.useEffect;
    const decodeEntities        = window.wp.htmlEntities.decodeEntities;

    const settings    = getSetting('culqi_data', {});
    const label       = decodeEntities(settings.title || 'Culqi');
    const description = decodeEntities(settings.description || '');
    const icons       = settings.icons || [];
    const logoUrl     = settings.logo_url || '';

    function abrirModalCulqi(url) {
        const modal  = document.getElementById('order-created-modal');
        const iframe = modal && modal.querySelector('iframe');

        if (!modal || !iframe) {
            console.error('[Culqi Block] Modal #order-created-modal no encontrado. Redirigiendo...');
            window.location.href = url;
            return;
        }

        iframe.src = url;
        modal.style.display = 'flex';
        document.body.classList.add('no-scroll');
    }

    function CulqiLabel(props) {
        const PaymentMethodLabel = props.components.PaymentMethodLabel;

        return createElement(
            'span',
            {
                style: {
                    display:        'flex',
                    alignItems:     'center',
                    justifyContent: 'space-between',
                    width:          '100%',
                },
            },
            createElement(
                'span',
                { style: { display: 'flex', alignItems: 'center', gap: '6px' } },
                logoUrl
                    ? createElement('img', {
                        src:   logoUrl,
                        alt:   'Culqi',
                        style: { height: '22px', verticalAlign: 'middle' },
                      })
                    : null,
                createElement(PaymentMethodLabel, { text: label })
            ),
            icons.length > 0
                ? createElement(
                    'span',
                    { style: { display: 'flex', alignItems: 'center', gap: '4px' } },
                    icons.map(function (icon) {
                        return createElement('img', {
                            key:   icon.id,
                            src:   icon.src,
                            alt:   icon.alt,
                            style: { height: '22px' },
                        });
                    })
                  )
                : null
        );
    }

    function CulqiContent(props) {
        const eventRegistration           = props.eventRegistration;
        const emitResponse                = props.emitResponse;
        const onPaymentSetup              = eventRegistration.onPaymentSetup;
        const onAfterProcessingWithSuccess = eventRegistration.onCheckoutAfterProcessingWithSuccess;

        useEffect(function () {
            const unsubscribe = onPaymentSetup(function () {
                return {
                    type: emitResponse.responseTypes.SUCCESS,
                    meta: {
                        paymentMethodData: {
                            culqi_checkout_type: 'block',
                        },
                    },
                };
            });

            return unsubscribe;
        }, [onPaymentSetup, emitResponse.responseTypes.SUCCESS]);

        useEffect(function () {
            const unsubscribe = onAfterProcessingWithSuccess(function (checkoutResponse) {
                const redirectUrl = checkoutResponse && checkoutResponse.redirectUrl;
                const orderId     = checkoutResponse && checkoutResponse.orderId;

                // Fallback: si hay redirectUrl (puede llegar del Store API)
                if (redirectUrl && redirectUrl !== window.location.href) {
                    try {
                        const parsed = new URL(redirectUrl);
                        if (
                            parsed.hostname.indexOf('culqi.com') !== -1 ||
                            parsed.hostname.indexOf('nonprodculqi.com') !== -1
                        ) {
                            abrirModalCulqi(redirectUrl);
                            return { type: emitResponse.responseTypes.SUCCESS };
                        }
                    } catch (e) {
                        console.warn('[Culqi Block] redirect_url inválida:', redirectUrl);
                    }
                }

                // Flujo principal: sin redirect, obtener URL via REST
                if (orderId) {
                    const url = settings.ajax_url + '?order_id=' + orderId;
                    fetch(url)
                        .then(function (r) { return r.json(); })
                        .then(function (data) {
                            if (data && data.url) {
                                abrirModalCulqi(data.url);
                            } else {
                                console.error('[Culqi Block] No se obtuvo URL de pago');
                            }
                        })
                        .catch(function (err) {
                            console.error('[Culqi Block] Error al obtener URL de pago:', err);
                        });
                }

                return { type: emitResponse.responseTypes.SUCCESS };
            });

            return unsubscribe;
        }, [onAfterProcessingWithSuccess, emitResponse.responseTypes.SUCCESS]);

        return createElement(
            'div',
            { className: 'wc-culqi-block-content' },
            description
                ? createElement('p', { style: { margin: '8px 0 0', fontSize: '14px' } }, description)
                : null
        );
    }

    // Componente: Edit (preview en el editor de Gutenberg)

    function CulqiEdit() {
        return createElement(
            'div',
            { style: { padding: '8px', fontSize: '13px', color: '#777' } },
            description || 'Culqi — tarjetas, Yape, PagoEfectivo'
        );
    }

    // Registro del método de pago

    registerPaymentMethod({
        name:    'culqi',

        label:   createElement(CulqiLabel, null),

        content: createElement(CulqiContent, null),

        edit:    createElement(CulqiEdit, null),

        ariaLabel: label,

        canMakePayment: function () {
            return true;
        },

        supports: {
            features: settings.supports || ['products'],
        },
    });

})();

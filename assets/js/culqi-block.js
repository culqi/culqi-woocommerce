
const { registerPaymentMethod } = window.wc.wcBlocksRegistry;

    const options = {
        name: 'culqi',
        title: 'Culqi',
        description: 'A setence or two about your payment method',
        gatewayId: 'culqi',
        label: 'Culqi',
        ariaLabel: 'Culqi',
        content: wp.element.createElement('div', null, 'Paga con Culqi'),
        edit: wp.element.createElement('div', null, 'Edit your payment method here'),
        canMakePayment: () => {
            return true;

        },
        paymentMethodId: 'culqi',
        supports: {
            features: ['default', 'products', 'subscriptions'],
            style: [],
        },
        method_title: 'Culqi',
        method_description: 'A sentence or two about your payment method',
    };
    registerPaymentMethod(options);     
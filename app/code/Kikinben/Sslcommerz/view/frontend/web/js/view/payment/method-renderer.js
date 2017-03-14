define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'testpayment',
                component: 'Kikinben_Sslcommerz/js/view/payment/method-renderer/sslcommerz'
            }
        );
        return Component.extend({});
    }
);
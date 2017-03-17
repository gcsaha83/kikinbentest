
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
                type: 'sslcommerz',
                component: 'Kikinben_Sslcommerz/js/view/payment/method-renderer/sslcommerz-method'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);

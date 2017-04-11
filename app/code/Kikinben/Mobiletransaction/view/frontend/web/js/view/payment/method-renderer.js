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
                type: 'mobiletransaction',
                component: 'Kikinben_Mobiletransaction/js/view/payment/method-renderer/mobiletransaction'
            }
        );
        return Component.extend({});
    }
);
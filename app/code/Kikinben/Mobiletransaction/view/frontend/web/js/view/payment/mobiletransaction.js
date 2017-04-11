
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
                component: 'Kikinben_Mobiletransaction/js/view/payment/method-renderer/mobiletransaction-method'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);

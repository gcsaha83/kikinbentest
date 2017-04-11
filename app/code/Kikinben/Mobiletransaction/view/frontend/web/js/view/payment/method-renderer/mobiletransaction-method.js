define(
    [
            'Magento_Checkout/js/view/payment/default',
            'mage/url'
        ],
    function (
        Component,
        url
    ) {
            'use strict';

            return Component.extend(
                {
                    defaults: {
                        template: 'Kikinben_Mobiletransaction/payment/form',
                    },
                    
                }
            );
    }
);



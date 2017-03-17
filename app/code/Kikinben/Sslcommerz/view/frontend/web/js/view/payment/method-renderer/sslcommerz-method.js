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
                        template: 'Kikinben_Sslcommerz/payment/form',
                    },
                    redirectAfterPlaceOrder: false,
                
                    getCode: function () {
                        return 'sslcommerz';
                    },
                
                    getData: function () {
                        return {
                            'method': this.item.method,
                            'additional_data': {
                                'transaction_result': "1"
                            }
                        };
                    },
                
                    afterPlaceOrder: function () {
                        window.location.replace(url.build('sslcommerz/index/index'));
                    }
                }
            );
    }
);



/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'Magento_Checkout/js/view/payment/default'
    ],
    function (Component) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Kikinben_Mobiletransaction/payment/form',
                transactionResult: '',
		mobileNumber:'',
		transactionId:''
            },

            initObservable: function () {

                this._super()
                    .observe([
                        'transactionResult',
			'mobileNumber',
			'transactionId'
                    ]);
                return this;
            },

            getCode: function() {
                return 'sample_gateway';
            },

            getData: function() {
                return {
                    'method': this.item.method,
                    'additional_data': {
                        'transaction_result': this.transactionResult(),
			'mobile_number':this.mobileNumber(),
			'transaction_id':this.transactionId()
                    }
                };
            },

            getTransactionResults: function() {
                return _.map(window.checkoutConfig.payment.sample_gateway.transactionResults, function(value, key) {
                    return {
                        'value': key,
                        'transaction_result': value
                    }
                });
            },
	   getMobileNumbers:function() {
		return _.map(window.checkoutConfig.payment.sample_gateway.mobileNumbers, function(value, key) {
                    return {
                        'value': key,
                        'mobile_number': value
                    }
                });

	  },
	 getTransactionIds:function() {
		return _.map(window.checkoutConfig.payment.sample_gateway.transactionIds, function(value, key) {
                    return {
                        'value': key,
                        'transaction_id': value
                    }
                });

	  },
        });
    }
);

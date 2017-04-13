/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'Magento_Checkout/js/view/payment/default','ko'
    ],
    function (Component,ko) {
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
            selectRow: function() {
            	/*this.transaction_result = ko.observable();
            	this.transaction_result.subscribe(function(latest) {
            	    alert("Input changed");
            	  }, this);*/
            	//alert(this.transaction_result);
            	
                
            },
            placeOrder:function(key){
            	var self = this;
            	//alert(key);
            	//if (key) {
                    //return self._super();
                //}
            	//alert(this.transactionResult());
            	//alert(this.mobileNumber());
            	//alert(this.transactionId());
            	//return self.placeOrder('parent');
            	
            	var transcationResult = this.transactionResult();
            	var mobileNumber = this.mobileNumber();
            	var transcationId = this.transactionId();
            	var filter = /^\d*(?:\.\d{1,2})?$/;
            	if(transcationResult == 'Bkash'){
            		//10 digits validation
            		if(filter.test(mobileNumber)){
            			if(mobileNumber.length==11 && transcationId ){
            				return self._super();
            			}else{
                			alert("Please enter valid mobile number and transaction Id");
            			}
            		}else{
            			alert("Please enter valid mobile number and transaction Id");
            		}
            	}else{
            		if(filter.test(mobileNumber)){
            			if(mobileNumber.length==12 && transcationId ){
            				return self._super();
            			}
            			else{
                			alert("Please enter valid mobile number and transaction Id");
            			}
            		}else{
            			alert("Please enter valid mobile number and transaction Id");
            		}    		
            	}
            	
            }
        });
    }
);

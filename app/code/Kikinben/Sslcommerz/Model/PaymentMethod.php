<?php
 
namespace Kikinben\Sslcommerz\Model;
 
/**
 * Pay In Store payment method model
 */
class PaymentMethod extends \Magento\Payment\Model\Method\AbstractMethod
{
 
    /**
     * Payment code
     *
     * @var string
     */    
    const METHOD_CODE                       = 'sslcommerz';    
    protected $_code                    	= self::METHOD_CODE;
}
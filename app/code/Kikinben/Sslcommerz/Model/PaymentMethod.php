<?php
 
namespace Kikinben\Sslcommerz\Model;
 

class PaymentMethod extends \Magento\Payment\Model\Method\AbstractMethod
{
 
    const METHOD_CODE                       = 'sslcommerz';    
    protected $_code                    	= self::METHOD_CODE;
}

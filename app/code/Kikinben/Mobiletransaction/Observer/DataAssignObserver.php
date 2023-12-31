<?php

namespace Kikinben\Mobiletransaction\Observer;

use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;

class DataAssignObserver extends AbstractDataAssignObserver
{    	
    const TRANSACTION_RESULT = 'transaction_result';
    const TRANSACTION_ID = 'transaction_id';
    const MOBILE_NUMBER = 'mobile_number';

    protected $additionalInformationList = [
        self::TRANSACTION_RESULT,
        self::TRANSACTION_ID,
        self::MOBILE_NUMBER
    ];
    public function execute(Observer $observer){

	$data = $this->readDataArgument($observer);
	$additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);

        if (!is_array($additionalData)) {		
            return;
        }
        $paymentInfo = $this->readPaymentModelArgument($observer);
        foreach ($this->additionalInformationList as $additionalInformationKey) {
            if (isset($additionalData[$additionalInformationKey])) {
                $paymentInfo->setAdditionalInformation(
                    $additionalInformationKey,
                    $additionalData[$additionalInformationKey]
                );
            }
        }

       
   }
}


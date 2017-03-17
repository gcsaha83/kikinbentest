<?php

namespace Kikinben\Sslcommerz\Controller\Index;

use Magento\Framework\Controller\ResultFactory;

class Cancel extends \Kikinben\Sslcommerz\Controller\Index
{

    public function execute()
    {
        /*$this->getHelper()->log('Cancel: Transacción denegada desde Redsys');
        $result_redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $result_redirect->setUrl($this->getStoreManager()->getStore()->getBaseUrl() . 'checkout/cart');
        return $result_redirect;*/
    }
}

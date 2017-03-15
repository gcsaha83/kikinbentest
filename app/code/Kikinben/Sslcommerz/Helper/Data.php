<?php

namespace Kikinben\Sslcommerz\Helper;

use Symfony\Component\Console\Output\OutputInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    const TYPE_INFO_MSJ = 0;
    const TYPE_ERROR_MSJ = 1;

    private $order_collection_factory = null;
    
    private $logger = null;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Kikinben\Sslcommerz\Logger\Logger $logger,
        \Magento\Framework\App\State $state
    ) {
        $this->order_collection_factory = $orderCollectionFactory;
        $state->setAreaCode('adminhtml');
        parent::__construct($context);
        $this->logger = $logger;
    }

    /*
    public function log(
        $message,
        $type = 0
    ) {
        if ($this->isLogActive()) {
            $this->logger->makeLog($message, $type);
        }
    }

    public function isActive()
    {
        $store_scope = \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITES;
        return $this->scopeConfig->getValue(self::XML_PATH_AO_ACTIVE, $store_scope);
    }

    public function isLogActive()
    {
        return $this->getConfigData('logactivo');
    }

    public function getConfigData(
        $field,
        $storeId = null
    ) {
    
        $path = 'payment/sslcommerz/' . $field;
        return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }*/
}

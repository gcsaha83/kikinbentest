<?php

/**
 * Copyright © 2016 iPragmatech solutions. All rights reserved.
 * @author: Ajay Mehta (iPragmatech solutions )
 **/
namespace Ipragmatech\Registration\Controller\Index;

use Magento\Framework\Controller\ResultFactory;

class Create extends \Magento\Framework\App\Action\Action
{

    /**
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    protected $_customerSession;

    protected $_storeManager;

    /**
     *
     * @param Action\Context $context
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Cache\StateInterface $cacheState
     * @param \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_customerSession = $customerSession;
        $this->_storeManager = $storeManager;
    }

    /**
     * Flush cache storage
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory
            ->create(ResultFactory::TYPE_REDIRECT);
        $mobileNumber = $this->_customerSession->getMobileNumber();
        $params = $this->getRequest()->getParams();
        if ($mobileNumber != $params['mobile']) {
            $resultRedirect->setUrl($this->_storeManager
                    ->getStore()
                    ->getBaseUrl() . 'registration/index/create/?mobile='
                . $mobileNumber);
        }
        $this->resultPage = $this->_resultPageFactory->create();
        return $this->resultPage;
    }
}

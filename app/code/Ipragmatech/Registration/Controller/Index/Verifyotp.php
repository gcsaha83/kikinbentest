<?php

/**
 * Copyright © 2016 iPragmatech solutions. All rights reserved.
 * @author: Ajay Mehta (iPragmatech solutions )
 **/
namespace Ipragmatech\Registration\Controller\Index;

use Magento\Framework\Controller\ResultFactory;

class Verifyotp extends \Magento\Framework\App\Action\Action
{

    /**
     *
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     *
     * @var \Magento\Framework\App\Cache\StateInterface
     */
    protected $_cacheState;

    /**
     *
     * @var \Magento\Framework\App\Cache\Frontend\Pool
     */
    protected $_cacheFrontendPool;

    /**
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    protected $_storeManager;

    protected $_customerSession;

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
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession
    ) {
        parent::__construct($context);
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_storeManager = $storeManager;
        $this->_customerSession = $customerSession;
    }

    /**
     * Flush cache storage
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory
        ::TYPE_REDIRECT);
        
        $params = $this->getRequest()->getParams();
        $otpNumber = $params['otpnumber'];
        $mobileNumber = $this->_customerSession->getMobileNumber();
        

        if (empty($mobileNumber) || !isset($mobileNumber) ||
            $mobileNumber == "") {
            $resultRedirect->setUrl($this->_storeManager->getStore()
                    ->getBaseUrl() . 'customer/account/create/');
        } else {
            $otpModel = $this->_objectManager
                ->create('Ipragmatech\Registration\Model\Otp')
                ->getCollection()
                ->addFieldToFilter('mobile_number', [
                    'like' => '%' . $mobileNumber . '%'
                ]);

            if (count($otpModel)) {
                foreach ($otpModel as $otpData) {
                    $otp = $otpData->getOtp();
                }
                if ($otp == $otpNumber) {
                   
                    $data = [
                        'mobile' => $mobileNumber
                    ];
                    $queryParam = http_build_query($data);
                    $resultRedirect->setUrl($this->_storeManager->getStore()
                            ->getBaseUrl() . 'registration/index/create?' .
                        $queryParam);
                } else {
                    
                    $this->messageManager->addError('OTP is not valid');
                    $resultRedirect->setUrl($this->_storeManager->getStore()
                            ->getBaseUrl() . 'registration/index/index');
                }
            } else {
                $this->messageManager
                    ->addError('No mobile number exist in the system');
                $resultRedirect->setUrl($this->_storeManager->getStore()
                        ->getBaseUrl() . 'customer/account/create/');
            }
        }
        return $resultRedirect;
    }
}

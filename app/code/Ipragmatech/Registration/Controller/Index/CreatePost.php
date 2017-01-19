<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ipragmatech\Registration\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Helper\Address;
use Magento\Framework\UrlFactory;
use Magento\Customer\Model\Metadata\FormFactory;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Customer\Api\Data\RegionInterfaceFactory;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Customer\Model\Registration;
use Magento\Framework\Escaper;
use Magento\Customer\Model\CustomerExtractor;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\InputException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CreatePost extends \Magento\Customer\Controller\AbstractAccount
{

    /** @var AccountManagementInterface */
    protected $_accountManagement;

    /** @var Address */
    protected $_addressHelper;

    /** @var FormFactory */
    protected $_formFactory;

    /** @var SubscriberFactory */
    protected $_subscriberFactory;

    /** @var RegionInterfaceFactory */
    protected $_regionDataFactory;

    /** @var AddressInterfaceFactory */
    protected $_addressDataFactory;

    /** @var Registration */
    protected $_registration;

    /** @var CustomerInterfaceFactory */
    protected $_customerDataFactory;

    /** @var CustomerUrl */
    protected $_customerUrl;

    /** @var Escaper */
    protected $_escaper;

    /** @var CustomerExtractor */
    protected $_customerExtractor;

    /** @var \Magento\Framework\UrlInterface */
    protected $_urlModel;

    /** @var DataObjectHelper */
    protected $_dataObjectHelper;

    /**
     *
     * @var Session
     */
    protected $_session;
    protected $_customerSession;
    protected $_storeManager;
    /**
     *
     * @var AccountRedirect
     */
    private $_accountRedirect;

    /**
     *
     * @param Context $context
     * @param Session $customerSession
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param AccountManagementInterface $accountManagement
     * @param Address $addressHelper
     * @param UrlFactory $urlFactory
     * @param FormFactory $formFactory
     * @param SubscriberFactory $subscriberFactory
     * @param RegionInterfaceFactory $regionDataFactory
     * @param AddressInterfaceFactory $addressDataFactory
     * @param CustomerInterfaceFactory $customerDataFactory
     * @param CustomerUrl $customerUrl
     * @param Registration $registration
     * @param Escaper $escaper
     * @param CustomerExtractor $customerExtractor
     * @param DataObjectHelper $dataObjectHelper
     * @param AccountRedirect $accountRedirect
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        AccountManagementInterface $accountManagement,
        Address $addressHelper,
        UrlFactory $urlFactory,
        FormFactory $formFactory,
        SubscriberFactory $subscriberFactory,
        RegionInterfaceFactory $regionDataFactory,
        AddressInterfaceFactory $addressDataFactory,
        CustomerInterfaceFactory $customerDataFactory,
        CustomerUrl $customerUrl,
        Registration $registration,
        Escaper $escaper,
        CustomerExtractor $customerExtractor,
        DataObjectHelper $dataObjectHelper,
        AccountRedirect $accountRedirect,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_session = $customerSession;
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        $this->_accountManagement = $accountManagement;
        $this->_addressHelper = $addressHelper;
        $this->_formFactory = $formFactory;
        $this->_subscriberFactory = $subscriberFactory;
        $this->_regionDataFactory = $regionDataFactory;
        $this->_addressDataFactory = $addressDataFactory;
        $this->_customerDataFactory = $customerDataFactory;
        $this->_customerUrl = $customerUrl;
        $this->_registration = $registration;
        $this->_escaper = $escaper;
        $this->_customerExtractor = $customerExtractor;
        $this->_urlModel = $urlFactory->create();
        $this->_dataObjectHelper = $dataObjectHelper;
        $this->_accountRedirect = $accountRedirect;
        parent::__construct($context);
        $this->_customerSession = $customerSession;
        $this->_storeManager = $storeManager;
    }

    /**
     * Create customer account action
     *
     * @return void @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect
         * $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $mobileNumber = $this->_customerSession->getMobileNumber();
        $params = $this->getRequest()->getParams();
        $mobile = $params['mobilenumber'];
        if ($mobileNumber != $mobile) {
            $this->messageManager->addError('Mobile Number mismatch');
            $resultRedirect->setUrl($this->_storeManager->getStore()
                    ->getBaseUrl() . 'registration/index/create/?mobile=' .
                $mobileNumber);
            return $resultRedirect;
        }
        if ($this->_session->isLoggedIn() ||
            !$this->_registration->isAllowed()) {
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }

        if (!$this->getRequest()->isPost()) {
            $url = $this->_urlModel->getUrl('*/*/create', [
                '_secure' => true
            ]);
            $resultRedirect->setUrl($this->_redirect->error($url));
            return $resultRedirect;
        }

        $this->_session->regenerateId();

        try {
            $address = $this->extractAddress();
            $addresses = $address === null ? [] : [
                $address
            ];

            $customer = $this->_customerExtractor
                ->extract('customer_account_create',
                $this->_request);
            $customer->setAddresses($addresses);

            $password = $this->getRequest()->getParam('password');
            $confirmation = $this->getRequest()
                ->getParam('password_confirmation');
            $redirectUrl = $this->_session->getBeforeAuthUrl();

            $this->checkPasswordConfirmation($password, $confirmation);

            $customer = $this->_accountManagement->createAccount($customer,
                $password, $redirectUrl);

            if ($this->getRequest()->getParam('is_subscribed', false)) {
                $this->_subscriberFactory->create()
                    ->subscribeCustomerById($customer->getId());
            }

            $this->_eventManager->dispatch('customer_register_success', [
                'account_controller' => $this,
                'customer'           => $customer
            ]);

            $confirmationStatus = $this->_accountManagement
                ->getConfirmationStatus($customer->getId());
            if ($confirmationStatus === AccountManagementInterface
                ::ACCOUNT_CONFIRMATION_REQUIRED) {
                $email = $this->_customerUrl
                    ->getEmailConfirmationUrl($customer->getEmail());
                // @codingStandardsIgnoreStart
                $this->messageManager
                    ->addSuccess(__('You must confirm your account.
 Please check your email for the confirmation link or 
 <a href="%1">click here</a> for a new link.',
                    $email));
                // @codingStandardsIgnoreEnd
                $url = $this->_urlModel->getUrl('*/*/index', [
                    '_secure' => true
                ]);
                $resultRedirect->setUrl($this->_redirect->success($url));
            } else {
                $this->_session->setCustomerDataAsLoggedIn($customer);
                $this->messageManager->addSuccess($this->getSuccessMessage());
                $resultRedirect = $this->_accountRedirect->getRedirect();
            }
            return $resultRedirect;
        } catch (StateException $e) {
            $url = $this->_urlModel->getUrl('customer/account/forgotpassword');
            // @codingStandardsIgnoreStart
            $message = __('There is already an account with this email address.
 If you are sure that it is your email address, <a href="%1">click here</a> 
 to get your password and access your account.',
                $url);
            // @codingStandardsIgnoreEnd
            $this->messageManager->addError($message);
        } catch (InputException $e) {
            $this->messageManager
                ->addError($this->_escaper->escapeHtml($e->getMessage()));
            foreach ($e->getErrors() as $error) {
                $this->messageManager
                    ->addError($this->_escaper
                        ->escapeHtml($error->getMessage()));
            }
        } catch (\Exception $e) {
            $this->messageManager->addException($e,
                __('We can\'t save the customer.'));
        }

        $this->_session->setCustomerFormData($this->getRequest()
            ->getPostValue());
        $defaultUrl = $this->_storeManager->getStore()
                ->getBaseUrl() . 'registration/index/create?mobile=' .
            $mobileNumber;
        // $defaultUrl = $this->urlModel->getUrl('*/*/create', ['_secure' =>
        // true]);
        $resultRedirect->setUrl($this->_redirect->error($defaultUrl));
        return $resultRedirect;
    }

    /**
     * Add address to customer during create account
     *
     * @return AddressInterface|null
     */
    protected function extractAddress()
    {
        if (!$this->getRequest()->getPost('create_address')) {
            return null;
        }

        $addressForm = $this->_formFactory->create('customer_address',
            'customer_register_address');
        $allowedAttributes = $addressForm->getAllowedAttributes();

        $addressData = [];

        $regionDataObject = $this->_regionDataFactory->create();
        foreach ($allowedAttributes as $attribute) {
            $attributeCode = $attribute->getAttributeCode();
            $value = $this->getRequest()->getParam($attributeCode);
            if ($value === null) {
                continue;
            }
            switch ($attributeCode) {
                case 'region_id':
                    $regionDataObject->setRegionId($value);
                    break;
                case 'region':
                    $regionDataObject->setRegion($value);
                    break;
                default:
                    $addressData[$attributeCode] = $value;
            }
        }
        $addressDataObject = $this->_addressDataFactory->create();
        $this->_dataObjectHelper->populateWithArray($addressDataObject,
            $addressData, '\Magento\Customer\Api\Data\AddressInterface');
        $addressDataObject->setRegion($regionDataObject);

        $addressDataObject->setIsDefaultBilling($this->getRequest()
            ->getParam('default_billing', false))
            ->setIsDefaultShipping($this->getRequest()
                ->getParam('default_shipping', false));
        return $addressDataObject;
    }

    /**
     * Make sure that password and password confirmation matched
     *
     * @param string $password
     * @param string $confirmation
     * @return void
     * @throws InputException
     */
    protected function checkPasswordConfirmation($password, $confirmation)
    {
        if ($password != $confirmation) {
            throw new
            InputException(__('Please make sure your passwords match.'));
        }
    }

    /**
     * Retrieve success message
     *
     * @return string
     */
    protected function getSuccessMessage()
    {
        if ($this->_addressHelper->isVatValidationEnabled()) {
            if ($this->_addressHelper
                    ->getTaxCalculationAddressType() ==
                Address::TYPE_SHIPPING) {
                // @codingStandardsIgnoreStart
                $message = __('If you are a registered VAT customer, 
please <a href="%1">click here</a> to enter your shipping 
address for proper VAT calculation.',
                    $this->_urlModel->getUrl('customer/address/edit'));
                // @codingStandardsIgnoreEnd
            } else {
                // @codingStandardsIgnoreStart
                $message = __('If you are a registered VAT customer,
 please <a href="%1">click here</a> to enter your billing
  address for proper VAT calculation.',
                    $this->_urlModel->getUrl('customer/address/edit'));
                // @codingStandardsIgnoreEnd
            }
        } else {
            $message = __('Thank you for registering with %1.',
                $this->_storeManager->getStore()->getFrontendName());
        }
        return $message;
    }
}

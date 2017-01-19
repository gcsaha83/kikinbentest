<?php
/**
 * Copyright © 2015 Ipragmatech . All rights reserved.
 */
namespace Ipragmatech\Registration\Block;

use Magento\Framework\UrlFactory;
use Magento\Framework\App\Helper\AbstractHelper;
class BaseBlock extends \Magento\Framework\View\Element\Template
{

    /**
     *
     * @var \Ipragmatech\Registration\Helper\Data
     */
    protected $_devToolHelper;

    /**
     *
     * @var \Magento\Framework\Url
     */
    protected $_urlApp;

    /**
     *
     * @var \Ipragmatech\Registration\Model\Config
     */
    protected $_config;

    protected $_customerSession;
    protected $_ipAddress;
    /**
     *
     * @param \Ipragmatech\Registration\Block\Context $context
     * @param \Magento\Framework\UrlFactory $urlFactory
     */
    public function __construct(
        \Ipragmatech\Registration\Block\Context $context,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->_devToolHelper = $context->getRegistrationHelper();
        $this->_config = $context->getConfig();
        $this->_urlApp = $context->getUrlFactory()->create();
        parent::__construct($context);
        $this->_customerSession = $customerSession;
    }

    /**
     * Function for getting event details
     *
     * @return array
     */
    public function getEventDetails()
    {
        return $this->_devToolHelper->getEventDetails();
    }

    /**
     * Function for getting current url
     *
     * @return string
     */
    public function getCurrentUrl()
    {
        return $this->_urlApp->getCurrentUrl();
    }

    /**
     * Function for getting controller url for given router path
     *
     * @param string $routePath
     * @return string
     */
    public function getControllerUrl($routePath)
    {
        return $this->_urlApp->getUrl($routePath);
    }

    /**
     * Function canShowRegistration
     *
     * @return bool
     */
    public function canShowRegistration()
    {
        $isEnabled = $this->getConfigValue('registration/module/is_enabled');
        if ($isEnabled) {
            $allowedIps = $this
                ->getConfigValue('registration/module/allowed_ip');
            if ($allowedIps === null) {
                return true;
            } else {
                $remoteIp = $this->_ipAddress;
                if (strpos($allowedIps, $remoteIp) !== false) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Function for getting current url
     *
     * @param string $path
     * @return string
     */
    public function getConfigValue($path)
    {
        return $this->_config->getCurrentStoreConfigValue($path);
    }

    public function getCustomerSession()
    {
        // return $this->_customerSession;
        return "customersession ";
    }
}

<?php
namespace Kikinben\Otp\Block;

use Magento\Customer\Model\Url;
use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session;

class Forgotpassword extends \Magento\Framework\View\Element\Template
{
    /* 
     * @var Url
     */
    protected $customerUrl;

    /**
     * @param Template\Context $context
     * @param Url $customerUrl
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Url $customerUrl,
        Session $customerSession,
        array $data = []
    ) {
        $this->customerUrl = $customerUrl;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * Get login URL
     *
     * @return string
     */
    public function getLoginUrl()
    {
        return $this->customerUrl->getLoginUrl();
    }
    public function getCustomerMobile(){
        return $this->_customerSession->getMobilenumber();

    }
    public function unsetCustomerMobile(){
        $this->_customerSession->unsMobilenumber();
    }

}

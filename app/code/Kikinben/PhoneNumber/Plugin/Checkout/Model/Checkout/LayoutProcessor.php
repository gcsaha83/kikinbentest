<?php
namespace Kikinben\PhoneNumber\Plugin\Checkout\Model\Checkout;
class LayoutProcessor
{
    protected $_customerLogin;

    public function __construct(\Magento\Customer\Model\Session $customerLogin)
    {
        $this->_customerLogin = $customerLogin;
    }
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
    ) {
	if(!$this->_customerLogin->isLoggedIn()){
		$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
		['shippingAddress']['children']['shipping-address-fieldset']['children']['delivery_date'] = [
		    'component' => 'Magento_Ui/js/form/element/abstract',
		    'config' => [
		        'customScope' => 'shippingAddress',
		        'template' => 'ui/form/field',
		        'elementTmpl' => 'ui/form/element/input',
		        'options' => [],
		        'id' => 'delivery-date'
		    ],
		    'dataScope' => 'shippingAddress.delivery_date',
		    'label' => 'Mobile Number',
		    'provider' => 'checkoutProvider',
		    'visible' => true,
		    'validation' => [],
		    'sortOrder' => 250,
		    'id' => 'delivery-date'
		];
        }
        return $jsLayout;
    }
}

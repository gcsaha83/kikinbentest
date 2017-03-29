<?php
namespace Kikinben\PhoneNumber\Plugin\Checkout\Model\Checkout;
class LayoutProcessor
{
    protected $_customerLogin;
    protected $_customerDetails;

    public function __construct(\Magento\Customer\Model\Session $customerLogin,\Magento\Customer\Model\Customer $customerDetails )
    {
        $this->_customerLogin = $customerLogin;
        $this->_customerDetails = $customerDetails;
    }
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
    ) {
	if(!$this->_customerLogin->isLoggedIn()){
		$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
		['shippingAddress']['children']['shipping-address-fieldset']['children']['telephone'] = [
		    'component' => 'Magento_Ui/js/form/element/abstract',
		    'config' => [
		        'customScope' => 'shippingAddress',
		        'template' => 'ui/form/field',
		        'elementTmpl' => 'ui/form/element/input',
		        'options' => [],
		        'id' => 'telephone'
		    ],
		    'dataScope' => 'shippingAddress.telephone',
		    'label' => 'Telephone Number',
		    'provider' => 'checkoutProvider',
		    'visible' => true,
            'validation' => [
                'required-entry' => true,
                'validate-phoneStrict'=>true
            ],
		    'sortOrder' => 250,
		    'id' => 'telephone'
		];
        }
    else if($this->_customerLogin->getId()){

        $customerDetailsFetch = $this->_customerDetails->load($this->_customerLogin->getId());

        	$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
		['shippingAddress']['children']['shipping-address-fieldset']['children']['telephone'] = [
		    'component' => 'Magento_Ui/js/form/element/abstract',
		    'config' => [
		        'customScope' => 'shippingAddress',
		        'template' => 'ui/form/field',
		        'elementTmpl' => 'ui/form/element/hidden',
		        'options' => [],
		        'id' => 'telephone'
		    ],
		    'dataScope' => 'shippingAddress.telephone',
		    'provider' => 'checkoutProvider',
		    'visible' => true,
		    'validation' => [],
		    'sortOrder' => 250,
            'id' => 'telephone',
            'value'=>$customerDetailsFetch->getMobilenumber()
		];



    }
        return $jsLayout;
    }
}

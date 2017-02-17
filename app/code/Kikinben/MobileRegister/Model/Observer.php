<?php

/*
 *
 * custom user reg page:
--
https://mage2.pro/t/topic/1331
http://magento.stackexchange.com/questions/113902/how-to-add-radio-option-in-magento-2
http://magento.stackexchange.com/questions/88245/magento2-create-a-customer-custom-attribute
https://amasty.com/customer-attributes-for-magento-2.html


address:
--
http://devdocs.magento.com/guides/v2.1/howdoi/checkout/checkout_address.html
http://www.magestore.com/magento-2-tutorial/create-customer-add-address-programmatically/
https://vinothkumaarr.wordpress.com/2016/05/13/add-customer-and-address-programmatically-magento2/
http://magento.stackexchange.com/questions/143947/updating-customer-group-from-observer-on-customer-register-success-even
 
*/

namespace Kikinben\MobileRegister\Model;
class Observer implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer){exit(__FILE__);}
}

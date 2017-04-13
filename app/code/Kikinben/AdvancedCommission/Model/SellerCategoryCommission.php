<?php
namespace Kikinben\AdvancedCommission\Model;
class SellerCategoryCommission extends \Magento\Framework\Model\AbstractModel implements SellerCategoryCommissionInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'kikinben_advancedcommission_sellercategorycommission';

    protected function _construct()
    {
        $this->_init('Kikinben\AdvancedCommission\Model\ResourceModel\SellerCategoryCommission');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}

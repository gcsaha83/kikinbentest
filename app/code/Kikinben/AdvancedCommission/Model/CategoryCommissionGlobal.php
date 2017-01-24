<?php
namespace Kikinben\AdvancedCommission\Model;
class CategoryCommissionGlobal extends \Magento\Framework\Model\AbstractModel implements CategoryCommissionGlobalInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'kikinben_advancedcommission_categorycommissionglobal';

    protected function _construct()
    {
        $this->_init('Kikinben\AdvancedCommission\Model\ResourceModel\CategoryCommissionGlobal');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}

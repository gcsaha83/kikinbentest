<?php
namespace Kikinben\AdvancedCommission\Model\ResourceModel\CategoryCommissionGlobal;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Kikinben\AdvancedCommission\Model\CategoryCommissionGlobal','Kikinben\AdvancedCommission\Model\ResourceModel\CategoryCommissionGlobal');
    }
}

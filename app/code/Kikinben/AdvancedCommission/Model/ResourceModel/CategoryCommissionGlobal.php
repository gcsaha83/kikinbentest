<?php
namespace Kikinben\AdvancedCommission\Model\ResourceModel;
class CategoryCommissionGlobal extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('kikinben_advancedcommission_categorycommissionglobal','kikinben_advancedcommission_categorycommissionglobal_id');
    }
}

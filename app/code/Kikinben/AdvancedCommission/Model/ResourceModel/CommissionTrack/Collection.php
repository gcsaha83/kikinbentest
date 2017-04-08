<?php
namespace Kikinben\AdvancedCommission\Model\ResourceModel\CommissionTrack;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Kikinben\AdvancedCommission\Model\CommissionTrack','Kikinben\AdvancedCommission\Model\ResourceModel\CommissionTrack');
    }
}

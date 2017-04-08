<?php
namespace Kikinben\AdvancedCommission\Model\ResourceModel;
class CommissionTrack extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('kikinben_advancedcommission_commissiontrack','kikinben_advancedcommission_commissiontrack_id');
    }
}

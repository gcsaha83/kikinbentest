<?php
namespace Kikinben\AdvancedCommission\Model\ResourceModel;
class GlobalLevelProductTrack extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('kikinben_advancedcommission_prd_commission_global_level_track','product_commission_global_level_track_id');
    }
}

<?php
namespace Kikinben\AdvancedCommission\Model;
class GlobalLevelProductTrack extends \Magento\Framework\Model\AbstractModel implements GlobalLevelProductTrackInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'kikinben_advancedcommission_prd_commission_global_level_track';

    protected function _construct()
    {
        $this->_init('Kikinben\AdvancedCommission\Model\ResourceModel\GlobalLevelProductTrack');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}

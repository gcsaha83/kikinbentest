<?php
namespace Kikinben\AdvancedCommission\Model;
class CommissionTrack extends \Magento\Framework\Model\AbstractModel implements CommissionTrackInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'kikinben_advancedcommission_commissiontrack';

    protected function _construct()
    {
        $this->_init('Kikinben\AdvancedCommission\Model\ResourceModel\CommissionTrack');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}

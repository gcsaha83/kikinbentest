<?php
namespace Kikinben\AdvancedCommission\Model\ResourceModel;
class SellerCategoryCommission extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('kikinben_advancedcommission_sellercategorycommission','kikinben_advancedcommission_sellercategorycommission_id');
    }
}

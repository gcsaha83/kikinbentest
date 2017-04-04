<?php
namespace Kikinben\AdvancedCommission\Model\ResourceModel\SellerCategoryCommission;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Kikinben\AdvancedCommission\Model\SellerCategoryCommission','Kikinben\AdvancedCommission\Model\ResourceModel\SellerCategoryCommission');
    }
}

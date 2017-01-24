<?php
namespace Kikinben\Otp\Model\ResourceModel\Otp;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Kikinben\Otp\Model\Otp','Kikinben\Otp\Model\ResourceModel\Otp');
    }
}

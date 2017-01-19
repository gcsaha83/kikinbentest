<?php
namespace Kikinben\Otp\Model\ResourceModel;
class Otp extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('kikinben_otp_otp','kikinben_otp_otp_id');
    }
}

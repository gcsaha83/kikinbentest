<?php
namespace Kikinben\Otp\Model;
class Otp extends \Magento\Framework\Model\AbstractModel implements OtpInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'kikinben_otp_otp';

    protected function _construct()
    {
        $this->_init('Kikinben\Otp\Model\ResourceModel\Otp');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}

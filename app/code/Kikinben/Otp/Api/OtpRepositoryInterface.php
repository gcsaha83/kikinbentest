<?php
namespace Kikinben\Otp\Api;

use Kikinben\Otp\Model\OtpInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface OtpRepositoryInterface 
{
    public function save(OtpInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(OtpInterface $page);

    public function deleteById($id);
}

<?php
namespace Kikinben\AdvancedCommission\Api;

use Kikinben\AdvancedCommission\Model\SellerCategoryCommissionInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface SellerCategoryCommissionRepositoryInterface 
{
    public function save(SellerCategoryCommissionInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(SellerCategoryCommissionInterface $page);

    public function deleteById($id);
}

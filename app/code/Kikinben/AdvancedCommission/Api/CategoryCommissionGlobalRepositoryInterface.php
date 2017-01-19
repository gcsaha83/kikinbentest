<?php
namespace Kikinben\AdvancedCommission\Api;

use Kikinben\AdvancedCommission\Model\CategoryCommissionGlobalInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface CategoryCommissionGlobalRepositoryInterface 
{
    public function save(CategoryCommissionGlobalInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(CategoryCommissionGlobalInterface $page);

    public function deleteById($id);
}

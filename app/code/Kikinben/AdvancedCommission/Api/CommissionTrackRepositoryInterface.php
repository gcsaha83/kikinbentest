<?php
namespace Kikinben\AdvancedCommission\Api;

use Kikinben\AdvancedCommission\Model\CommissionTrackInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface CommissionTrackRepositoryInterface 
{
    public function save(CommissionTrackInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(CommissionTrackInterface $page);

    public function deleteById($id);
}

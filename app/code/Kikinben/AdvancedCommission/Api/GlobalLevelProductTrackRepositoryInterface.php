<?php
namespace Kikinben\AdvancedCommission\Api;

use Kikinben\AdvancedCommission\Model\GlobalLevelProductTrackInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface GlobalLevelProductTrackRepositoryInterface 
{
    public function save(GlobalLevelProductTrackInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(GlobalLevelProductTrackInterface $page);

    public function deleteById($id);
}

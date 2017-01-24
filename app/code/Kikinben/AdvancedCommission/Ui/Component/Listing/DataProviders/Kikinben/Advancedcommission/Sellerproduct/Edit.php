<?php
namespace Kikinben\AdvancedCommission\Ui\Component\Listing\DataProviders\Kikinben\Advancedcommission\Sellerproduct;

class Edit extends \Magento\Ui\DataProvider\AbstractDataProvider
{    
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Kikinben\AdvancedCommission\Model\ResourceModel\SellerProductCommission\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }
    public function getData()
    {
    	return [];
    }
}

<?php
namespace Kikinben\AdvancedCommission\Block\Adminhtml\Sellercategory;

use Magento\Framework\DataObject;

class SellercategoryList extends \Magento\Backend\Block\Widget\Grid\Extended {
	
	protected $productFactory;
	protected $_sellerCollectionFactory;
	protected $_customer;
	protected $_collection;
	
	
	public function __construct(
			\Magento\Backend\Block\Template\Context $context,
			\Magento\Backend\Helper\Data $backendHelper,
			\Magento\Catalog\Model\ProductFactory $productFactory,
			\Magento\Framework\Registry $coreRegistry,
			\Apptha\Marketplace\Model\Seller $sellerCollectionFactory,
			\Magento\Customer\Model\Customer $customer,
			\Magento\Catalog\Model\CategoryFactory $categoryFactory,
			\Kikinben\AdvancedCommission\Model\ResourceModel\SellerCategory\Collection $sellerCategoryCollection,
			array $data = []
				
			) {
				$this->productFactory = $productFactory;
				$this->_coreRegistry = $coreRegistry;
				$this->_sellerCollectionFactory =  $sellerCollectionFactory;
				$this->_customer = $customer;
				$this->_categoryInstance = $categoryFactory;
				$this->_collection = $sellerCategoryCollection;
				parent::__construct($context, $backendHelper, $data);
	}
	
	protected function _construct()
	{
		parent::_construct();
		$this->setId('hello_tab_grid');
		$this->setDefaultSort('entity_id');
		$this->setUseAjax(true);
	}
	
	protected function _preparePage()
	{
		$this->getCollection()->setPageSize(500)->setCurPage(1);
	}
	
	protected function _prepareCollection()
	{		
		$customerId = $this->_sellerCollectionFactory->load($this->getData('customer_id'))->getCustomerId();
		$collectionProduct = $this->productFactory->create()->getCollection()->addAttributeToSelect("*");
		$collectionProduct->addAttributeToFilter ( 'seller_id', $customerId );
		foreach($collectionProduct as $collectionKey => $collectionVal){
			$productId = $collectionVal->getEntityId();
			$products = $this->productFactory->create()->load($productId);
			$cats = $products->getCategoryIds();
			foreach($cats as $k => $v){
				$sellerCategory[] = $v;
			}
		}
		
		$catgArrayUnique = array_unique($sellerCategory);
		$withoutParent = array_diff($catgArrayUnique,array(2,253,11));
		
		$catListTop = $this->_categoryInstance->create()->getCollection()->addAttributeToSelect(array('entity_id','name'));
		$catListTop->addAttributeToFilter('entity_id', $withoutParent);		
		
		foreach ($catListTop as $catTop) {
			$categoryData[] = [					
						'entity_id' =>$catTop->getEntityId(),
						'name'	    =>$catTop->getName() 	
			];
		}
		
		$collection = $this->_collection;
		foreach ($categoryData as $data) {
			$collection->addItem(
					new DataObject($data)
					);
		}
		$this->setCollection($collection);
		
		
		return parent::_prepareCollection();
	}
	protected function _prepareColumns()
	{
		
		$this->addColumn(
				'entity_id',
				[
						'header' => __('Category Id'),
						'sortable' => true,
						'index' => 'entity_id',
						'header_css_class' => 'col-id',
						'column_css_class' => 'col-id'
				]
				);
		$this->addColumn(
				'name',
				[
						'header' => __('Category Name'),
						'sortable' => true,
						'index' => 'name',
						'header_css_class' => 'col-id',
						'column_css_class' => 'col-id'
				]
				);
	
		return parent::_prepareColumns();
	}
	public function getGridUrl()
	{
		return $this->getUrl('sellerproduct/*/index', ['_current' => true]);
	}
	public function getRowUrl($row)
	{
		return $this->getUrl('kikinben_advancedcommission/sellercategory/edit', ['id' => $row->getEntityId(),'seller_id'=>$this->getData('customer_id')]);
	}
	
	
}

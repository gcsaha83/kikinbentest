<?php
namespace Magecomp\Mobilelogin\Block;
class Mobilelogin  extends \Magento\Framework\View\Element\Template
{
	  protected $_objectManager;
	 
	  protected $_collectionFactory;
	  public $_coreRegistry;
	  public $_filterProvider;
	  public $_blockFactory;
	
	public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager,
								\Magento\Backend\Block\Template\Context $context,
								
								\Magento\Framework\Registry $registry,
								 \Magento\Cms\Model\Template\FilterProvider $filterProvider,
								 \Magento\Cms\Model\BlockFactory $blockFactory,
								\Magento\Framework\Data\CollectionFactory $collectionFactory
							   )
	{
		$this->_objectManager = $objectManager;
		
		$this->_coreRegistry = $registry;
		$this->_filterProvider = $filterProvider;
        $this->_blockFactory = $blockFactory;
		$this->_collectionFactory = $collectionFactory;
		parent::__construct($context);
	}
	public function loadItem()
	{
		$product = $this->_objectManager->get('Magento\Framework\Registry')->registry('current_product');
		$collection = $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')
            ->addAttributeToSelect('*')
			->addFieldToFilter('entity_id',$product->getId())
            ->load();		
		return $collection;	 			
	}
	public function convert($sizechart)
	{
		 return $this->_filterProvider->getBlockFilter()->filter($sizechart);
	}
	
	
}
?>
<?php
namespace Kikinben\Breadcrumbs\Model;

class Observer implements \Magento\Framework\Event\ObserverInterface
{
    protected $_registry;
    protected $_productloader; 
    protected $_category;
    protected $_breadcrumbs;
    protected $_page;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,        
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Catalog\Model\Category $category,
        \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbs,
        \Magento\Framework\View\Result\PageFactory $page,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Catalog\Helper\Data $helper,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    )
    {        
        $this->_productloader = $_productloader;
        $this->_category      = $category;
        $this->_registry      = $registry;
        $this->_breadcrumbs   = $breadcrumbs;
        $this->_page          = $page->create();
        $this->_request       = $request;
        $this->_helper        = $helper;  
        $this->categoryRepository = $categoryRepository;
        $this->_storeManager = $storeManager;
        $this->context      = $context;
    }


    public function execute(\Magento\Framework\Event\Observer $observer){

        $title = array();
        $breadcrumbBlock = $this->_getBreadcrumbBlock();
        if (!$breadcrumbBlock) {
            return $this;
        }

        $this->_page->getLayout()->getBlock('breadcrumbs')->unsetChild('breadcrumbs');

        $productId = $this->_registry->registry('current_product')->getId();
        $product  = $this->_registry->registry('current_product');
        $categories = $product->getCategoryIds(); /*will return category ids array*/

        if(!empty($categories)){

            foreach($categories as $category){
            $cat = $this->_category->load($category);
            $name = $cat->getName();
            $catgPath = $cat->getPath();
            $catUrl = $cat->getUrl();
            }

            $categoryPath = explode("/",$catgPath);

        foreach($categoryPath as $Ckey => $Cval){

            $catgPathLink   = $this->_category->load($Cval);
            $getsubCatgName = $catgPathLink->getName();
            $getsubCatgLink = $catgPathLink->getUrl();
            $catgId         = $catgPathLink->getId();
            $category = $this->categoryRepository->get($Cval, $this->_storeManager->getStore()->getId());
            $breadcrumbBlock->addCrumb('product', array('label'=>$getsubCatgName,'link'=>$category->getUrl()));
           
        }
            $breadcrumbBlock->addCrumb('product', array('label'=>$product->getName()));


    }
        return $this;
    }
    protected function _getBreadcrumbBlock() {
        $layout = $this->_page->getLayout();
        $block = $layout->getBlock('breadcrumbs');
        return $block;
    }
     }

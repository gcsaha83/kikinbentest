<?php
namespace Kikinben\SellerCategory\Block;

class Menu extends \Magento\Catalog\Block\Navigation
{
    /**
     * @var Category
     */
    protected $_categoryInstance;

    /**
     * Current category key
     *
     * @var string
     */
    protected $_currentCategoryKey;

    /**
     * Array of level position counters
     *
     * @var array
     */
    protected $_itemLevelPositions = [];

    /**
     * Catalog category
     *
     * @var \Magento\Catalog\Helper\Category
     */
    protected $_catalogCategory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * Customer session
     *
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * Catalog layer
     *
     * @var \Magento\Catalog\Model\Layer
     */
    protected $_catalogLayer;

    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\Indexer\Category\Flat\State
     */
    protected $flatState;


    // +++++++++add new +++++++++

    public $_sysCfg;

    /**
     * magicmenu collection factory.
     *
     * @var \Magiccart\Magicmenu\Model\ResourceModel\Magicmenu\CollectionFactory
     */
    protected $_magicmenuCollectionFactory;
    /**
     *
     * @var Magiccart\Magicmenu\Block\GridFactory
     */
    protected $_gridFactory;


    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Catalog\Helper\Category $catalogCategory,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $flatState,
	\Magento\Catalog\Model\ProductFactory $gridFactory,
        \Magento\Customer\Model\Session $customer,
        \Magento\Store\Model\StoreManagerInterface $stores,
            

        // +++++++++add new +++++++++
        // \Magiccart\Magicmenu\Model\CategoryFactory $categoryFactory,
        \Magiccart\Magicmenu\Model\ResourceModel\Magicmenu\CollectionFactory $magicmenuCollectionFactory,

        array $data = []
    ) {

        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogLayer = $layerResolver->get();
        $this->httpContext = $httpContext;
        $this->_catalogCategory = $catalogCategory;
        $this->_registry = $registry;
        $this->flatState = $flatState;
        $this->_categoryInstance = $categoryFactory->create();
        $this->_customer          = $customer;

        // +++++++++add new +++++++++
        $this->_magicmenuCollectionFactory = $magicmenuCollectionFactory;
        $this->_sysCfg= (object) $context->getScopeConfig()->getValue(
            'magicmenu',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
	$this->_gridFactory = $gridFactory;
        $this->_stores  = $stores;

        parent::__construct($context, $categoryFactory, $productCollectionFactory, $layerResolver, $httpContext, $catalogCategory, $registry, $flatState, $data);

    }

    public function getIsHomePage()
    {
        return $this->getUrl('') == $this->getUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true));
    }

    public function isCategoryActive($catId)
    {
        return $this->getCurrentCategory() ? in_array($catId, $this->getCurrentCategory()->getPathIds()) : false;
    }

    public function getLogo()
    {
        $src = $this->_scopeConfig->getValue(
            'design/header/logo_src',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $logo = '<li class="level0 logo display"><a class="level-top" href="'.$this->getHomeUrl().'"><img alt="logo" src="' .$this->getSkinUrl($src). '"></a></li>';
        return $logo;
    }

    public function getRootName()
    {
        $rootCatId = $this->_storeManager->getStore()->getRootCategoryId();
	//custom code begins
        $categoryName = "";
        $seller = $this->getRequest()->getControllerName();
        $viewseller = $this->getRequest()->getActionName();
        if($seller === 'seller' && $viewseller === 'displayseller'){
        	$categoryName = "Seller Categories";
        }
        else{
        	$categoryName = $this->_categoryInstance->load($rootCatId)->getName();
        }
        //custom code ends
        
        return $categoryName;
        //return $this->_categoryInstance->load($rootCatId)->getName();
    }
    //custom code added by Apptha called in app/design/frontend/Alothemes/default/Magiccart_Magicmenu/templates/aio-topmenu.phtml
    public function appthaGetRootName()
    {
    	$rootCatId = $this->_storeManager->getStore()->getRootCategoryId();
    	return $this->_categoryInstance->load($rootCatId)->getName();
    }

    public function drawHomeMenu()
    {
        if($this->hasData('homeMenu')) return $this->getData('homeMenu');
        $drawHomeMenu = '';
        $active = ($this->getIsHomePage()) ? ' active' : '';
        $drawHomeMenu .= '<li class="level0 home' . $active . '">';
        $drawHomeMenu .= '<a class="level-top" href="'.$this->getBaseUrl().'"><span class="icon-home fa fa-home"></span><span class="icon-text">' .__('Home') .'</span>';
        $drawHomeMenu .= '</a>';
        if($this->_sysCfg->topmenu['demo']){
            $demo = '';
            $currentStore = $this->_storeManager->getStore();
            foreach ($this->_storeManager->getWebsites() as $website) {
                $groups = $website->getGroups();
                if(count($groups) > 1){
                    foreach ($groups as $group) {
                        $store = $group->getDefaultStore();
                        if ($store && !$store->getIsActive()) {
                            $stores = $group->getStores();
                            foreach ($stores as $store) {
                                if ($store->getIsActive()) break;
                                else $store = '';
                            }
                        }                     
                        if($store){
                            if( $store->getCode() == $currentStore->getCode() )  $demo .= '<div><a href="' .$store->getBaseUrl(). '"><span class="demo-home">'. $group->getName(). '</span></a></div>';
                            else $demo .= '<div><a href="'.$store->getBaseUrl(). 'stores/store/switch/?___store=' .$store->getCode(). '"><span class="demo-home">'. $group->getName(). '</span></a></div>';
                        }
                    }
                }
            }
            if($demo) $drawHomeMenu .= '<div class="level-top-mega">' .$demo .'</div>';           
        }

        $drawHomeMenu .= '</li>';
        $this->setData('homeMenu', $drawHomeMenu);
        return $drawHomeMenu;
    }

    public function drawMainMenu()
    {
        if($this->hasData('mainMenu')) return $this->getData('mainMenu');
        // Mage::log('your debug', null, 'yourlog.log');
        $desktopHtml = array();
		$mobileHtml  = array();
		 $rootCatId = $this->_storeManager->getStore()->getRootCategoryId();
                 //custom code starts
                $customerId = $this->getRequest ()->getParam ( 'id' );
                $seller = $this->getRequest()->getControllerName();
                $viewseller = $this->getRequest()->getActionName();                
                if($seller === 'seller' && $viewseller === 'displayseller'){
                    $collections = $this->_gridFactory->create ()->getCollection ();        
                    $collections->addAttributeToFilter ( 'seller_id', $customerId );
                    foreach($collections as $collectionV){          
                        $products = $this->_gridFactory->create ()->load($collectionV->getEntityId());
			
                        $cats = $products->getCategoryIds();
			
                        foreach($cats as $k => $v){
                            $sellerCategory[] = $v;
                    }
                    
                    }
                    $catgArrayUnique = array_unique($sellerCategory); 
		    $withoutParent = array_diff($catgArrayUnique,array(2,253,11));       		  
                    $filtered = array_filter($catgArrayUnique);
		    $catListTop = $this->_categoryInstance->getCollection()
                        ->addAttributeToSelect(array('entity_id','name','magic_label','url_path','magic_image','magic_thumbnail','kinkinbin_icon_thumb','image'))
                        ->addAttributeToFilter('entity_id', $withoutParent)
                        ->addAttributeToFilter('include_in_menu', 1)
                        ->addIsActiveFilter()
                        ->addAttributeToSort('position', 'asc');	
		    	
                    /*foreach($catgArrayUnique as $catgArrayUniqueVal){
			
			echo  $catgArrayUniqueVal."<br/>";
                        $catListTop = $this->getChildExt($catgArrayUniqueVal); 			

			
                    }*/
                }
                else{
                    $catListTop = $this->getChildExt($rootCatId);
                }
                //custom code ends
        //$catListTop = $this->getChildExt($rootCatId);
        $contentCatTop  = $this->getContentCatTop();
        $extData    = array();
        foreach ($contentCatTop as $ext) {
            $extData[$ext->getCatId()] = $ext->getData();
        }
        $i = 1; $last = count($catListTop);
        $dropdownIds = explode(',', $this->_sysCfg->general['dropdown']);
        foreach ($catListTop as $catTop) :
	    $idTop    = $catTop->getEntityId();
            $hasChild = $catTop->hasChildren() ? ' hasChild parent' : '';
            $isDropdown = in_array($idTop, $dropdownIds) ? ' dropdown' : '';
            $active   = $this->isCategoryActive($idTop) ? ' active' : '';
            $urlTop      =  '<a class="level-top" href="' .$catTop->getUrl(). '">' .$this->getThumbnail($catTop). '<span>' .__($catTop->getName()) . $this->getCatLabel($catTop). '</span><span class="boder-menu"></span></a>';
            $classTop    = ($i == 1) ? 'first' : ($i == $last ? 'last' : '');
            $classTop   .= $active . $hasChild .$isDropdown;

            // drawMainMenu
            if($isDropdown){ // Draw Dropdown Menu
		$childHtml = $this->getTreeCategoriesExt($idTop); // include magic_label
                $desktopHtml[$idTop] = '<li class="level0 nav-' .$i. ' cat ' . $classTop . '">' . $urlTop . $childHtml . '</li>';
                $mobileHtml[$idTop]  = '<li class="level0 nav-' .$i. ' '.$classTop.'">' . $urlTop . $childHtml . '</li>';
                $i++;
                continue;
            }
			// Draw Mega Menu 
            $data =''; $options='';
            if(isset($extData[$idTop])) $data   = $extData[$idTop];
            $blocks = array('top'=>'', 'left'=>'', 'right'=>'', 'bottom'=>'');
            if($data){
                foreach ($blocks as $key => $value) {
                    $proportion = $key .'_proportion';
                    $weight = (isset($data[$proportion])) ? $data[$proportion]:'';
                    $html = $this->getStaticBlock($data[$key]);
                    if($html) $blocks[$key] = "<div class='mage-column mega-block-$key'>".$html.'</div>';
                }
                $remove = array('top'=>'', 'left'=>'', 'right'=>'', 'bottom'=>'', 'cat_id'=>'');
                foreach ($remove as $key => $value) {
                    unset($data[$key]);
                }
                $opt     = json_encode($data);
                $options = $opt ? " data-options='$opt'" : '';
            }

			$desktopTmp = $mobileTmp  = '';
			if($hasChild || $blocks['top'] || $blocks['left'] || $blocks['right'] || $blocks['bottom']) :
				$desktopTmp .= '<div class="level-top-mega">';  /* Wrap Mega */
					$desktopTmp .='<div class="content-mega">';  /*  Content Mega */
						$desktopTmp .= $blocks['top'];
						$desktopTmp .= '<div class="content-mega-horizontal">';
							$desktopTmp .= $blocks['left'];
							if($hasChild) :
								$desktopTmp .= '<ul class="level0 mage-column cat-mega">';
								$mobileTmp .= '<ul class="submenu">';
								$childTop  = $this->getChildExt($idTop);
								foreach ($childTop as $child) {
									$id = $child->getId();
									$class = ' level1';
									$class .= $this->isCategoryActive($child->getId()) ? ' active' : '';
									$url =  '<a href="'. $child->getUrl().'"><span>'.__($child->getName()) . $this->getCatLabel($child) . '</span></a>';
									$childHtml = $this->getTreeCategoriesExt($id); // include magic_label
									// $childHtml = $this->getTreeCategoriesExtra($id); // include magic_label
									//$desktopTmp .= '<li class="children' . $class . '">' . $this->getImage($child) . $url . $childHtml . '</li>';
                                    $desktopTmp .= '<li class="children' . $class . '">'. $url . $this->getImage($child) . $childHtml . '</li>';

									$mobileTmp  .= '<li class="' . $class . '">' . $url . $childHtml . '</li>';
								}
								$desktopTmp .= '<li>'  .$blocks['bottom']. '</li>';
								$desktopTmp .= '</ul>'; // end cat-mega
								$mobileTmp .= '</ul>';
							endif;
							$desktopTmp .= $blocks['right'];
						$desktopTmp .= '</div>';
						//$desktopTmp .= $blocks['bottom'];
					$desktopTmp .= '</div>';  /* End Content mega */
				$desktopTmp .= '</div>';  /* Warp Mega */
			endif;
            $desktopHtml[$idTop] = '<li class="level0 nav-' .$i. ' cat ' . $classTop . '"' . $options .'>' .$urlTop . $desktopTmp . '</li>';
            $mobileHtml[$idTop]  = '<li class="level0 nav-' .$i. ' '. $classTop . '">' . $urlTop . $mobileTmp . '</li>';
            $i++;
        endforeach;
        $menu['desktop'] = $desktopHtml;
        $menu['mobile'] = implode("\n", $mobileHtml);
        $this->setData('mainMenu', $menu);
        return $menu;
    }
    //custom code added by Apptha called in app/design/frontend/Alothemes/default/Magiccart_Magicmenu/templates/aio-topmenu.phtml

   

    public function appthaDrawMainMenu()
    {
    	if($this->hasData('mainMenu')) return $this->getData('mainMenu');
    	// Mage::log('your debug', null, 'yourlog.log');
    	$desktopHtml = array();
    	$mobileHtml  = array();
    	$rootCatId = $this->_storeManager->getStore()->getRootCategoryId();
    	
    	$catListTop = $this->getChildExt($rootCatId);
    
    	$contentCatTop  = $this->getContentCatTop();
    	$extData    = array();
    	foreach ($contentCatTop as $ext) {
    		$extData[$ext->getCatId()] = $ext->getData();
    	}
    	$i = 1; $last = count($catListTop);
    	$dropdownIds = explode(',', $this->_sysCfg->general['dropdown']);
    	foreach ($catListTop as $catTop) :
    	$idTop    = $catTop->getEntityId();
    	$hasChild = $catTop->hasChildren() ? ' hasChild parent' : '';
    	$isDropdown = in_array($idTop, $dropdownIds) ? ' dropdown' : '';
    	$active   = $this->isCategoryActive($idTop) ? ' active' : '';
    	$urlTop      =  '<a class="level-top" href="' .$catTop->getUrl(). '">' .$this->getThumbnail($catTop). '<span>' .__($catTop->getName()) . $this->getCatLabel($catTop). '</span><span class="boder-menu"></span></a>';
    	$classTop    = ($i == 1) ? 'first' : ($i == $last ? 'last' : '');
    	$classTop   .= $active . $hasChild .$isDropdown;
    
    	// drawMainMenu
    	if($isDropdown){ // Draw Dropdown Menu
    		$childHtml = $this->getTreeCategoriesExt($idTop); // include magic_label
    		$desktopHtml[$idTop] = '<li class="level0 nav-' .$i. ' cat ' . $classTop . '">' . $urlTop . $childHtml . '</li>';
    		$mobileHtml[$idTop]  = '<li class="level0 nav-' .$i. ' '.$classTop.'">' . $urlTop . $childHtml . '</li>';
    		$i++;
    		continue;
    	}
    	// Draw Mega Menu
    	$data =''; $options='';
    	if(isset($extData[$idTop])) $data   = $extData[$idTop];
    	$blocks = array('top'=>'', 'left'=>'', 'right'=>'', 'bottom'=>'');
    	if($data){
    		foreach ($blocks as $key => $value) {
    			$proportion = $key .'_proportion';
    			$weight = (isset($data[$proportion])) ? $data[$proportion]:'';
    			$html = $this->getStaticBlock($data[$key]);
    			if($html) $blocks[$key] = "<div class='mage-column mega-block-$key'>".$html.'</div>';
    		}
    		$remove = array('top'=>'', 'left'=>'', 'right'=>'', 'bottom'=>'', 'cat_id'=>'');
    		foreach ($remove as $key => $value) {
    			unset($data[$key]);
    		}
    		$opt     = json_encode($data);
    		$options = $opt ? " data-options='$opt'" : '';
    	}
    
    	$desktopTmp = $mobileTmp  = '';
    	if($hasChild || $blocks['top'] || $blocks['left'] || $blocks['right'] || $blocks['bottom']) :
    	$desktopTmp .= '<div class="level-top-mega">';  /* Wrap Mega */
    	$desktopTmp .='<div class="content-mega">';  /*  Content Mega */
    	$desktopTmp .= $blocks['top'];
    	$desktopTmp .= '<div class="content-mega-horizontal">';
    	$desktopTmp .= $blocks['left'];
    	if($hasChild) :
    	$desktopTmp .= '<ul class="level0 mage-column cat-mega">';
    	$mobileTmp .= '<ul class="submenu">';
    	$childTop  = $this->getChildExt($idTop);
    	foreach ($childTop as $child) {
    		$id = $child->getId();
    		$class = ' level1';
    		$class .= $this->isCategoryActive($child->getId()) ? ' active' : '';
    		$url =  '<a href="'. $child->getUrl().'"><span>'.__($child->getName()) . $this->getCatLabel($child) . '</span></a>';
    		$childHtml = $this->getTreeCategoriesExt($id); // include magic_label
    		// $childHtml = $this->getTreeCategoriesExtra($id); // include magic_label
    		$desktopTmp .= '<li class="children' . $class . '">' . $this->getImage($child) . $url . $childHtml . '</li>';
    		$mobileTmp  .= '<li class="' . $class . '">' . $url . $childHtml . '</li>';
    	}
    	$desktopTmp .= '<li>'  .$blocks['bottom']. '</li>';
    	$desktopTmp .= '</ul>'; // end cat-mega
    	$mobileTmp .= '</ul>';
    	endif;
    	$desktopTmp .= $blocks['right'];
    	$desktopTmp .= '</div>';
    	//$desktopTmp .= $blocks['bottom'];
    	$desktopTmp .= '</div>';  /* End Content mega */
    	$desktopTmp .= '</div>';  /* Warp Mega */
    	endif;
    	$desktopHtml[$idTop] = '<li class="level0 nav-' .$i. ' cat ' . $classTop . '"' . $options .'>' .$urlTop . $desktopTmp . '</li>';
    	$mobileHtml[$idTop]  = '<li class="level0 nav-' .$i. ' '. $classTop . '">' . $urlTop . $mobileTmp . '</li>';
    	$i++;
    	endforeach;
    	$menu['desktop'] = $desktopHtml;
    	$menu['mobile'] = implode("\n", $mobileHtml);
    	$this->setData('mainMenu', $menu);
    	return $menu;
    }
    //custom code ends

    //apptha seller profile category list

    public function appthaSellerCategoryList($customerId){
        
        if($this->hasData('mainMenu')) 
	        return $this->getData('mainMenu');
        $desktopHtml = array();
        $mobileHtml  = array();
        $objectModelManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $collections = $objectModelManager->get ( 'Magento\Catalog\Model\ResourceModel\Product\Collection' )
                ->addAttributeToSelect ( '*' )
                ->addAttributeToFilter ( 'seller_id', $customerId )
                ->addAttributeToFilter ( 'product_approval', 1 )                
                ->addAttributeToFilter ( 'status', 1 )
                ->addAttributeToFilter ( 'visibility', array (
                'eq' => \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH
        ) );
        
        //echo $collections->getSelect();die;
        foreach($collections as $collectionV){   
            
            $products = $this->_gridFactory->create ()->load($collectionV->getEntityId());		
            $cats = $products->getCategoryIds();			
            foreach($cats as $k => $v){
        	$sellerCategory[] = $v;
            }                    
        }
        $catgArrayUnique = array_unique($sellerCategory);
        $withoutParent = array_diff($catgArrayUnique,array(2,253,11));
        
        //print_r($withoutParent);
        
        $catListTop = $this->_categoryInstance->getCollection()
                        ->addAttributeToSelect(array('entity_id','name','magic_label','url_path','magic_image','magic_thumbnail','kinkinbin_icon_thumb','image'))
                        ->addAttributeToFilter('entity_id', $withoutParent)
                        ->addAttributeToFilter('include_in_menu', 1)
                        ->addIsActiveFilter()
                        ->addAttributeToSort('position', 'asc');	
        $contentCatTop  = $this->getContentCatTop();
        $extData    = array();
        foreach ($contentCatTop as $ext) {
            $extData[$ext->getCatId()] = $ext->getData();
        }
        $i = 1; 
        $last = count($catListTop);
        $dropdownIds = explode(',', $this->_sysCfg->general['dropdown']);
        foreach ($catListTop as $catTop) {
            $baseUrl = $this->_stores->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK);
            //$sellerProductUrl = $baseUrl.'marketplace/seller/displayseller/id/'.$customerId.'/catgid/'.$catTop->getEntityId();
            $sellerProductUrl = $baseUrl.'kikinben_sellercategory/index/index/id/'.$customerId.'/catgid/'.$catTop->getEntityId();
            $idTop    = $catTop->getEntityId();
            $active   = $this->isCategoryActive($idTop) ? ' active' : '';
            $isDropdown = in_array($idTop, $dropdownIds) ? ' dropdown' : '';
            //$urlTop      =  '<a class="level-top" href="' .$catTop->getUrl(). '">' .$this->getThumbnail($catTop). '<span>' .__($catTop->getName()) . $this->getCatLabel($catTop). '</span><span class="boder-menu"></span></a>';
            //$urlTop      =  '<a onclick="getSellerProducts('.$customerId.','.$idTop.')" class="level-top" href="' .$sellerProductUrl. '">' .$this->getThumbnail($catTop). '<span>' .__($catTop->getName()) . $this->getCatLabel($catTop). '</span><span class="boder-menu"></span></a>';
            
            $urlTop      =  '<a onclick="getSellerProducts('.$customerId.','.$idTop.')" class="level-top" href="#">' .$this->getThumbnail($catTop). '<span>' .__($catTop->getName()) . $this->getCatLabel($catTop). '</span><span class="boder-menu"></span></a>';

            $classTop    = ($i == 1) ? 'first' : ($i == $last ? 'last' : '');
            $classTop   .= $active ;
            $desktopHtml[$idTop] = '<li class="level0 nav-' .$i. ' cat ' . $classTop . '">' . $urlTop .  '</li>';
    		$mobileHtml[$idTop]  = '<li class="level0 nav-' .$i. ' '.$classTop.'">' . $urlTop .  '</li>';
        }
        $menu['desktop'] = $desktopHtml;
    	$menu['mobile'] = implode("\n", $mobileHtml);
    	$this->setData('mainMenu', $menu);
    	return $menu;

    }
   
    
    public function appthaSellerCategoryListFiltered($customerId){
        
        if($this->hasData('mainMenu')) 
	        return $this->getData('mainMenu');
        $desktopHtml = array();
        $mobileHtml  = array();
        
        $collections = $this->_productCollectionFactory->create();
        $collections ->addAttributeToSelect ( '*' )
                ->addAttributeToFilter ( 'seller_id', $customerId )
                ->addAttributeToFilter ( 'product_approval', 1 )                
                ->addAttributeToFilter ( 'status', 1 )
                ->addAttributeToFilter ( 'visibility', array (
                'eq' => \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH
        ));
        //echo $collections->getSelect();   
        foreach($collections as $collectionV){   
            
            $products = $this->_gridFactory->create ()->load($collectionV->getEntityId());		
            $cats = $products->getCategoryIds();			
            foreach($cats as $k => $v){
        	$sellerCategory[] = $v;
            }                    
        }
        $catgArrayUnique = array_unique($sellerCategory);
        $withoutParent = array_diff($catgArrayUnique,array(2,253,11));
        
        //print_r($withoutParent);die;    
        
        $catListTop = $this->_categoryInstance->getCollection()
                        ->addAttributeToSelect(array('entity_id','name','magic_label','url_path','magic_image','magic_thumbnail','kinkinbin_icon_thumb','image'))
                        ->addAttributeToFilter('entity_id', $withoutParent)
                        ->addAttributeToFilter('include_in_menu', 1)
                        ->addIsActiveFilter()
                        ->addAttributeToSort('position', 'asc');	
        $contentCatTop  = $this->getContentCatTop();
        $extData    = array();
        foreach ($contentCatTop as $ext) {
            $extData[$ext->getCatId()] = $ext->getData();
        }
        $i = 1; 
        $last = count($catListTop);
        $dropdownIds = explode(',', $this->_sysCfg->general['dropdown']);
        foreach ($catListTop as $catTop) {
            $baseUrl = $this->_stores->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK);
            //$sellerProductUrl = $baseUrl.'marketplace/seller/displayseller/id/'.$customerId.'/catgid/'.$catTop->getEntityId();
            $sellerProductUrl = $baseUrl.'kikinben_sellercategory/index/index/id/'.$customerId.'/catgid/'.$catTop->getEntityId();
            $idTop    = $catTop->getEntityId();
            $active   = $this->isCategoryActive($idTop) ? ' active' : '';
            $isDropdown = in_array($idTop, $dropdownIds) ? ' dropdown' : '';
            //$urlTop      =  '<a class="level-top" href="' .$catTop->getUrl(). '">' .$this->getThumbnail($catTop). '<span>' .__($catTop->getName()) . $this->getCatLabel($catTop). '</span><span class="boder-menu"></span></a>';

            $urlTop      =  '<a class="level-top" href="' .$sellerProductUrl. '">' .$this->getThumbnail($catTop). '<span>' .__($catTop->getName()) . $this->getCatLabel($catTop). '</span><span class="boder-menu"></span></a>';

            $classTop    = ($i == 1) ? 'first' : ($i == $last ? 'last' : '');
            $classTop   .= $active ;
            $desktopHtml[$idTop] = '<li class="level0 nav-' .$i. ' cat ' . $classTop . '">' . $urlTop .  '</li>';
    		$mobileHtml[$idTop]  = '<li class="level0 nav-' .$i. ' '.$classTop.'">' . $urlTop .  '</li>';
        }
        $menu['desktop'] = $desktopHtml;
    	$menu['mobile'] = implode("\n", $mobileHtml);
    	$this->setData('mainMenu', $menu);
    	return $menu;

    }
    public function drawExtraMenu()
    {
      
        if($this->hasData('extraMenu')) return $this->getData('extraMenu');
        $extMenu    = $this->getExtraMenu();
        $count = count($extMenu);
        $drawExtraMenu = '';
        if($count){
            $i = 1; $class = 'first';
            $currentUrl = $this->getCurrentUrl();
            foreach ($extMenu as $ext) { 
                $link = $ext->getLink();
                $url = (filter_var($link, FILTER_VALIDATE_URL)) ? $link : $this->getUrl($link);
                $active = ( $link && $url == $currentUrl) ? ' active' : '';
                $html = $this->getStaticBlock($ext->getExtContent());
                if($html) $active .=' hasChild parent';
                $drawExtraMenu .= "<li class='level0 dropdown ext $active $class'>";
                    if($link) $drawExtraMenu .= '<a class="level-top" href="' .$url. '"><span>' .__($ext->getName()) . $this->getCatLabel($ext). '</span></a>';
                    else $drawExtraMenu .= '<span class="level-top"><span>' .__($ext->getName()) . $this->getCatLabel($ext). '</span></span>';
                    if($html) $drawExtraMenu .= $html; //$drawExtraMenu .= '<div class="level-top-mega">'.$html.'</div>';
                $drawExtraMenu .= '</li>';
                $i++;
                $class = ($i == $count) ? 'last' : '';  
            }
        }
        $this->setData('extraMenu', $drawExtraMenu);
        return $drawExtraMenu;
    }

    public function getChildExt($parentId)
    {
	

        /*$collection = $this->_categoryInstance->getCollection()
                        ->addAttributeToSelect(array('entity_id','name','magic_label','url_path','magic_image','magic_thumbnail'))
                        ->addAttributeToFilter('parent_id', $parentId)
                        ->addAttributeToFilter('include_in_menu', 1)
                        ->addIsActiveFilter()
                        ->addAttributeToSort('position', 'asc'); //->addOrderField('name');*/

        $collection = $this->_categoryInstance->getCollection()
                        ->addAttributeToSelect(array('entity_id','name','magic_label','url_path','magic_image','magic_thumbnail','kinkinbin_icon_thumb','image'))
                        ->addAttributeToFilter('parent_id', $parentId)
                        ->addAttributeToFilter('include_in_menu', 1)
                        ->addIsActiveFilter()
                        ->addAttributeToSort('position', 'asc'); //->addOrderField('name');




        return $collection;
    }

    public function getExtraMenu()
    {
        
        //code by contus starts
        

        if($this->_customer->isLoggedIn()){
         $store = $this->_storeManager->getStore()->getStoreId();
         $collection = $this->_magicmenuCollectionFactory->create();

            
                       $collection ->addFieldToSelect(array('link','name','magic_label','ext_content','order'))
                        
                        ->addFieldToFilter('extra', 1) 
                        ->addFieldToFilter('status', 1);

    }else{
        $store = $this->_storeManager->getStore()->getStoreId();
        $collection = $this->_magicmenuCollectionFactory->create();

                        $collection->addFieldToSelect(array('link','name','magic_label','ext_content','order'))
                        ->addFieldToFilter('name',array('nin'=>array('Your Account')))    
                        ->addFieldToFilter('extra', 1)
                        ->addFieldToFilter('status', 1);

        }
        //code by contus ends

       /*$store = $this->_storeManager->getStore()->getStoreId();

        $collection = $this->_magicmenuCollectionFactory->create()
                        ->addFieldToSelect(array('link','name','magic_label','ext_content','order'))
                        ->addFieldToFilter('extra', 1)
                        ->addFieldToFilter('status', 1);
        $collection->getSelect()->where('find_in_set(0, stores) OR find_in_set(?, stores)', $store)->order('order');*/
        return $collection;        
    }

    public function getStaticBlock($id)
    {
        return $this->getLayout()->createBlock('Magento\Cms\Block\Block')->setBlockId($id)->toHtml();
    }

    public function getContentCatTop()
    {
        $store = $this->_storeManager->getStore()->getStoreId();
        $collection = $this->_magicmenuCollectionFactory->create()
                        ->addFieldToSelect(array(
                                'cat_id','cat_col','cat_proportion','top',
                                'right','right_proportion','bottom','left','left_proportion'
                            ))
                        ->addFieldToFilter('stores',array( array('finset' => 0), array('finset' => $store)))
                        ->addFieldToFilter('status', 1);
        return $collection;
    }

    public function  getTreeCategoriesExt($parentId) // include Magic_Label
    { 
        $categories = $this->_categoryInstance->getCollection()
                        ->addAttributeToSelect(array('name','magic_label','url_path'))
                        ->addAttributeToFilter('include_in_menu', 1)
                        ->addAttributeToFilter('parent_id', $parentId)
						->addIsActiveFilter()
                        ->addAttributeToSort('position', 'asc'); 
        $html = '';
        foreach($categories as $category)
        {
            $level = $category->getLevel();
            $childHtml = $this->getTreeCategoriesExt($category->getId());
            $childClass = $childHtml ? ' hasChild parent' : '';
            $childClass .= $this->isCategoryActive($category->getId()) ? ' active' : '';
            $html .= '<li class="level' .($level -2) .$childClass. '"><a href="' . $category->getUrl(). '"><span>' .$category->getName() . $this->getCatLabel($category) . "</span></a>\n" . $childHtml . '</li>';
        }
        if($html) $html = '<ul class="level'.($level -3).' submenu">' .$html. '</ul>';
        return $html;
    }

    public function  getTreeCategoriesExtra($parentId) // include Magic_Label
    {
        $html = '';
        $categories = $this->_categoryInstance->getCategories($parentId);
        foreach($categories as $category) {
            $cat = $this->_categoryInstance->load($category->getId());
            $count = $cat->getProductCount();
            $level = $cat->getLevel();
            $childClass = $category->hasChildren() ? ' hasChild parent' : '';
            $childClass .= $this->isCategoryActive($category->getId()) ? ' active' : '';
            $html .= '<li class="level' .($level -2) .$childClass. '"><span class="alo-expand"><a href="' . $cat->getUrl(). '"><span>' .$cat->getName() . "(".$count.")" . $this->getCatLabel($cat). "</span></a>\n";
            if($childClass) $html .=  $this->getTreeCategories($category->getId());
            $html .= '</li>';
        }
        $html = '<ul class="level' .($level -3). ' submenu">' . $html . '</ul>';
        return  $html;
    }

    public function getCatLabel($cat)
    {
        $html = '';
        $label = explode(',', $cat->getMagicLabel());
        foreach ($label as $lab) {
          if($lab) $html .= '<span class="cat_label '.$lab.'">'.__(trim($lab)) .'</span>';
        }
        return $html;
    }

    public function getImage($object)
    {
        $url = false;
        //$image = $object->getMagicImage(); 
        $image = $object->getImage();
        
        if ($image) {
            $url = $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . 'catalog/category/' . $image;
        }
        if($url) return '<a class="a-image" href="' .$object->getUrl(). '"><img class="img-responsive" alt="' .$object->getName(). '" src="'.$url.'"></a>';
    }

    public function getThumbnail($object)
    {
        $url = false;
        $image = $object->getKinkinbinIconThumb();
        //$image = $object->getImage();
        if ($image) {
            $url = $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . 'catalog/category/' . $image;
        }
        if($url) return '<img class="img-responsive" alt="' .$object->getName(). '" src="'.$url.'">';
    }
    
    //contus market place codes
    public function getProductsList($customerId,$categoryId){
        
       $category = $this->_categoryInstance->load($categoryId); 
       $collections = $this->_productCollectionFactory->create();
       $collections ->addAttributeToSelect ( '*' )
                ->addAttributeToFilter ( 'seller_id', $customerId )
                ->addAttributeToFilter ( 'product_approval', 1 )  
                ->addCategoryFilter($category)
                ->addAttributeToFilter ( 'status', 1 )
                ->addAttributeToFilter ( 'visibility', array (
                'eq' => \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH
        ));
        //echo $collections->getSelect();die;
       return $collections;
    }
    
    /**
     * Function to Get Seller State and Address
     *
     * @return array
     */
    public function getAddress() {
        $objectModelManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $customerId = $this->getRequest ()->getParam ( 'id' );
        $sellerDatas = $objectModelManager->get ( 'Apptha\Marketplace\Model\Seller' );
        $sellerDetails = $sellerDatas->load ( $customerId, 'customer_id' );
        $sellerState = $sellerDetails->getState ();
        $sellerCountry = trim ( $sellerDetails->getCountry () );
        return array (
                'state' => $sellerState,
                'country' => $sellerCountry
        );
    }
    /**
     * Function to get Contact Details of seller
     *
     * @return array
     */
    public function getSellerDetails() {
        $customerName = $customerEmail = '';
        $sellerId = $this->getRequest ()->getParam ( 'id' );
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $sellerModel = $objectManager->get ( 'Magento\Customer\Model\Customer' );
        $sellerDetails = $sellerModel->load ( $sellerId );
        $sellerName = $sellerDetails->getFirstname ();
        $sellerEmail = $sellerDetails->getEmail ();
        $customerSession = $objectManager->create ( 'Magento\Customer\Model\Session' );
        if ($customerSession->isLoggedIn ()) {
            $customerName = $customerSession->getCustomer ()->getName ();
            $customerEmail = $customerSession->getCustomer ()->getEmail ();
        }
        return array (
                'seller_name' => $sellerName,
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'seller_id' => $sellerId,
                'seller_email' => $sellerEmail
        );
    }

    /**
     * Get post parameters
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product) {
        $objectModelManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $url = $this->getAddToCartUrl ( $product );
        return [
                'action' => $url,
                'data' => [
                        'product' => $product->getEntityId (),
                        \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED => $objectModelManager->get ( 'Magento\Framework\Url\Helper\Data' )->getEncodedUrl ( $url )
                ]
        ];
    }

    /**
     * Retrieve add to wishlist params
     *
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return string
     */
    public function getAddToWishlistParams($product) {
        $objectModelManager = \Magento\Framework\App\ObjectManager::getInstance ();
        return $objectModelManager->get ( 'Magento\Wishlist\Helper\Data' )->getAddParams ( $product );
    }

    /**
     * Retrieve url for add product to cart
     * Will return product view page URL if product has required options
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param array $additional
     *
     * @return string
     */
    public function getAddToCartUrl($product, $additional = []) {
        $objectModelManager = \Magento\Framework\App\ObjectManager::getInstance ();
        if ($product->getTypeInstance ()->hasRequiredOptions ( $product )) {
            if (! isset ( $additional ['_escape'] )) {
                $additional ['_escape'] = true;
            }
            if (! isset ( $additional ['_query'] )) {
                $additional ['_query'] = [ ];
            }
            $additional ['_query'] ['options'] = 'cart';

            return $this->getProductUrl ( $product, $additional );
        }
        return $objectModelManager->get ( 'Magento\Checkout\Helper\Cart' )->getAddUrl ( $product, $additional );
    }

    /**
     * Get product url
     *
     * @param Object $product
     * @param array $additional
     *
     * @return string
     */
    public function getProductUrl($product, $additional = []) {
        if ($this->hasProductUrl ( $product )) {
            if (! isset ( $additional ['_escape'] )) {
                $additional ['_escape'] = true;
            }
            return $product->getUrlModel ()->getUrl ( $product, $additional );
        }

        return '#';
    }

    /**
     * Check whether product url or not
     *
     * @param Object $product
     *
     * @return bool
     */
    public function hasProductUrl($product) {
        if ($product->getVisibleInSiteVisibilities ()) {
            return true;
        }
        if ($product->hasUrlDataObject () && in_array ( $product->hasUrlDataObject ()->getVisibility (), $product->getVisibleInSiteVisibilities () )) {
            return true;
        }
        return false;
    }

    /**
     * Get rating data
     *
     * @param int $sellerId
     *
     * @return array
     */
    public function getRatingsData($sellerId) {
        $ratings = $count = array ();

        $oneRating = $twoRating = $threeRating = $fourRating = $fiveRating = $overall = 0;

        /**
         * Get review collection
         */
        $reviews = $this->getReviewcount ( $sellerId );

        foreach ( $reviews as $review ) {
            $overall = $overall + 1;
            switch ($review->getRating ()) {
                case 1 :
                    $oneRating = $oneRating + 1;
                    break;
                case 2 :
                    $twoRating = $twoRating + 1;
                    break;
                case 3 :
                    $threeRating = $threeRating + 1;
                    break;
                case 4 :
                    $fourRating = $fourRating + 1;
                    break;
                default :
                    $fiveRating = $fiveRating + 1;
            }
        }

        /**
         * Check advanced total is greater than or equal to 1
         */
        if ($overall != 0) {
            if ($oneRating > 0) {
                $ratings ['one'] = ($oneRating / $overall) * 100;
            }
            if ($twoRating > 0) {
                $ratings ['two'] = ($twoRating / $overall) * 100;
            }
            if ($threeRating > 0) {
                $ratings ['three'] = ($threeRating / $overall) * 100;
            }
            if ($fourRating > 0) {
                $ratings ['four'] = ($fourRating / $overall) * 100;
            }
            if ($fiveRating > 0) {
                $ratings ['five'] = ($fiveRating / $overall) * 100;
            }

            $count ['one'] = $oneRating;
            $count ['two'] = $twoRating;
            $count ['three'] = $threeRating;
            $count ['four'] = $fourRating;
            $count ['five'] = $fiveRating;
        }
        return array (
                'percent' => $ratings,
                'count' => $count
        );
    }

    /**
     * Get review data
     *
     * @param int $sellerId
     *
     * @return object
     */
    public function getReviewcount($sellerId) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $reviews = $objectManager->get ( 'Apptha\Marketplace\Model\Review' )->getCollection ();
        $reviews->addFieldToSelect ( '*' );
        $reviews->addFieldToFilter ( 'seller_id', $sellerId );
        $reviews->addFieldToFilter ( 'status', 1 );
        return $reviews;
    }

    /**
     * Get review url
     */
    public function getReviewUrl($sellerId) {
        return $this->getUrl ( 'marketplace/seller/review/seller_id/' . $sellerId );
    }

    /**
     * Check whether customer logged in or not
     */
    public function getLoggedInCustomerId() {
        $customerId = '';
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $customerSession = $objectManager->get ( 'Magento\Customer\Model\Session' );
        if ($customerSession->isLoggedIn ()) {
            $customerId = $customerSession->getId ();
        }
        return $customerId;
    }

    /**
     * Get login url
     *
     * @return string
     */
    public function getLoginUrl() {
        return $this->getUrl ( 'customer/account/login' );
    }

    /**
     * Get write review url
     *
     * @return string
     */
    public function getWriteReviewUrl($sellerId) {
        return $this->getUrl ( 'marketplace/seller/review/seller_id/' . $sellerId . '/write/1' );
    }

    /**
     * Check whether customer can review or not
     *
     * @param int $customerId
     * @param int $sellerId
     *
     * @return bool
     */
    public function canReview($customerId, $sellerId) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
        return $objectManager->get ( 'Apptha\Marketplace\Block\Seller\Review' )->canReview ( $customerId, $sellerId );
    }
    /**
     * Check whether is seller review enable or not
     *
     * @return bool
     */
    public function isSellerReviewEnabled() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $sellerReview = $objectManager->get ( 'Apptha\Marketplace\Helper\Data' );
        return $sellerReview->isSellerReviewEnabled ();
    }
    public function getProductPrice(\Magento\Catalog\Model\Product $product){
        $priceRender = $this->getPriceRender();

        $price = '';
        if ($priceRender) {
            $price = $priceRender->render(
                    \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
                    $product,
                    [
                            'include_container' => true,
                            'display_minimal_price' => true,
                            'zone' => \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST
                    ]
                    );
        }

        return $price;
    }

    /**
     * @return \Magento\Framework\Pricing\Render
     */
    protected function getPriceRender() {
        return $this->getLayout()->getBlock('product.price.render.default');
    }
    public function getDisplaySellerContact(){
        $isSellerStoreInformationEnabled = 0;
        $objectModelManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $isSellerProfileInformation = $objectModelManager->get ( 'Magento\Framework\App\Config\ScopeConfigInterface' )->getValue ( 'marketplace/seller/seller_profile_information', \Magento\Store\Model\ScopeInterface::SCOPE_STORE );

        if ($isSellerProfileInformation == 1 ) {
            $isSellerStoreInformationEnabled = 1;
        }
        return $isSellerStoreInformationEnabled;
    }

}

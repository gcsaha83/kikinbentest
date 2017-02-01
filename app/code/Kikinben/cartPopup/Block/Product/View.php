<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Kikinben\cartPopup\Block\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;

/**
 * Product View block
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class View  extends \Magento\Catalog\Block\Product\View
{
    		
   public function getRelatedProductCollection($id){
   	   	   	
   	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
   	$productCollection = $objectManager->create('Magento\Catalog\Model\Product');
   	$product = $productCollection->load($id);
   	return $product;  
   }
   
   public function getProductImageDetails($id){
   	 
   	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
   	$product = $objectManager->create('Magento\Catalog\Model\Product')->load($id);   	
   	$thumb = $product->getThumbnail();
   	$price = $product->getPrice();
   	$name  = $product->getName();
   	$productDetails = array('name'=>$name,'image'=>$thumb,'price'=>$price);
   	return $productDetails;
   }
   public function getProductName($id){
   	
   }
   public function getCurrentUrl() {
       $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
       $storeCollection = $objectManager->create('\Magento\Store\Model\StoreManagerInterface'); 
       return $storeCollection->getStore()->getCurrentUrl();
   }
}

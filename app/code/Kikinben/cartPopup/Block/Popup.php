<?php
namespace Kikinben\cartPopup\Block;
class Popup extends \Magento\Catalog\Block\Product\View
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
    function _prepareLayout(){}
    
}

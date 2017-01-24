<?php
/**
 * Apptha
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.apptha.com/LICENSE.txt
 *
 * ==============================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * ==============================================================
 * This package designed for Magento COMMUNITY edition
 * Apptha does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Apptha does not provide extension support in case of
 * incorrect edition usage.
 * ==============================================================
 *
 * @category    Apptha
 * @package     Apptha_Marketplace
 * @version     1.1
 * @author      Apptha Team <developers@contus.in>
 * @copyright   Copyright (c) 2016 Apptha. (http://www.apptha.com)
 * @license     http://www.apptha.com/LICENSE.txt
 *
 */

namespace Kikinben\AdvancedCommission\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;


class KikinbenAdvancedCommissionAdminCategoryOptions extends AbstractHelper
{
	/**
	 * Filter manager
	 *
	 * @var \Magento\Framework\Filter\FilterManager
	 */
	protected $filter;
	
	/**
	 * Escaper
	 *
	 * @var \Magento\Framework\Escaper
	 */
	protected $_escaper;
	protected $_productFactory;
	protected $_categoryFactory;
	
	/**
	 * @param \Magento\Framework\App\Helper\Context $context
	 * @param \Magento\Framework\Escaper $escaper
	 * @param \Magento\Framework\Filter\FilterManager $filter
	 */
	public function __construct(
			\Magento\Framework\App\Helper\Context $context,
			\Magento\Framework\Escaper $escaper,
			\Magento\Framework\Filter\FilterManager $filter,
			\Magento\Catalog\Model\ProductFactory $productFactory,
			\Magento\Catalog\Model\Category $categoryFactory,
			array $data = []
			) {
				$this->_escaper = $escaper;
				$this->filter   = $filter;
				$this->_productFactory   = $productFactory;
				$this->_categoryFactory = $categoryFactory;
				parent::__construct($context,$data);
	}
	/*public function getSellerId($sellerId){
		$collections = $this->_productFactory->create ()->getCollection ();
		$collections->addAttributeToFilter ( 'seller_id', $sellerId );
		foreach($collections as $collectionV){
			$products = $this->_productFactory->create ()->load($collectionV->getEntityId());
			$cats = $products->getCategoryIds();
			foreach($cats as $k => $v){
				$sellerCategory[$collectionV->getEntityId()] = $v;
			}
		
		}
		$catgArrayUnique = array_unique($sellerCategory);
		foreach($catgArrayUnique as $catgArrayUniqueKey => $catgArrayUniqueVal){
			$cat = $this->_categoryFactory->load($catgArrayUniqueVal);
			$subcats = $cat->getChildren();
			$subcategories = array();
			foreach(explode(',',$subcats) as $subCatid){
				$_subCategory = $this->_categoryFactory->load($subCatid);
				if($_subCategory->getIsActive()) {
					$subcategories = array('id'=>$_subCategory->getId(),'name'=>$_subCategory->getName(),'product'=>$catgArrayUniqueKey);
					echo '<pre>';
					print_r($subcategories);
					echo '</pre>';
				}
			}
			
			
		}
		
		
	}*/
	
	public function getSellerId($sellerId){
		$collections = $this->_productFactory->create ()->getCollection ();
		$collections->addAttributeToFilter ( 'seller_id', $sellerId );
		foreach($collections as $collectionV){
			 $productId =  $collectionV->getEntityId();
			 $productCollection[] = $this->_productFactory->create ()->load($productId);
			 			
		}
		
		return $productCollection;
		
	
	}
	
}
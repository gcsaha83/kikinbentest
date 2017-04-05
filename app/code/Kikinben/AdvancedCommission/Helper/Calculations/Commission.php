<?php
namespace Kikinben\AdvancedCommission\Helper\Calculations;

class Commission extends \Magento\Framework\App\Helper\AbstractHelper
{
	public function __construct(
			
			\Kikinben\AdvancedCommission\Model\GlobalLevelProductTrack $commissionSave,
			\Magento\Catalog\Model\Product $product,
			\Apptha\Marketplace\Model\Seller $seller,
			\Kikinben\AdvancedCommission\Model\SellerProductCommissionFactory $SellerProductCommission,
			\Kikinben\AdvancedCommission\Model\SellerCategoryCommissionFactory $sellerCategoryCommission,
			\Kikinben\AdvancedCommission\Model\CommissionFactory $sellerCommission,
			\Magento\Catalog\Model\CategoryFactory $categoryFactory,
			\Magento\Customer\Model\Customer $sellerDetails,
			\Magento\Sales\Model\Order $order,
			\Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory,
			\Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
			\Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
			\Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurable
			
			)
	{
		
		$this->_commissionSave = $commissionSave;
		$this->_order          = $order;
		$this->_product        = $product;
		$this->_seller         = $seller;
		$this->_sellerDetails         = $sellerDetails;
		$this->_SellerProductCommission = $SellerProductCommission;
		$this->_sellerCommission = $sellerCommission;
		$this->_categoryInstance = $categoryFactory->create();
		$this->_sellerCategoryCommission = $sellerCategoryCommission;
		
		$this->productFactory = $productFactory;
		$this->productRepository = $productRepository;
		$this->dataObjectHelper = $dataObjectHelper;
		$this->_configurable=$configurable;
		
	}
	public function calculateCommissionConfig($associated_products,$allProductId,$orderId){
		
		$commission=array();
		$items = array();
		
		
		for($i=0;$i<count($associated_products);$i++){
		
			foreach($associated_products[$i] as $key => $val){
		
				$simpleProductId[] = $val['entity_id'];
		
			}
			 
		}
		$resultConfig = array_intersect($allProductId, $simpleProductId);
		
		$orderData = $this->_order->load ( $orderId );
		foreach ($orderData->getAllItems() as $item) {
			$product = $this->_product->load($item->getProductId());
			$commission[$item->getProductId ()] = [
			
					'order_id'      => $orderData->getIncrementId(),
					'name'          => $item->getName(),
					'sku'           => $item->getSku(),
					'product_price' => $item->getPrice(),
					'Qty'           => $item->getQtyOrdered(),
					'buyer_id'      => $orderData->getCustomerId(),
					'product_id'    => $item->getProductId (),
					'seller_index'  => $product->getSellerId (),
					 
			
			];
			
		}
		
		foreach($resultConfig as $resultConfigVal){
			$parentByChild = $this->_configurable->getParentIdsByChild($resultConfigVal);
			$associatedProducts[] = $resultConfigVal;
		
			if(isset($parentByChild[0])){
				$parentId = $parentByChild[0];
			}
			//calculate
		
			$productCalculation = $this->_product->load($resultConfigVal);
		
			//echo "parent id:".$parentId."Child id:".$resultConfigVal."<br/>";
		
		
			$sellerProductCollection = $this->_SellerProductCommission->create()->getCollection();
			$sellerProducts = $sellerProductCollection->addFieldToFilter('product_id',['in'=>$resultConfigVal])->getData();
		
			for($i=0;$i< count($sellerProducts);$i++){
		
				$productIdRecord  = $sellerProducts[$i]['product_id'];
				$sellerId         = $sellerProducts[$i]['seller_id'];
				if(in_array($productIdRecord,$associatedProducts)){
		
					$productOptions = $this->_product->load($productIdRecord);
					$productPrice  =  $productOptions->getPrice();
					$commissionAmount = $sellerProducts[$i]['amount'];
					$commissionType   = $sellerProducts[$i]['percentage'];
					//$commission[$productIdRecord]['seller_id'] = $sellerId;
					//$commission[$productIdRecord]['commission_amount'] = $commissionAmount;
					$commissionTypeString = ($commissionType == 2) ? 'Percentage' : 'Fixed' ;
		
					if($commissionType == 2){ // if percentage
						 
		
						$productpriceAfterCommissionPercent =  $productPrice - (($commissionAmount / 100) * $productPrice);
						$ProductchargeToSeller              =  $productPrice - $productpriceAfterCommissionPercent;
		
						$commission[$parentId]['commission'] =  [
								'PriceAfterCommission'=> $productpriceAfterCommissionPercent,
								'commissionAmount'    => $ProductchargeToSeller ,
								'commissiontype'	  => $commissionTypeString,
								'commission_value'	  => $commissionAmount ,
								'product_price' => $productOptions->getPrice(),
								'seller_index'  => $productOptions->getSellerId (),
                                'parent' =>$parentId,
                                'child'=>$resultConfigVal
		
						];
		
					}
					else if($commissionType == 1){ // fixed amount
						 
		
						$productchargeFixedAmount = $productPrice - $commissionAmount;
						$productpriceAfterCommission = $productPrice - $productchargeFixedAmount;
		
						$commission[$parentId]['commission'] =  [
								'PriceAfterCommission'=> $productchargeFixedAmount,
								'commissionAmount'    => $productpriceAfterCommission ,
								'commissiontype'	  => $commissionTypeString,
								'commission_value'	  => $commissionAmount,
								'product_price' => $productOptions->getPrice(),
								'seller_index'  => $productOptions->getSellerId (),
                                'parent' =>$parentId,
                                'child'=>$resultConfigVal
		
						];
		
					}
		
		
		
				}
		
		
			}
		
		
		
		
		}
                return $commission;
		
	}
        public function calculateCommissionSimple($productIds){
            $commission = array();
            $sellerProductCollection = $this->_SellerProductCommission->create()->getCollection();
	    $sellerProducts = $sellerProductCollection->addFieldToFilter('product_id',['in'=>$productIds])->getData();
            for($i=0;$i< count($sellerProducts);$i++){
		
				$productIdRecord  = $sellerProducts[$i]['product_id'];
				
				
		
					$productOptions = $this->_product->load($productIdRecord);
					$productPrice  =  $productOptions->getPrice();
					$commissionAmount = $sellerProducts[$i]['amount'];
					$commissionType   = $sellerProducts[$i]['percentage'];
					//$commission[$productIdRecord]['seller_id'] = $sellerId;
					//$commission[$productIdRecord]['commission_amount'] = $commissionAmount;
					$commissionTypeString = ($commissionType == 2) ? 'Percentage' : 'Fixed' ;
		
					if($commissionType == 2){ // if percentage
						 		
						$productpriceAfterCommissionPercent =  $productPrice - (($commissionAmount / 100) * $productPrice);
						$ProductchargeToSeller              =  $productPrice - $productpriceAfterCommissionPercent;
		
						$commission[$productIds]['commission'] =  [
								'PriceAfterCommission'=> $productpriceAfterCommissionPercent,
								'commissionAmount'    => $ProductchargeToSeller,
								'commissiontype'	  => $commissionTypeString,
								'commission_value'	  => $commissionAmount,
								'product_price' => $productOptions->getPrice(),
								'seller_index'  => $productOptions->getSellerId (),
                                                               
		
						];
		
					}
					else if($commissionType == 1){ // fixed amount
						 
		
						$productchargeFixedAmount = $productPrice - $commissionAmount;
						$productpriceAfterCommission = $productPrice - $productchargeFixedAmount;
		
						$commission[$productIds]['commission'] =  [
								'PriceAfterCommission'=> $productchargeFixedAmount,
								'commissionAmount'    => $productpriceAfterCommission,
								'commissiontype'	  => $commissionTypeString,
								'commission_value'	  => $commissionAmount,
								'product_price' => $productOptions->getPrice(),
								'seller_index'  => $productOptions->getSellerId (),
                                                                
		
						];
		
					}
		
			}
                        return $commission;
            
        }
        public function sellerCommission($sellerIdGlobal,$orderData){ // sellerId as array
            $commission = array();
            $items=array();
            $sellerCommissionCollection = $this->_sellerCommission->create()->getCollection();
        	$sellerCommission = $sellerCommissionCollection->addFieldToFilter('seller_id',['in'=>$sellerIdGlobal])->getData();
                
                foreach ($orderData->getAllItems() as $item) {
                    
                    $product = $this->_product->load($item->getProductId());
                    
                 $items[$item->getProductId ()] = [

                'order_id'      => $orderData->getIncrementId(),
                'name'          => $item->getName(),
                'sku'           => $item->getSku(),
                'product_price' => $item->getPrice(),
                'Qty'           => $item->getQtyOrdered(),
                'buyer_id'      => $orderData->getCustomerId(),
                'product_id'    => $item->getProductId (),
            	'seller_index'  => $product->getSellerId (),
            	
            
            ];
                }
        	
        	   if(!empty($sellerCommission)){
        	   	
        		for($i=0;$i<count($sellerCommission);$i++){        			
        			$seller_id = $sellerCommission[$i]['seller_id'];
        			$sellerDetails = $this->_seller->load($seller_id);
        			
        			foreach($items as $itemsKey => $itemsVal){
        				
        				if($itemsVal['seller_index'] == $seller_id ){
                                                
        					$product_id = $itemsVal['product_id'];
        					$qunatity   = $itemsVal['Qty'];
        					$price      = $itemsVal['product_price'];
        					$commissionType = $sellerCommission[$i]['commission_type'];
        					$amount  = $sellerCommission[$i]['amount'];
        					$range_start = $sellerCommission[$i]['uprice_range_from'];
        					$range_end   = $sellerCommission[$i]['uprice_range_to'];
        					$globalSet 	 = $sellerCommission[$i]['product_id'];
        					$orderTotal = $price * (int)$qunatity;
        					$appathaCommission = $sellerDetails->getCommission();
        					$commissionTypeString = ($commissionType == 2) ? 'Percentage' : 'Fixed' ;
        					if($globalSet == 2 ){ // price range not set
        						
        						if($commissionType == 2){ // fixed
        							
        							$priceAfterCommissionPercent =  $price - (($amount / 100) * $price);
        							$chargeToSeller              =  ($price - $priceAfterCommissionPercent)*$qunatity;
        							$commission[$product_id]['commission'] = [
        									
        									'PriceAfterCommission'=>$priceAfterCommissionPercent,
        									'commissionAmount' =>$chargeToSeller,
        									'commissiontype'	  => $commissionTypeString,
        									'commission_value'    => $amount
        							
        							];
        						}
        						else if($commissionType == 1){
        							
        							$chargeFixedAmount = $price - $amount;
        							$priceAfterCommission = ($price - $chargeFixedAmount)*$qunatity;
        							$commission[$product_id]['commission'] = [
        									
        									'PriceAfterCommission'=>$chargeFixedAmount,
        									'commissionAmount' =>$priceAfterCommission,
        									'commissiontype'	  => $commissionTypeString,
        									'commission_value'    => $amount
        							
        							];
        							
        						}
        						
        					}
        					else if($globalSet == 1){ // price range set
        						
        						$sellerProductCollectionNonRange = $this->_SellerProductCommission->create()->getCollection();
        						$sellerProductsNonRange = $sellerProductCollectionNonRange->addFieldToFilter('product_id',['in'=>$product_id])->getData();
        						
        						if(!empty($sellerProductsNonRange)){
        							
                                   if((floatval($range_start) <= floatval($orderTotal)) && (floatval($orderTotal) <= floatval($range_end))){
        							
        							if($commissionType == 2){ // percentage
        								
        								$priceAfterCommissionPercentRange =  $price - (($amount / 100) * $price);
        								$chargeToSellerRange              =  ($price - $priceAfterCommissionPercentRange)*$qunatity;
        								$commission[$product_id]['commission'] = [
        										'PriceAfterCommission'=>$priceAfterCommissionPercentRange,
        										'commissionAmount' =>$chargeToSellerRange,
        										'commissiontype'	  => $commissionTypeString,
        										'range'	=>[$range_start,$range_end],
        										'commission_value'    => $amount
        										
        								];
        								 
        							}
        							else if($commissionType == 1){// fixed
        								echo $itemsVal['seller_index'] ."==". $seller_id;
        								$chargeFixedAmountRange = $price - $amount;
        								$priceAfterCommissionRange = ($price - $chargeFixedAmountRange)*$qunatity;
        								$commission[$product_id]['commission'] = [
        										'PriceAfterCommission'=>$chargeFixedAmountRange,
        										'commissionAmount' =>$priceAfterCommissionRange,
        										'commissiontype'	  => $commissionTypeString,
        										'range'	=>[$range_start,$range_end],
        										'commission_value'    => $amount
        								
        								];
        							}
        							
        						}
        						}
        						else{
                                                            
        						if((floatval($range_start) <= floatval($orderTotal)) && (floatval($orderTotal) <= floatval($range_end))){
        							
        							if($commissionType == 2){ // percentage
        								
        								$priceAfterCommissionPercentRange =  $price - (($amount / 100) * $price);
        								$chargeToSellerRange              =  ($price - $priceAfterCommissionPercentRange)*$qunatity;
        								$commission[$product_id]['commission'] = [
        										'PriceAfterCommission'=>$priceAfterCommissionPercentRange,
        										'commissionAmount' =>$chargeToSellerRange,
        										'commissiontype'	  => $commissionTypeString,
        										'range'	=>[$range_start,$range_end],
        										'commission_value'    => $amount
        										
        								];
        								 
        							}
        							else if($commissionType == 1){// fixed
        								echo $itemsVal['seller_index'] ."==". $seller_id;
        								$chargeFixedAmountRange = $price - $amount;
        								$priceAfterCommissionRange = ($price - $chargeFixedAmountRange)*$qunatity;
        								$commission[$product_id]['commission'] = [
        										'PriceAfterCommission'=>$chargeFixedAmountRange,
        										'commissionAmount' =>$priceAfterCommissionRange,
        										'commissiontype'	  => $commissionTypeString,
        										'range'	=>[$range_start,$range_end],
        										'commission_value'    => $amount
        								
        								];
        							}
        							
        						}
        					}
        						
        					}
        					
        					
        				}
        				
        			}
        		}
        	   }
                   return $commission;
            
        }
        
        public function getCategoryCommissionGlobal($productIdCat,$orderData){
            
             foreach ($orderData->getAllItems() as $item) {
                    
                    $product = $this->_product->load($item->getProductId());
                    
                 $commission[$item->getProductId ()] = [

                'order_id'      => $orderData->getIncrementId(),
                'name'          => $item->getName(),
                'sku'           => $item->getSku(),
                'product_price' => $item->getPrice(),
                'Qty'           => $item->getQtyOrdered(),
                'buyer_id'      => $orderData->getCustomerId(),
                'product_id'    => $item->getProductId (),
            	'seller_index'  => $product->getSellerId (),
            	
            
            ];
                }
            
            $productCategory = $this->_product->load($productIdCat);
        			$categories = $productCategory->getCategoryIds();
        			foreach($categories as $categoriesK=>$categoriesV){
        				$categoryIds[] = $categoriesV;
        			}
        			$catListTop = $this->_categoryInstance->getCollection()
        			->addAttributeToSelect(array('entity_id','name','kikinben_fulfilled','kikinben_percentage_amount','kikinben_product_commission'))
        			->addAttributeToFilter('entity_id', $categoryIds)
        			->addAttributeToFilter('include_in_menu', 1)
        			->addIsActiveFilter()
        			->addAttributeToSort('position', 'asc');      
        			
        			foreach ($catListTop as $catTop){
        				 
        				$idTop    = $catTop->getEntityId();
        				$percentage_amount = $catTop->getKikinbenPercentageAmount();
        				$commissionTypeStringCatg = ($percentage_amount == 1) ? 'Percentage' : 'Fixed' ;
        				if($percentage_amount == 1){ // percentage
        					
        					$percentage_commission = $catTop->getKikinbenProductCommission();
        					
        					$product_amount = $commission[$productIdCat]['product_price'];
        					$qty			= $commission[$productIdCat]['Qty'];        					
        					$categoryCommissionPercentRange =  $product_amount - (($percentage_commission / 100) * $product_amount);
        					$categoryCommission              =  ($product_amount - $categoryCommissionPercentRange)*$qty;
        					
        					$commission[$productIdCat]['commission'] = [
        							
        							'PriceAfterCommission'=>$categoryCommissionPercentRange,
        							'commissionAmount' =>$categoryCommission,
        							'commissiontype'	  => $commissionTypeStringCatg,   
        							'commission_value'    => $percentage_commission
        					];
        					
        				}
        				else{ //fixed
        					$percentage_commission = $catTop->getKikinbenProductCommission();
        					$chargeFixedAmountRange = $product_amount - $percentage_commission;
        					$priceAfterCommissionRange = ($product_amount - $chargeFixedAmountRange)*$qty;
        					
        					$commission[$productIdCat]['commission'] = [
        							 
        							'PriceAfterCommission'=>$priceAfterCommissionRange,
        							'commissionAmount' 	  =>$chargeFixedAmountRange,
        							'commissiontype'	  => $commissionTypeStringCatg,
        							'commission_value'    => $percentage_commission
        							
        					];
        				}
        				
        				 
        			}
                                return $commission;
            
        }
        
    public function getCategoryCommissionSeller($productIdCat,$orderData){
    	
    	$commission = array();
    	
    	foreach ($orderData->getAllItems() as $item) {
    	
    		$product = $this->_product->load($item->getProductId());
    	
    		$commission[$item->getProductId ()] = [
    	
    				'order_id'      => $orderData->getIncrementId(),
    				'name'          => $item->getName(),
    				'sku'           => $item->getSku(),
    				'product_price' => $item->getPrice(),
    				'Qty'           => $item->getQtyOrdered(),
    				'buyer_id'      => $orderData->getCustomerId(),
    				'product_id'    => $item->getProductId (),
    				'seller_index'  => $product->getSellerId (),
    				 
    	
    		];
    	}
    	
    	foreach($productIdCat as $val){
    		
    		$productCategory = $this->_product->load($val);
    		$categories[] = $productCategory->getCategoryIds();
    		$seller_id[] =  $productCategory->getSellerId();
    		
    	}
    	
    	$sellerCategoryCollectionNonRange = $this->_sellerCategoryCommission->create()->getCollection();
    	$sellerProductsNonRange = $sellerCategoryCollectionNonRange
    							 ->addFieldToFilter('category_id',['in'=>$categories])
    							 ->addFieldToFilter('seller_id',['in'=>$seller_id])
    							 ->getData();
    	
    	
    	/*for($i=0;$i<count($sellerProductsNonRange);$i++){
    		foreach($sellerProductsNonRange[$i] as $rangeVal){
    			
    			$percentage_amount = $rangeVal['percentage'];
    			$amount = $rangeVal['amount'];
    			$commissionTypeStringCatg = ($percentage_amount == 1) ? 'Percentage' : 'Fixed' ;
    			if($percentage_amount == 1){
    				
    			}
    			
    		}
    	}*/
    	
    	return $commission;
    	
    }
        
	public function getSimpleFromAssociated($configProductId){
		
		$parentByChild = $this->_configurable->getParentIdsByChild($configProductId);
		
		
		if(isset($parentByChild[0])){
			$parentId = $parentByChild[0];
		}
		return $parentId;
	}
	
}
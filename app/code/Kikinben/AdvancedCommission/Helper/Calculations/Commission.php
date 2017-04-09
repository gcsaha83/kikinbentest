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
			\Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurable,
			\Magento\Store\Model\StoreManagerInterface $storeManager,
			\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollection
			
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
		$this->_storeManager = $storeManager;
		$this->_categoryCollection = $categoryCollection;
		
		$this->productFactory = $productFactory;
		$this->productRepository = $productRepository;
		$this->dataObjectHelper = $dataObjectHelper;
		$this->_configurable=$configurable;
		
	}
	public function calculateCommissionConfig($associated_products,$allProductId,$orderId){
		
		$commission=array();
		$items = array();
		$commissionFinal = array();
		
		
		for($i=0;$i<count($associated_products);$i++){
		
			foreach($associated_products[$i] as $key => $val){
		
				$simpleProductId[] = $val['entity_id'];
		
			}
			 
		}
		$resultConfig = array_intersect($allProductId, $simpleProductId);
		
		$orderData = $this->_order->load ( $orderId );
		foreach ($orderData->getAllItems() as $item) {
			$product = $this->_product->load($item->getProductId());
			$commission['simple'][$item->getProductId ()] = [
			
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
		
						$commissionFinal[$parentId] =  [
								'price_after_commission'=> $productpriceAfterCommissionPercent,
								'commission_amount_to_seller'    => $ProductchargeToSeller * $commission['simple'][$parentId]['Qty'] ,
								'commission_type'	  => $commissionTypeString,
								'commission_amount_set'	  => $commissionAmount ,
								'product_price' => $productOptions->getPrice(),
								'seller_id'  => $productOptions->getSellerId (),
                                'parent' =>$parentId,
                                'child'=>$resultConfigVal,
                                'commission_for'=>"Product_Level",
                                'order_id' =>$orderId,
                                'product_id' =>$productIdRecord,
                                'product_qty' =>$commission['simple'][$parentId]['Qty'],
                                'commission_id'=>$sellerProducts[$i]['kikinben_advancedcommission_sellerproductcommission_id']

						];
		
					}
					else if($commissionType == 1){ // fixed amount
						 
		
						$productchargeFixedAmount = $productPrice - $commissionAmount;
						$productpriceAfterCommission = $productPrice - $productchargeFixedAmount;
		
						$commissionFinal[$parentId] =  [
								'PriceAfterCommission'=> $productchargeFixedAmount,
								'commission_amount_to_seller'    => $productpriceAfterCommission * $commission['simple'][$parentId]['Qty'],
								'commission_type'	  => $commissionTypeString,
								'commission_amount_set'	  => $commissionAmount,
								'product_price' => $productOptions->getPrice(),
								'seller_id'  => $productOptions->getSellerId (),
                                'parent' =>$parentId,
                                'child'=>$resultConfigVal,
                                'commission_for'=>"Product_Level",
                                'order_id' =>$orderId,
                                'product_id' =>$productIdRecord,
                                'product_qty' =>$commission['simple'][$parentId]['Qty'],
                                'commission_id'=>$sellerProducts[$i]['kikinben_advancedcommission_sellerproductcommission_id']

		
						];
		
					}
		
		
		
				}
		
		
			}
		
		
		
		
		}
                return $commissionFinal;
		
	}
        public function calculateCommissionSimple($productIds,$orderId){
            $commission = array();            
            $commissionFinal = array();
            
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
            
            $sellerProductCollection = $this->_SellerProductCommission->create()->getCollection();
	    $sellerProducts = $sellerProductCollection->addFieldToFilter('product_id',['in'=>$productIds])->getData();
            for($i=0;$i< count($sellerProducts);$i++){
		
				$productIdRecord  = $sellerProducts[$i]['product_id'];
				
				
		
					$productOptions = $this->_product->load($productIdRecord);
					$productPrice  =  $productOptions->getPrice();
					$commissionAmount = $sellerProducts[$i]['amount'];
					$commissionType   = $sellerProducts[$i]['percentage'];
					$qty 			  = $commission[$productIds]['Qty'];
					//$commission[$productIdRecord]['seller_id'] = $sellerId;
					//$commission[$productIdRecord]['commission_amount'] = $commissionAmount;
					$commissionTypeString = ($commissionType == 2) ? 'Percentage' : 'Fixed' ;
		
					if($commissionType == 2){ // if percentage
						 		
						$productpriceAfterCommissionPercent =  $productPrice - (($commissionAmount / 100) * $productPrice);
						$ProductchargeToSeller              =  $productPrice - $productpriceAfterCommissionPercent;
						
		
						$commissionFinal[$productIds] =  [
								'price_after_commission'=> $productpriceAfterCommissionPercent,
								'commission_amount_to_seller'    => $ProductchargeToSeller * $qty,
								'commission_type'	  => $commissionTypeString,
								'commission_amount_set'	  => $commissionAmount,
								'product_price' => $productOptions->getPrice(),
								'seller_id'  => $productOptions->getSellerId (),
								'product_qty'		=> $qty,
								'order_id'	=>$commission[$productIds]['order_id'],
                                'commission_for'=>"Product_Level",
                                'commission_id' =>$sellerProducts[$i]['kikinben_advancedcommission_sellerproductcommission_id'],
                                'product_id'=>$productIdRecord
								
		
						];
		
					}
					else if($commissionType == 1){ // fixed amount
						 
		
						$productchargeFixedAmount = $productPrice - $commissionAmount;
						$productpriceAfterCommission = $productPrice - $productchargeFixedAmount;
		
						$commissionFinal[$productIds] =  [
								'price_after_commission'=> $productchargeFixedAmount,
								'commission_amount_to_seller'    => $productpriceAfterCommission * $qty,
								'commission_type'	  => $commissionTypeString,
								'commission_amount_set'	  => $commissionAmount,
								'product_price' => $productOptions->getPrice(),
								'seller_id'  => $productOptions->getSellerId (),
								'product_qty'		=> $qty,
								'order_id'	=>$commission[$productIds]['order_id'],
                                'commission_for'=>"Product_Level",
                                'commission_id' =>$sellerProducts[$i]['kikinben_advancedcommission_sellerproductcommission_id'],
                                'product_id' =>$productIdRecord
                                                                
		
						];
		
					}
		
			}
                        return $commissionFinal;
            
        }
        public function sellerCommission($sellerIdGlobal,$orderData){ // sellerId as array
            $commission = array();
            $items=array();
            /* @var mixed $commissionFinal */
            $commissionFinal = array();
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
                 
                 $commission['seller'][$item->getProductId ()] = [
                 
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
        							$commissionFinal[$product_id] = [
        									
        									'price_after_commission'=>$priceAfterCommissionPercent,
        									'commission_amount_to_seller' =>$chargeToSeller,
        									'commission_type'	  => $commissionTypeString,
        									'commission_amount_set'    => $amount,
        									'product_price' => $price,
										    'product_qty'		=> $qunatity,
										    'order_id'	=>$itemsVal['order_id'],
                                            'commission_for'=>"seller_Level",
                                            'seller_id' => $seller_id,
                                            'commission_id' =>$sellerCommission[$i]['kikinben_advancedcommission_commission_id'],
                                            'product_id' =>$product_id
        							
        							];
        						}
        						else if($commissionType == 1){
        							
        							$chargeFixedAmount = $price - $amount;
        							$priceAfterCommission = ($price - $chargeFixedAmount)*$qunatity;
        							$commissionFinal[$product_id] = [
        									
        									'price_after_commission'=>$chargeFixedAmount,
        									'commission_amount_to_seller' =>$priceAfterCommission,
        									'commission_type'	  => $commissionTypeString,
        									'commission_amount_set'    => $amount,
        									'product_price' => $price,
										    'product_qty'		=> $qunatity,
										    'order_id'	=>$itemsVal['order_id'],
                                            'commission_for'=>"seller_Level",
                                            'seller_id' => $seller_id,
                                            'commission_id' =>$sellerCommission[$i]['kikinben_advancedcommission_commission_id'],
                                            'product_id' =>$product_id
                                            
        							
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
        								$commissionFinal[$product_id] = [
        										'price_after_commission'=>$priceAfterCommissionPercentRange,
        										'commission_amount_to_seller' =>$chargeToSellerRange,
        										'commission_type'	  => $commissionTypeString,
                                                'commission_range_from'	=>$range_start,
                                                'commission_range_to'   =>$range_end,
        										'commission_amount_set'    => $amount,
        										'product_qty'	    	=> $qunatity,
										        'order_id'	=>$itemsVal['order_id'],
                                                'commission_for'=>"seller_Level",
                                                'product_price' => $price,
                                                'seller_id' => $seller_id,
                                                'commission_id' =>$sellerCommission[$i]['kikinben_advancedcommission_commission_id'],
                                                'product_id' =>$product_id

        										
        								];
        								 
        							}
        							else if($commissionType == 1){// fixed
        								$chargeFixedAmountRange = $price - $amount;
        								$priceAfterCommissionRange = ($price - $chargeFixedAmountRange)*$qunatity;
        								$commissionFinal[$product_id] = [
        										'price_after_commission'=>$chargeFixedAmountRange,
        										'commission_amount_to_seller' =>$priceAfterCommissionRange,
        										'commission_type'	  => $commissionTypeString,
        										'commission_range_from'	=>$range_start,
                                                'commission_range_to'   =>$range_end,
        										'commission_amount_set'    => $amount,
        										'product_qty'		=> $qunatity,
									        	'order_id'	=>$itemsVal['order_id'],
                                                'commission_for'=>"seller_Level",
                                                'product_price' => $price,
                                                'seller_id' => $seller_id,
                                                'commission_id' =>$sellerCommission[$i]['kikinben_advancedcommission_commission_id'],
                                                'product_id' =>$product_id

        								
        								];
        							}
        							
        						}
        						}
        						else{
                                                            
        						if((floatval($range_start) <= floatval($orderTotal)) && (floatval($orderTotal) <= floatval($range_end))){
        							
        							if($commissionType == 2){ // percentage
        								
        								$priceAfterCommissionPercentRange =  $price - (($amount / 100) * $price);
        								$chargeToSellerRange              =  ($price - $priceAfterCommissionPercentRange)*$qunatity;
        								$commissionFinal[$product_id] = [
        										'price_after_commission'=>$priceAfterCommissionPercentRange,
        										'commission_amount_to_seller' =>$chargeToSellerRange,
        										'commission_type'	  => $commissionTypeString,
										        'order_id'	=>$itemsVal['order_id'],
                                                'commission_for'=>"seller_Level",
                                                'commission_range_from'	=>$range_start,
                                                'commission_range_to'   =>$range_end,
        										'commission_amount_set'    => $amount,
        										'product_qty'		=> $qunatity,
									        	'order_id'	=>$itemsVal['order_id'],
                                                'commission_for'=>"seller_Level",
                                                'product_price' => $price,
                                                'seller_id' => $seller_id,
                                                'commission_id' =>'',
                                                'product_id' =>$product_id
        										
        								];
        								 
        							}
        							else if($commissionType == 1){// fixed
        								
        								$chargeFixedAmountRange = $price - $amount;
        								$priceAfterCommissionRange = ($price - $chargeFixedAmountRange)*$qunatity;
        								$commissionFinal[$product_id] = [
        										'price_after_commission'=>$chargeFixedAmountRange,
        										'commission_amount_to_seller' =>$priceAfterCommissionRange,
        										'commission_type'	  => $commissionTypeString,
                                                'order_id'	=>$itemsVal['order_id'],
                                                'commission_for'=>"seller_Level",
                                                'commission_range_from'	=>$range_start,
                                                'commission_range_to'   =>$range_end,
        										'commission_amount_set'    => $amount,
        										'product_qty'		=> $qunatity,
									        	'order_id'	=>$itemsVal['order_id'],
                                                'commission_for'=>"seller_Level",
                                                'product_price' => $price,
                                                'seller_id' => $seller_id,
                                                'commission_id' =>'',
                                                'product_id' =>$product_id

        								];
        							}
        							
        						}
        					}
        						
        					}
        					
        					
        				}
        				
        			}
        		}
        	   }
                   return $commissionFinal;
            
        }
        
        public function getCategoryCommissionGlobal($productIdCat,$orderData){
            
             foreach ($orderData->getAllItems() as $item) {
                    
                    $product = $this->_product->load($item->getProductId());
                    
                 $commission['category'][$item->getProductId ()] = [

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
        					
        					$product_amount = $commission['category'][$productIdCat]['product_price'];
        					$qty			= $commission['category'][$productIdCat]['Qty'];        					
        					$categoryCommissionPercentRange =  $product_amount - (($percentage_commission / 100) * $product_amount);
        					$categoryCommission              =  ($product_amount - $categoryCommissionPercentRange)*$qty;
        					
        					$commission['category'][$productIdCat]['commission'] = [
        							
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
        					
        					$commission['category'][$productIdCat]['commission'] = [
        							 
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
    	$items	    = array();
    	
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
    	
    	foreach($productIdCat as $val){
    		
    		$productCategory = $this->_product->load($val);
    		$categories = $productCategory->getCategoryIds();
    		$seller_id =  $productCategory->getSellerId();    		
    	}
    	
    	$sellerCategoryCollectionNonRange = $this->_sellerCategoryCommission->create()->getCollection();
    	$sellerProductsNonRange = $sellerCategoryCollectionNonRange
    							 ->addFieldToFilter('category_id',['in'=>$categories])
    							 ->addFieldToFilter('seller_id',['in'=>$seller_id])
    							 ->getSelect();
    	
    	
    	 
    		foreach($sellerProductsNonRange as $rangeVal){    			    			
    			
    			$percentage_amount = $rangeVal['percentage'];
    			$amount = $rangeVal['amount'];
    			$rangeSet = $rangeVal['price_range_enable']; 	
    			$commissionTypeStringCatg = ($percentage_amount == 1) ? 'Percentage' : 'Fixed' ;
    			if($rangeSet){
    				
    				if($percentage_amount == 1){
    				
    				}
    				else{
    				
    				}
    			}
    			
    			else{
    				if($percentage_amount == 1){
    				
    				}
    				else{
    				
    				}
    			
    		}
    	}
    	
    	//echo '<pre>';
    	//echo $sellerProductsNonRange;
    	//print_r($sellerProductsNonRange);
    	//print_r($categories);
    	//print_r($seller_id);
    	//print_r($productIdCat);
    	//echo '</pre>';
    	
    	//return $commission;
    	
    }
        
	public function getSimpleFromAssociated($configProductId){
		
		$parentByChild = $this->_configurable->getParentIdsByChild($configProductId);
		
		
		if(isset($parentByChild[0])){
			$parentId = $parentByChild[0];
		}
		return $parentId;
	}
	
}

<?php
namespace Kikinben\AdvancedCommission\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;


class SaveProductCommissionGlobalLevelTrack implements ObserverInterface
{
    protected $_commissionSave;
    public function __construct(
        \Kikinben\AdvancedCommission\Model\GlobalLevelProductTrack $commissionSave,
        \Magento\Sales\Model\Order $order,
        \Magento\Catalog\Model\Product $product,
        \Apptha\Marketplace\Model\Seller $seller,
        \Kikinben\AdvancedCommission\Model\SellerProductCommissionFactory $SellerProductCommission,
    	\Kikinben\AdvancedCommission\Model\CommissionFactory $sellerCommission,
    	\Magento\Catalog\Model\CategoryFactory $categoryFactory,
    	\Magento\Customer\Model\Customer $sellerDetails
    
    
    ){
        $this->_commissionSave = $commissionSave;
        $this->_order          = $order;
        $this->_product        = $product;
        $this->_seller         = $seller;
        $this->_sellerDetails         = $sellerDetails;
        $this->_SellerProductCommission = $SellerProductCommission;
        $this->_sellerCommission = $sellerCommission;
        $this->_categoryInstance = $categoryFactory->create();
    }

   /* Order of execution
    * 0)When a category has a fixed commission for whole category (set in category space)
    * 1)when a product has fixed commission in global level (catalog/product space)
    * 2)else when the product has commission set in each seller's space (marketplace mannage seller with product grid) 
    * 3)else the product has commission set for all product for specific seller (marketplace mannage seller with seller's all product)
    * 4)else category commission set for specified seller's category
    *
    * */

    public function execute(Observer $observer){

        $orderObserver = $observer->getOrderIds ();
        $orderId = $orderObserver [0];
        $orderData = $this->_order->load ( $orderId );
        
        $items=array();
        $commission = array();
        
        

        foreach ($orderData->getAllItems() as $item) {
            $productId[] = $item->getProductId ();
            $product = $this->_product->load($item->getProductId());
            $sellerIdGlobal[] = $product->getSellerId ();
            $sellerEmail = $this->_sellerDetails->load($product->getSellerId ())->getEmail();
            
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
            
            $commission[$item->getProductId ()] = [
            
            		'order_id'      => $orderData->getIncrementId(),
            		'name'          => $item->getName(),
            		'sku'           => $item->getSku(),
            		'product_price' => $item->getPrice(),
            		'Qty'           => $item->getQtyOrdered(),
            		'buyer_id'      => $orderData->getCustomerId(),
            		'product_id'    => $item->getProductId (),
            		'seller_index'  => $product->getSellerId (),
            		'seller_email'  => $sellerEmail,
            		
            
            ];

        }
        $sellerProductCollection = $this->_SellerProductCommission->create()->getCollection();
        $sellerProducts = $sellerProductCollection->addFieldToFilter('product_id',['in'=>$productId])->getData();
        
        
        for($i=0;$i< count($sellerProducts);$i++){
            $productIdRecord  = $sellerProducts[$i]['product_id'];
            $sellerId         = $sellerProducts[$i]['seller_id'];
            //$commissionFk     = $sellerProducts[$i]['id'];
            if(in_array($productIdRecord,$productId)){
            	

                $product = $this->_product->load($productIdRecord);
                $productPrice  = $product->getPrice();
                $commissionAmount = $sellerProducts[$i]['amount'];
                $commissionType   = $sellerProducts[$i]['percentage'];
                $commission[$productIdRecord]['seller_id'] = $sellerId;
                //$commission[$productIdRecord]['product_commission_global_level_id'] = $commissionFk;
                $commission[$productIdRecord]['commission_amount'] = $commissionAmount;
                $commissionTypeString = ($commissionType == 2) ? 'Percentage' : 'Fixed' ;

                if($commissionType == 2){ // if percentage
                	

                    $productpriceAfterCommissionPercent =  $productPrice - (($commissionAmount / 100) * $productPrice);
                    $ProductchargeToSeller              =  $productPrice - $productpriceAfterCommissionPercent;

                    $commission[$productIdRecord]['commission'] =  [
                        'PriceAfterCommission'=> $productpriceAfterCommissionPercent,
                        'commissionAmount'    => $ProductchargeToSeller,
                    	'commissiontype'	  => $commissionTypeString,
                    	'commission_value'	  => $commissionAmount

                    ];

                }
                else if($commissionType == 1){ // fixed amount
                	

                    $productchargeFixedAmount = $productPrice - $commissionAmount;
                    $productpriceAfterCommission = $productPrice - $productchargeFixedAmount;

                    $commission[$productIdRecord]['commission'] =  [
                        'PriceAfterCommission'=> $productchargeFixedAmount,
                        'commissionAmount'    => $productpriceAfterCommission,
                    	'commissiontype'	  => $commissionTypeString,
                    	'commission_value'	  => $commissionAmount

                    ];

                }
                
            }
            
        }
       
        	
        	$sellerCommissionCollection = $this->_sellerCommission->create()->getCollection();
        	$sellerCommission = $sellerCommissionCollection->addFieldToFilter('seller_id',['in'=>$sellerIdGlobal])->getData();
        	
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
        							//echo "Product gets fixed";
        							//echo '<pre>';
        							//print_r($sellerProductsNonRange);
        							//echo '</pre>';
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
        	  
        	
        	foreach($commission as $commissionK=>$commissionV){
        		 
        		if(!array_key_exists('commission',$commissionV)){
        			$productIdCat=$commissionV['product_id'];
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
        		}
        			
        		
        		 
        	}
        	
        	
        
        	echo '<pre>';
        	//print_r( $sellerCommission );
        	//echo "==";
        	//print_r( $items );
        	//echo "==";
        	print_r($commission);
        	echo '</pre>';

        
         
        
        //echo '<pre>';
        //print_r($items);
        //echo '</pre>';
        die;

        //return $this;


    }

    }



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
    	\Magento\Catalog\Model\CategoryFactory $categoryFactory
    
    
    ){
        $this->_commissionSave = $commissionSave;
        $this->_order          = $order;
        $this->_product        = $product;
        $this->_seller         = $seller;
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
            $items[$item->getProductId ()] = [

                'order_id'      => $orderData->getIncrementId(),
                'name'          => $item->getName(),
                'sku'           => $item->getSku(),
                'product_price' => $item->getPrice(),
                'Qty'           => $item->getQtyOrdered(),
                'buyer_id'      => $orderData->getCustomerId(),
                'product_id'    => $item->getProductId (),
            	'seller_index'  => $product->getSellerId ()	
            
            ];

        }
        $sellerProductCollection = $this->_SellerProductCommission->create()->getCollection();
        $sellerProducts = $sellerProductCollection->addFieldToFilter('product_id',['in'=>$productId])->getData();
        
        if(count($sellerProducts)){
        for($i=0;$i< count($sellerProducts);$i++){
            $productIdRecord  = $sellerProducts[$i]['product_id'];
            $sellerId         = $sellerProducts[$i]['seller_id'];
            //$commissionFk     = $sellerProducts[$i]['id'];
            if(in_array($productIdRecord,$productId)){

                $product = $this->_product->load($productIdRecord);
                $productPrice  = $product->getPrice();
                $commissionAmount = $sellerProducts[$i]['amount'];
                $commissionType   = $sellerProducts[$i]['percentage'];
                $items[$productIdRecord]['seller_id'] = $sellerId;
                $items[$productIdRecord]['product_commission_global_level_id'] = $commissionFk;
                $items[$productIdRecord]['commission_amount'] = $commissionAmount;

                if($commissionType == 2){ // if percentage

                    $priceAfterCommissionPercent =  $productPrice - (($commissionAmount / 100) * $productPrice);
                    $chargeToSeller              =  $productPrice - $priceAfterCommissionPercent;

                    $items[$productIdRecord]['commission'] =  [
                        'PriceAfterCommission'=> $priceAfterCommissionPercent,
                        'commissionAmount'    => $chargeToSeller,

                    ];

                }
                else if($commissionType == 1){ // fixed amount

                    $chargeFixedAmount = $productPrice - $commissionAmount;
                    $priceAfterCommission = $productPrice - $chargeFixedAmount;

                    $items[$productIdRecord]['commission'] =  [
                        'PriceAfterCommission'=> $chargeFixedAmount,
                        'commissionAmount'    => $priceAfterCommission,

                    ];

                }
                
            }
            
        }
        foreach($items as $itemKey => $itemValue){
        	//echo '<pre>';
        	//print_r($itemValue);
        	//echo '</pre>';
        	//$amount   = $itemValue['commission']['commissionAmount'] * (int)$itemValue['Qty'];
        	//$items[$itemKey]['finalCommission'] = $amount;
        	 
        }
        }
        else{
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
        					if($globalSet == 2 ){ // price range not set
        						
        						if($commissionType == 2){ // fixed
        							
        							$priceAfterCommissionPercent =  $price - (($amount / 100) * $price);
        							$chargeToSeller              =  ($price - $priceAfterCommissionPercent)*$qunatity;
        							$commission[$product_id] = [
        									
        									'PriceAfterCommission'=>$priceAfterCommissionPercent,
        									'commissionAmount' =>$chargeToSeller
        							
        							];
        						}
        						else if($commissionType == 1){
        							
        							$chargeFixedAmount = $price - $amount;
        							$priceAfterCommission = ($price - $chargeFixedAmount)*$qunatity;
        							$commission[$product_id] = [
        									
        									'PriceAfterCommission'=>$chargeFixedAmount,
        									'commissionAmount' =>$priceAfterCommission
        							
        							];
        							
        						}
        						
        					}
        					else if($globalSet == 1){ // price range set
        						
        						if((floatval($range_start) <= floatval($orderTotal)) && (floatval($orderTotal) >= floatval($range_end))){
        							
        							if($commissionType == 2){ // percentage
        								
        								$priceAfterCommissionPercentRange =  $price - (($amount / 100) * $price);
        								$chargeToSellerRange              =  ($price - $priceAfterCommissionPercentRange)*$qunatity;
        								$commission[$product_id] = [
        										'PriceAfterCommission'=>$priceAfterCommissionPercentRange,
        										'commissionAmount' =>$chargeToSellerRange
        										
        								];
        								 
        							}
        							else if($commissionType == 1){// fixed
        								
        								$chargeFixedAmountRange = $price - $amount;
        								$priceAfterCommissionRange = ($price - $chargeFixedAmountRange)*$qunatity;
        								$commission[$product_id] = [
        										'PriceAfterCommission'=>$chargeFixedAmountRange,
        										'commissionAmount' =>$priceAfterCommissionRange
        								
        								];
        							}
        							
        						}
        						
        					}
        					
        					//echo $seller_id.'-'.$product_id.'-'.$qunatity.'-'.$price.'-'.$amount;
        					//echo '<br>';
        				}
        				
        			}
        		}
        	   }
        	   else{ // global category
        	   	
        	   	foreach($items as $product_id => $v){
        	   		
        	   		/*$catListTop = $this->_categoryInstance->getCollection()
        	   		->addAttributeToSelect(array('entity_id','name','magic_label','url_path','magic_image','magic_thumbnail','kinkinbin_icon_thumb','image'))
        	   		->addAttributeToFilter('entity_id', $withoutParent)
        	   		->addAttributeToFilter('include_in_menu', 1)
        	   		->addIsActiveFilter()
        	   		->addAttributeToSort('position', 'asc');*/
        	   	}
        	   	
        	   }
        	
        	
        	echo '<pre>';
        	print_r( $sellerCommission );
        	echo "==";
        	print_r( $items );  
        	echo "==";
        	print_r($commission);
        	echo '</pre>'; 
        	
        }
        

        
         
        
        //echo '<pre>';
        //print_r($items);
        //echo '</pre>';
        die;

        //return $this;


    }

    }



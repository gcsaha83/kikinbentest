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
        \Apptha\Marketplace\Model\SellerFactory $seller,
        \Kikinben\AdvancedCommission\Model\SellerProductCommissionFactory $SellerProductCommission
    
    
    ){
        $this->_commissionSave = $commissionSave;
        $this->_order          = $order;
        $this->_product        = $product;
        $this->_seller         = $seller;
        $this->_SellerProductCommission = $SellerProductCommission;
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
            //$sellerId[] = $product->getSellerId ();
            $items[$item->getProductId ()] = [

                'order_id'      => $orderData->getIncrementId(),
                'name'          => $item->getName(),
                'sku'           => $item->getSku(),
                'product_price' => $item->getPrice(),
                'Qty'           => $item->getQtyOrdered(),
                'buyer_id'      => $orderData->getCustomerId(),
                'product_id'    => $item->getProductId ()
            
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
            $amount   = $itemValue['commission']['commissionAmount'] * (int)$itemValue['Qty'];
            $items[$itemKey]['finalCommission'] = $amount;
           
        } 

        return $this;


    }

    }



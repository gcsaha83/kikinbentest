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

        $order = $observer->getOrderIds ();
        $orderId = $order [0];
        $orderData = $this->_order->load ( $orderId );
        $orderItems = $orderData->getAllItems ();
        $sellerData = array ();
        $customOptions = array ();
        foreach ( $orderItems as $item ) {

            $productId = $item->getProductId ();
            $itemId = $item->getItemId ();
            $productPrice = $item->getPrice ();
            $product = $this->_product->load($productId);
            $sellerId = $product->getSellerId ();
            $sellerProductCollection = $this->_SellerProductCommission->create()->getCollection();

            $sellerData       = $this->_seller->load($sellerId);

            $sellerProducts = $sellerProductCollection->addFieldToFilter('seller_id',['eq'=>$sellerData->getId()])
                ->addFieldToFilter('product_id',['eq'=>$productId])->getData();

            for($i=0;$i< count($sellerProducts);$i++){

                $commissionAmount = $sellerProducts[$i]['amount'];
                $priceAfterCommissionPercent[] =  $productPrice - (($commissionAmount / 100) * $productPrice);


            }
               
            
        }


        echo '<pre>';
        //echo $sellerProducts->getSelect();
        print_r($sellerProducts);
        print_r($priceAfterCommissionPercent);
        echo '</pre>';
        die;


    }

	/*public function execute(Observer $observer)
    {
        $order = $observer->getOrderIds ();
        $orderId = $order [0];
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $orderDetails = $objectManager->get ( 'Magento\Sales\Model\Order' );
        $orderData = $orderDetails->load ( $orderId );
        $customerId = $orderDetails->getCustomerId ();
        $orderItems = $orderData->getAllItems ();
        $sellerData = array ();
        $customOptions = array ();
         
        foreach ( $orderItems as $item ) {
            $productId = $item->getProductId ();
            $itemId = $item->getItemId ();
            $productPrice = $item->getPrice ();
            $product = $objectManager->get ( 'Magento\Catalog\Model\Product' )->load ( $productId );
            $sellerId = $product->getSellerId ();
            if (! empty ( $sellerId ) && $item->getParentItemId () == '') {
                $sellerDatas = $objectManager->get ( 'Apptha\Marketplace\Model\Seller' )->load ( $sellerId, 'customer_id' );
                $commissionType = $product->getKikinbenPercentageAmount();
                $commissionAmount =$product->getkikinbenProductCommission();
                $fullFill         =$product->getKikinbenFulfilled();
                

                if($commissionType == 1){ // percentage commission set
                    $priceAfterCommissionPercent =  $productPrice - (($commissionAmount / 100) * $productPrice);
                    $sellerCommissionPercent =  ($commissionAmount / 100) * $productPrice;

                    $this->_commissionSave->setOrderId($orderId)
                    ->setProductId($productId)
                    ->setSellerId($sellerId)
                    ->setBuyerId($customerId)
                    ->setCommissionAmount($commissionAmount)
                    ->setKikinbenFullfiled($fullFill)
                    ->setCommissionType($commissionType);


                    $this->_commissionSave->setSellerAmount($sellerCommissionPercent)
                    ->setProductPrice($priceAfterCommissionPercent)->save();


                } 
                else{
                    $sellerCommission[] =  $commissionAmount;
                    $priceAfterCommission[] = $productPrice - $commissionAmount; 

                  //  $this->_commissionSave->setSellerAmount($sellerCommission)
                   //     ->setProductPrice($priceAfterCommission)->save();

                }
                                 
            }
            
        }
        
		return $this;
    }*/
}

<?php
namespace Kikinben\AdvancedCommission\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Kikinben\AdvancedCommission\Model\GlobalLevelProductTrack;

class SaveProductCommissionGlobalLevelTrack implements ObserverInterface
{
    protected $_commissionSave;
    public function __construct(GlobalLevelProductTrack $commissionSave){
        $this->_commissionSave = $commissionSave;
    }
	public function execute(Observer $observer)
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
                /*else{
                    $sellerCommission[] =  $commissionAmount;
                    $priceAfterCommission[] = $productPrice - $commissionAmount; 

                  //  $this->_commissionSave->setSellerAmount($sellerCommission)
                   //     ->setProductPrice($priceAfterCommission)->save();

                }*/
                                 
            }
            
        }
        
		return $this;
	}
}

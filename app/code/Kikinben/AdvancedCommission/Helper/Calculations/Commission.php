<?php
namespace Kikinben\AdvancedCommission\Helper\Calculations;

class Commission extends \Magento\Framework\App\Helper\AbstractHelper
{
	public function __construct(
			
			\Kikinben\AdvancedCommission\Model\GlobalLevelProductTrack $commissionSave,
			\Magento\Catalog\Model\Product $product,
			\Apptha\Marketplace\Model\Seller $seller,
			\Kikinben\AdvancedCommission\Model\SellerProductCommissionFactory $SellerProductCommission,
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
		
		$this->productFactory = $productFactory;
		$this->productRepository = $productRepository;
		$this->dataObjectHelper = $dataObjectHelper;
		$this->_configurable=$configurable;
		
	}
	public function calculateCommissionConfig($associated_products,$allProductId){
		
		$commission=array();
		for($i=0;$i<count($associated_products);$i++){
		
			foreach($associated_products[$i] as $key => $val){
		
				$simpleProductId[] = $val['entity_id'];
		
			}
			 
		}
		$resultConfig = array_intersect($allProductId, $simpleProductId);
		
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
					$commission[$productIdRecord]['seller_id'] = $sellerId;
					$commission[$productIdRecord]['commission_amount'] = $commissionAmount;
					$commissionTypeString = ($commissionType == 2) ? 'Percentage' : 'Fixed' ;
		
					if($commissionType == 2){ // if percentage
						 
		
						$productpriceAfterCommissionPercent =  $productPrice - (($commissionAmount / 100) * $productPrice);
						$ProductchargeToSeller              =  $productPrice - $productpriceAfterCommissionPercent;
		
						$commission[$parentId]['commission'] =  [
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
		
						$commission[$parentId]['commission'] =  [
								'PriceAfterCommission'=> $productchargeFixedAmount,
								'commissionAmount'    => $productpriceAfterCommission,
								'commissiontype'	  => $commissionTypeString,
								'commission_value'	  => $commissionAmount,
								'product_price' => $productOptions->getPrice(),
								'seller_index'  => $productOptions->getSellerId (),
		
						];
		
					}
		
		
		
				}
		
		
			}
		
		
		
		
		}
		
	}
	public function getSimpleFromAssociated($configProductId){
		
		$parentByChild = $this->_configurable->getParentIdsByChild($configProductId);
		$associatedProducts[] = $configProductId;
		
		if(isset($parentByChild[0])){
			$parentId = $parentByChild[0];
		}
		return $parentId;
	}
	
}
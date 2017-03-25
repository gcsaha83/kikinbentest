<?php
namespace Kikinben\AdvancedCommission\Block\Onepage;

use Magento\Customer\Model\Context;
use Magento\Sales\Model\Order;

/**
 * One page checkout success page
 */
class Success extends \Magento\Framework\View\Element\Template
{
	/**
	 * @var \Magento\Checkout\Model\Session
	 */
	protected $_checkoutSession;

	/**
	 * @var \Magento\Sales\Model\Order\Config
	 */
	protected $_orderConfig;

	/**
	 * @var \Magento\Framework\App\Http\Context
	 */
	protected $httpContext;

	/**
	 * @param \Magento\Framework\View\Element\Template\Context $context
	 * @param \Magento\Checkout\Model\Session $checkoutSession
	 * @param \Magento\Sales\Model\Order\Config $orderConfig
	 * @param \Magento\Framework\App\Http\Context $httpContext
	 * @param array $data
	 */
	public function __construct(
			\Magento\Framework\View\Element\Template\Context $context,
			\Magento\Checkout\Model\Session $checkoutSession,
			\Magento\Sales\Model\Order\Config $orderConfig,
			\Magento\Framework\App\Http\Context $httpContext,
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
            \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurable,
			array $data = []
			) {
				parent::__construct($context, $data);
				$this->_checkoutSession = $checkoutSession;
				$this->_orderConfig = $orderConfig;
				$this->_isScopePrivate = true;
				$this->httpContext = $httpContext;
				
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

	/**
	 * Render additional order information lines and return result html
	 *
	 * @return string
	 */
	public function getAdditionalInfoHtml()
	{
		return $this->_layout->renderElement('order.success.additional.info');
	}

	/**
	 * Initialize data and prepare it for output
	 *
	 * @return string
	 */
	protected function _beforeToHtml()
	{
		$this->prepareBlockData();
		return parent::_beforeToHtml();
	}

	/**
	 * Prepares block data
	 *
	 * @return void
	 */
	protected function prepareBlockData()
	{
		$order = $this->_checkoutSession->getLastRealOrder();

		$this->addData(
				[
						'is_order_visible' => $this->isVisible($order),
						'view_order_url' => $this->getUrl(
								'sales/order/view/',
								['order_id' => $order->getEntityId()]
								),
						'print_url' => $this->getUrl(
								'sales/order/print',
								['order_id' => $order->getEntityId()]
								),
						'can_print_order' => $this->isVisible($order),
						'can_view_order'  => $this->canViewOrder($order),
						'order_id'  => $order->getIncrementId()
				]
				);
	}

	/**
	 * Is order visible
	 *
	 * @param Order $order
	 * @return bool
	 */
	protected function isVisible(Order $order)
	{
		return !in_array(
				$order->getStatus(),
				$this->_orderConfig->getInvisibleOnFrontStatuses()
				);
	}

	/**
	 * Can view order
	 *
	 * @param Order $order
	 * @return bool
	 */
	protected function canViewOrder(Order $order)
	{
		return $this->httpContext->getValue(Context::CONTEXT_AUTH)
		&& $this->isVisible($order);
	}
	
	public function getOrderItems($orderId){

        $commission=array();

		$orderData = $this->_order->load ( $orderId );
		
        foreach($orderData->getItems() as $allItems){

            $allProductId[] = $allItems->getProductId(); 


        }

        foreach ($orderData->getAllVisibleItems() as $item) {

            $product_id = $item->getProductId();
			$product = $this->_product->load($item->getProductId());
			
            if($product->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE){
                $associated_products[] = $this->_configurable->getUsedProductCollection($product)->addAttributeToSelect('*')->addFilterByRequiredOptions()->getData();
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

                               
                
			}//config products ends
						
        }
         for($i=0;$i<count($associated_products);$i++){

             foreach($associated_products[$i] as $key => $val){

                 $simpleProductId[] = $val['entity_id'];


             }
             
         }
        $resultConfig = array_intersect($allProductId, $simpleProductId);

        foreach($resultConfig as $resultConfigVal){
            $parentByChild = $this->_configurable->getParentIdsByChild($resultConfigVal);

            if(isset($parentByChild[0])){
                $parentId = $parentByChild[0];          
            }
            //calculate

            $productCalculation = $this->_product->load($resultConfigVal);

            
            


        }
                echo '<pre>';
                print_r($commission);
                //print_r($simpleProductId);
                //print_r($allProductId);
                
                
                echo '</pre>';
	 //return $items;
    }

    


}

<?php
namespace Kikinben\Sslcommerz\Block;

class Payment extends \Magento\Backend\Block\Template
{
	protected $_helper;
	protected $_checkoutSession;
	protected $_storeManager;
	protected $_urlInterface;
	protected $response;
	protected $_responseFactory;
	
	public function __construct(
			\Magento\Backend\Block\Template\Context $context,
			\Kikinben\Sslcommerz\Helper\Data $helper,
			\Magento\Checkout\Model\Session $checkoutSession,
			\Magento\Sales\Model\Order $order,
			\Magento\Checkout\Model\Cart $cart,
			\Magento\Store\Model\StoreManagerInterface $storeManager,
			\Magento\Framework\UrlInterface $urlInterface,
			\Magento\Framework\App\Response\Http $response,
			\Magento\Framework\App\ResponseFactory $responseFactory,
			
			array $data = []
			) {
		
				$this->_storeManager = $storeManager;
				$this->_helper = $helper;
				$this->_checkoutSession = $checkoutSession;
				$this->_order           = $order;
				$this->_cart = $cart;
				$this->_urlInterface = $urlInterface;
				$this->response = $response;
				$this->_responseFactory = $responseFactory;
				
		
				parent::__construct($context, $data);
	}
	public function getStoreUrl(){
		
		return $this->_storeManager->getStore()->getBaseUrl();
	}
	public function getOrderDetails(){						
		$fields = array();
		$serverType = array('1'=>'https://sandbox.sslcommerz.com/gwprocess/v3/api.php',
				'2'=>'https://securepay.sslcommerz.com/gwprocess/v3/api.php');
		
		$fields_string = "";
		
		$marchent_id = $this->_helper->getConfigData('store_id');
		$storePasswd = $this->_helper->getConfigData('validation_password');
		$envType = $this->_helper->getConfigData('env');
		$order_id = $this->_checkoutSession->getLastRealOrderId(); 
		$url = ($envType == 1) ? $serverType[2] : $serverType[1] ;
		
		$url_success = $this->_urlInterface->getUrl($this->getStoreUrl()."checkout/onepage/success",array('_secure'=>true));
		$url_fail 	 = $this->_urlInterface->getUrl($this->getStoreUrl()."sslcommerz/index/notify",array('_secure'=>true));
		$url_cancel  = $this->_urlInterface->getUrl($this->getStoreUrl()."sslcommerz/index/cancel",array('_secure'=>true));
		
		
		if (!empty($order_id)) {
			//$order = $this->_order->load ( $order_id );
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$order = $objectManager->create('\Magento\Sales\Api\Data\OrderInterface')->loadByIncrementId($order_id);

			$items = $this->_cart->getQuote()->getAllVisibleItems();
			$totalItems = $this->_cart->getQuote()->getItemsCount();
			$totalQuantity = $this->_cart->getQuote()->getItemsQty();
			$billingaddress = $order->getBillingAddress();
			$shippingaddress = $order->getShippingAddress();
			$address = $billingaddress->getStreet();
			$address1 = $shippingaddress->getStreet();
			 
			foreach($order->getAllVisibleItems() as $itemId => $item){
		
				$name[]		 = $item->getName();
				$unitPrice[] =$item->getPrice();
				$sku[]		 =$item->getSku();
				$ids[]		 =$item->getProductId();
				$qty[]		 =$item->getQtyToInvoice();
			}
			$productname = implode(',',$name);
			$productunitPrice= implode(',',$unitPrice);
			$productsku= implode(',',$sku);
			$productids= implode(',',$ids);
			$productqty= implode(',',$qty);
			$desc = 'Product Name: '.$productname.'@ Product Sku: '.$productsku.'@ Product Quantity: '. $productqty.'@ Product Price: '.$productunitPrice;
		}
		$fields = array(
				'store_id' => $marchent_id,
				'store_passwd' =>$storePasswd,
				'total_amount' => $order->getGrandTotal(),				
				'currency' => $this->_storeManager->getStore()->getCurrentCurrencyCode(),
				'tran_id' => $order_id,
				'cus_name' => $order->getCustomerFirstname().' '.$order->getCustomerLastname(),
				'cus_email' => $order->getCustomerEmail(),
				'cus_add1' => $address[0],
				'cus_add2' => (isset($address[1])) ? $address[1] : "" ,
				'cus_city' => $billingaddress->getCity(),
				'cus_state' => $billingaddress->getRegion(),
				'cus_postcode' => $billingaddress->getPostcode(),
				'cus_country' => $billingaddress->getCountryId(),
				'cus_phone' => $billingaddress->getTelephone(),
				'cus_fax' => 'NotApplicable',
				'ship_name' => $shippingaddress->getCustomerFirstname().' '.$shippingaddress->getCustomerLastname(),
				'ship_add1' => $address1[0],
				'ship_add2' => (isset($address1[1])) ? $address1[1] : ""  ,
				'ship_city' => $shippingaddress->getCity(),
				'ship_state' => $shippingaddress->getRegion(),
				'ship_postcode' => $shippingaddress->getPostcode(),
				'ship_country' => $shippingaddress->getCountryId(),
				'success_url' => $url_success,
				'fail_url' => $url_fail,
				'cancel_url' => $url_cancel,
				
		
		);		
		$return = $this->_helper->curl_post_wrapper($url,$fields);
		
		if($return['status'] === 'SUCCESS'){
			$gatwayUrl = $return['GatewayPageURL'];
			$this->response->setRedirect($gatwayUrl);
		}
		else{
			$RedirectUrl= $this->_urlInterface->getUrl('sslcommerz/index/notify');
			$this->_responseFactory->create()->setRedirect($RedirectUrl)->sendResponse();
		}
		return $return;
		
	}
	
	function _prepareLayout(){}
}

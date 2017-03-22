<?php

namespace Kikinben\Sslcommerz\Controller;
abstract class Index extends \Magento\Framework\App\Action\Action
{
	protected $_helper;
	protected $_checkoutSession;
	protected $_storeManager;
	protected $_urlInterface;
	protected $resultPageFactory;

    
    public function __construct(
        \Magento\Framework\App\Action\Context $context, 
    	\Magento\Framework\View\Result\PageFactory $resultPageFactory,
    	\Kikinben\Sslcommerz\Helper\Data $helper,
    	\Magento\Checkout\Model\Session $checkoutSession,
    	\Magento\Sales\Model\Order $order,
    	\Magento\Checkout\Model\Cart $cart,
    	\Magento\Store\Model\StoreManagerInterface $storeManager,
    	\Magento\Framework\UrlInterface $urlInterface
    ) {
    	$this->_storeManager = $storeManager;
    	$this->_helper = $helper;    
    	$this->_checkoutSession = $checkoutSession;
    	$this->_order           = $order;
    	$this->_cart = $cart;
    	$this->_urlInterface = $urlInterface;
    	$this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
	
    }
    public function execute()
    {
    	
    	/*$fields = array();
    	$serverType = array('1'=>'https://sandbox.sslcommerz.com/gwprocess/v3/process.php',
    			'2'=>'https://sandbox.sslcommerz.com/gwprocess/v3/process.php');
    	
    	
    	$marchent_id = $this->_helper->getConfigData('store_id');
    	$storePasswd = $this->_helper->getConfigData('validation_password');
    	$envType = $this->_helper->getConfigData('env');
    	$order_id = $this->_checkoutSession->getLastRealOrderId();
    	$url = ($envType == 1) ? $serverType[2] : $serverType[1] ; 
    	
    	$url_success = $this->_urlInterface->getUrl($this->getStoreUrl()."sslcommerz/index/success",array('_secure'=>true));
    	$url_fail 	 = $this->_urlInterface->getUrl($this->getStoreUrl()."sslcommerz/index/notify",array('_secure'=>true));
    	$url_cancel  = $this->_urlInterface->getUrl($this->getStoreUrl()."sslcommerz/index/cancel",array('_secure'=>true));
    	
    	
    	if (!empty($order_id)) {
    		$order = $this->_order->load ( $order_id );
    		$items = $this->_cart->getQuote()->getAllVisibleItems();
    		$totalItems = $this->_cart->getQuote()->getItemsCount();
    		$totalQuantity = $this->_cart->getQuote()->getItemsQty();
    		$billingaddress = $order->getBillingAddress();
    		$shippingaddress = $order->getShippingAddress();
    		$address = $billingaddress->getStreet();
    		$address1 = $shippingaddress->getStreet();
    		
    		foreach($items as $itemId => $item){
    			
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
    			'total_amount' => $this->_cart->getQuote()->getBaseGrandTotal(),
    			'currency' => $currencyDesc,
    			'tran_id' => $order_id,
    			'cus_name' => $order->getCustomerFirstname().' '.$order->getCustomerLastname(),
    			'cus_email' => $order->getCustomerEmail(),
    			'cus_add1' => $address[0],
    			'cus_add2' => $address[1],
    			'cus_city' => $billingaddress->getCity(),
    			'cus_state' => $billingaddress->getRegion(),
    			'cus_postcode' => $billingaddress->getPostcode(),
    			'cus_country' => $billingaddress->getCountryId(),
    			'cus_phone' => $billingaddress->getTelephone(),
    			'cus_fax' => 'NotApplicable',
    			'ship_name' => $shippingaddress->getCustomerFirstname().' '.$shippingaddress->getCustomerLastname(),
    			'ship_add1' => $address1[0],
    			'ship_add2' => $address1[1],
    			'ship_city' => $shippingaddress->getCity(),
    			'ship_state' => $shippingaddress->getRegion(),
    			'ship_postcode' => $shippingaddress->getPostcode(),
    			'ship_country' => $shippingaddress->getCountryId(),
    			'success_url' => $url_success,
    			'fail_url' => $url_fail,
    			'cancel_url' => $url_cancel
    	
    	);
    	$security_key = $this->_helper->sslcommerz_hash_key($storePasswd,$fields);
    	
    	$fields['verify_sign'] = $security_key['verify_sign'];
    	$fields['verify_key'] = $security_key['verify_key'];
    	
    	foreach($fields as $key => $val){
    		$fields_string .= $key.'='.$value.'&';    		
    	}
    	rtrim($fields_string, '&');    	
    	$return = $this->_helper->curl_post_wrapper($url,$fields_string,$fields);*/
    	     	   	
    }
    public function getStoreUrl()
    {
    	return $this->_storeManager->getStore()->getBaseUrl();
    }
}

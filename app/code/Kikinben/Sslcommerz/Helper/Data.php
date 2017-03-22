<?php

namespace Kikinben\Sslcommerz\Helper;


class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	protected $_scopeConfig;
	
	
	public function __construct(			
			\Magento\Framework\App\Helper\Context $context,
			\Magento\Framework\App\Config\ScopeConfigInterface $scopeInterface,			
			\Magento\Framework\App\State $state
			
			)
	{		
		$this->_scopeConfig = $scopeInterface;
		
		
		parent::__construct($context);
	}
	public function getConfigData($field,$storeId = null) {	
		
				$path = 'payment/sslcommerz/' . $field;
				return $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
	}
	public function sslcommerz_hash_key($store_passwd="", $parameters=array()) {
	
		$return_key = array(
				"verify_sign"	=>	"",
				"verify_key"	=>	""
		);
		if(!empty($parameters)) {
			$parameters['store_passwd'] = md5($store_passwd);
			ksort($parameters);
			$hash_string="";
			$verify_key = "";	
			foreach($parameters as $key=>$value) {
				$hash_string .= $key.'='.($value).'&';
				if($key!='store_passwd') {
					$verify_key .= "{$key},";
				}
			}
			$hash_string = rtrim($hash_string,'&');
			$verify_key = rtrim($verify_key,',');
			$verify_sign = md5($hash_string);
			$return_key['verify_sign'] = $verify_sign;
			$return_key['verify_key'] = $verify_key;
		}
		return $return_key;
	}
	public function curl_Api_wrapper($direct_api_url,$post_data){		
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, $direct_api_url );
		curl_setopt($handle, CURLOPT_TIMEOUT, 10);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($handle, CURLOPT_POST, 1 );
		curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		$content = curl_exec($handle );
		$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		
		if($code == 200 && !( curl_errno($handle))) {
			curl_close( $handle);
			$sslcommerzResponse = $content;
		} else {
			curl_close( $handle);
			echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
			exit;
		}
		$sslcz = json_decode($sslcommerzResponse, true );
		return $sslcz; 
		
	}
	public function curl_post_wrapper($url,$fields_string){				
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);				
		$content = curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if($code == 200 && !( curl_errno($ch))) {			
			curl_close( $ch);
			$sslcommerzResponse = $content;			
		} else {
			curl_close( $ch);
			echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
			exit;
		}
		$sslcz = json_decode($sslcommerzResponse, true );
		return $sslcz;
		
	}
	
    
}

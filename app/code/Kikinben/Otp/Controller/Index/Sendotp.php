<?php
namespace Kikinben\Otp\Controller\Index;


use Magento\Framework\Controller\ResultFactory;


class Sendotp extends \Magento\Framework\App\Action\Action
{
    protected $_cacheTypeList;
    protected $_cacheState;
    protected $_cacheFrontendPool;
    protected $_resultPageFactory;
    protected $_storeManager;
    protected $_customerSession;
    protected $_scopeConfig;

     public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession,
     	\Magento\Framework\App\Config\ScopeConfigInterface $scopeInterface
     	
    ) {
        parent::__construct($context);
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_storeManager = $storeManager;
        $this->_customerSession = $customerSession;
        $this->_scopeConfig = $scopeInterface;
        
    }
    public function execute()
    {
       $xmlStructure = array();	
       $smsUserName = $this->_scopeConfig->getValue('twiliosetting/frontend_display/account_sid', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
       $smsUserPasswd = $this->_scopeConfig->getValue('twiliosetting/frontend_display/auth_token', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
       $smsSid = $this->_scopeConfig->getValue('twiliosetting/frontend_display/twilio_number', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
       //echo $smsUserName."==".$smsUserPasswd."==".$smsSid.'<br/>'; die;
       $resultRedirect = $this->resultFactory->create(
            ResultFactory::TYPE_REDIRECT);
        $params = $this->getRequest()->getParams();
        $mobileNumber = str_replace(' ', '',
            $params['mobilenumber']);

        //contus code start

        //$first_name = str_replace(' ','', $params['fname']);
        //$last_name  = str_replace(' ','',$params['lname']);


        //contus code end

        if (empty($mobileNumber) ||
            !isset($mobileNumber) || $mobileNumber == "") {
            $this->messageManager->addError('Mobile Number not valid');
            $resultRedirect->setUrl(
                $this->_storeManager->getStore()
                    ->getBaseUrl() . 'customer/account/create/');
        } else {
            $objectManager = \Magento\Framework\App\ObjectManager
                ::getInstance();
            $customers = $objectManager->create(
                'Magento\Customer\Model\Customer')
                ->getCollection()
                ->addFieldToFilter('mobilenumber',
                    [
                        'like' => '%' . $mobileNumber . '%'
                    ]);
if (!count($customers)) {
    try {
                    $otp = rand(100000, 999999);
                    $modelOtp = $this->_objectManager->create(
                        'Ipragmatech\Registration\Model\Otp')
                        ->getCollection()
                        ->addFieldToFilter('mobile_number',
                            [
                                'like' => '%' . $mobileNumber . '%'
                            ]);

if (count($modelOtp)) {
    $modelId = '';
    foreach ($modelOtp as $model) {
                            $modelId = $model->getId();

    }
    $modelOtp = $this->_objectManager->create(
        'Ipragmatech\Registration\Model\Otp')->load($modelId);
    $modelOtp->setOtp($otp);
    $modelOtp->save();
} else {
                        $modelOtp = $this->_objectManager->create(
                            'Ipragmatech\Registration\Model\Otp');
                        $modelOtp->setOtp($otp);
                        $modelOtp->setCustomerId(0);
                        $modelOtp->setMobileNumber($mobileNumber);
                        $modelOtp->setStatus(0);
                        //contus code start

                        //$modelOtp->setFname($first_name);
                        //$modelOtp->setLname($last_name);

                        //contus code end
                        $modelOtp->save();
}
                    // send OTP on mobile code
                    $fromNumber = $mobileNumber; // '+91 88020 45390';
                    $message = "Your Kikinben one time password is " . $otp;

try {
                        $to = str_replace('+', '', $fromNumber);                    
                        //$api = 'http://api.bulksms.top/2/?user=kikinben&pass=kikinben2016&to='.$to.'&sender=Kikinben&message='.urlencode($message);
                        //$sms = file_get_contents($api);
                        $url="http://sms.sslwireless.com/pushapi/dynamic/server.php";
                        $param="user=".$smsUserName."&pass=".$smsUserPasswd."&sms[0][0]=".$to."&sid=".$smsSid."&sms[0][1]=".urlencode($message);
                        
                        $crl = curl_init();
                        curl_setopt($crl,CURLOPT_SSL_VERIFYPEER,FALSE);
                        curl_setopt($crl,CURLOPT_SSL_VERIFYHOST,2);
                        curl_setopt($crl,CURLOPT_URL,$url);
                        curl_setopt($crl,CURLOPT_HEADER,0);
                        curl_setopt($crl,CURLOPT_RETURNTRANSFER,1);
                        curl_setopt($crl,CURLOPT_POST,1);
                        curl_setopt($crl,CURLOPT_POSTFIELDS,$param);
                        $response = curl_exec($crl);
                        curl_close($crl);
                        //echo $response;die;
                        $xml = simplexml_load_string($response);
                        $json = json_encode($xml);
                        $smsArray = json_decode($json,TRUE);
                        
                        
                        
                        if($smsArray['PARAMETER']==="OK" && 
                        		$smsArray['LOGIN'] ==="SUCESSFULL" && 
                        		$smsArray['PUSHAPI'] === 'ACTIVE' &&
                        		$smsArray['STAKEHOLDERID'] === 'OK' &&
                        		$smsArray['PERMITTED'] === 'OK'
                        		
                        		)
                        {
                        	if($smsArray['SMSINFO']['REFERENCEID']){
                        		
                        		$this->_customerSession
                        		->setMobileNumber($mobileNumber);
                        		$resultRedirect->setUrl(
                        				$this->_storeManager->getStore()
                        				->getBaseUrl() . 'registration/index/index');
                        		
                        	}
                        	else{
                        		$this->messageManager->addError('We can\'t send the otp, something went wrong');
                        		$resultRedirect->setUrl($this->_redirect->getRefererUrl());
                        	}
                        	
                        }
                        else{                        	
                        	$this->messageManager->addError('We can\'t send the otp, something went wrong');
                        	$resultRedirect->setUrl($this->_redirect->getRefererUrl());
                        }
                        	
                        
                        
                        
                       /* $this->_customerSession
                            ->setMobileNumber($mobileNumber);
                        $resultRedirect->setUrl(
                            $this->_storeManager->getStore()
                                ->getBaseUrl() . 'registration/index/index');*/
} catch (\Exception $e) {
                        $this->messageManager->addException($e,
                            __('We can\'t send the otp, 
                            something went wrong'));
                        $resultRedirect->setUrl(
                            $this->_redirect->getRefererUrl());
}
    } catch (\Exception $e) {
                    $this->messageManager->addException($e,
                        __('We can\'t send the otp, something went wrong'));
    }
} else {
                $this->messageManager->addError('Mobile Number already exist');
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
}
        }
        return $resultRedirect; 


    }
    





}

<?php
namespace Kikinben\Otp\Controller\Index;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\AccountManagement;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\SecurityViolationException;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Ipragmatech\Registration\Model\Otp;
use Magento\Customer\Model\Customer;

class Forgotpassword extends \Magento\Framework\App\Action\Action
{
   public function __construct(
        Context $context,
        Session $customerSession,
        AccountManagementInterface $customerAccountManagement,
        CustomerRepositoryInterface $customerRepositoryInterface,
        Otp $otp,
        Customer $customer,
        Escaper $escaper
    ) {
        $this->session = $customerSession;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->escaper = $escaper;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->_otp = $otp;
        $this->_customer = $customer;
        parent::__construct($context);
   }

   /**
     * Forgot customer password action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $email = (string)$this->getRequest()->getPost('email');
        $phone = (string)$this->getRequest()->getPost('mobilenumber');
       
        if ($email) {

            if (!\Zend_Validate::is($email, 'EmailAddress')) {
                $this->session->setForgottenEmail($email);
                $this->messageManager->addErrorMessage(__('Please correct the email address.'));
                return $resultRedirect->setPath('*/*/forgotpassword');
            }

            try {
                $this->customerAccountManagement->initiatePasswordReset(
                    $email,
                    AccountManagement::EMAIL_RESET
                );
            } catch (NoSuchEntityException $exception) {
                // Do nothing, we don't want anyone to use this action to determine which email accounts are registered.
            } catch (SecurityViolationException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
                return $resultRedirect->setPath('*/*/forgotpassword');
            } catch (\Exception $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    __('We\'re unable to send the password reset email.')
                );
                return $resultRedirect->setPath('*/*/forgotpassword');
            }
            $this->messageManager->addSuccessMessage($this->getSuccessMessage($email));
            return $resultRedirect->setPath('*/*/');
        }
       
         /* 
          * get email with mobile number
          *
          * */
        //contus code starts
        
        else if($phone){
            
            $mobileNumber = $this->_customer->getCollection()->addFieldToFilter('mobilenumber',
                            [
                                'like' =>  $phone 
                            ]);
            
            foreach($mobileNumber as $model){
                 $customerId = $model->getId(); 
                 $mobile     = $model->getMobilenumber();
                 $customerEmail   = $model->getEmail();
            }
            if(isset($customerId)){
                $modelOtp = $this->_otp->getCollection()->addFieldToFilter('mobile_number',[
                                'like' => '%' . $mobile . '%'
                            ]
                        );

                $otp = rand(100000, 999999);
                $fromNumber = $phone;
                $message = "Your One Time Password is " . $otp;

                try{
                    $to = str_replace('+', '', $fromNumber);
                    $api = 'http://api.bulksms.top/2/?user=kikinben&pass=kikinben2016&to='.$to.'&sender=kikinben&message='.urlencode($message);
                    $sms = file_get_contents($api);
                    $this->session->setMobilenumber($fromNumber);
                    $this->session->setForgottenEmail($customerEmail);
                    return $resultRedirect->setPath('*/*/forgotpassword');

            }catch(\Exception $exception){

            }
                
            }else{
                $this->messageManager->addErrorMessage(__('Phone number does not match.'));
                return $resultRedirect->setPath('*/*/forgotpassword');
            }
            
        }
        //contus code ends

         else {
            $this->messageManager->addErrorMessage(__('Please enter your email.'));
            return $resultRedirect->setPath('*/*/forgotpassword');
        }
       
    }

    /**
     * Retrieve success message
     *
     * @param string $email
     * @return \Magento\Framework\Phrase
     */
    protected function getSuccessMessage($email)
    {
        return __(
            'If there is an account associated with %1 you will receive an email with a link to reset your password.',
            $this->escaper->escapeHtml($email)
        );
    }



}

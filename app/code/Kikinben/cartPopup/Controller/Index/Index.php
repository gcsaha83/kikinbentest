<?php
namespace Kikinben\cartPopup\Controller\Index;
class Index extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;        
        return parent::__construct($context);
    }
    
    public function execute()
    {
	$data = $this->getRequest()->getParams();
	$productId = $data['id'];
        $result                 = $this->resultPageFactory->create();
	$block = $result->getLayout()
              ->createBlock('Kikinben\cartPopup\Block\Product\View','kikinben_cartpopup_block_popup_new', ['data' => ['product_id' => $productId]])
                ->setTemplate('Kikinben_cartPopup::popup.phtml')
                ->toHtml();
        $this->getResponse()->setBody($block);
        
    }
}

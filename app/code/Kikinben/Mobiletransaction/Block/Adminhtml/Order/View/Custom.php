<?php
namespace Kikinben\Mobiletransaction\Block\Adminhtml\Order\View;

class Custom extends \Magento\Backend\Block\Template
{
	protected $registry;
		
	public function __construct(
			\Magento\Backend\Block\Template\Context $context,
			\Magento\Framework\Registry $registry,
			array $data = []
			) {
				$this->registry = $registry;
				parent::__construct($context, $data);
	}
	
	/**
	 * @return \Magento\Sales\Model\Order\Payment
	 */
	public function getPayment()
	{
		$order = $this->registry->registry('current_order');
		return $order->getPayment();
	}

}

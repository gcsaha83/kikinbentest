<?php
namespace Kikinben\AdvancedCommission\Ui\Component\Listing\DataProviders\Kikinben\Advancedcommission\Sellerproduct;

use Magento\Framework\Data\OptionSourceInterface;
use Kikinben\AdvancedCommission\Model\SellerProductCommissionFactory;

use Magento\Framework\View\Model\PageLayout\Config\BuilderInterface;

class KikinbenFulfilledOptions implements OptionSourceInterface
{
	protected $options;
	protected $_commissionModel;
	
	public function __construct(SellerProductCommissionFactory $commissionModel)
	{
		$this->_commissionModel = $commissionModel;
	}
	public function toOptionArray()
	{
		$options[] = array('label'=>"Yes",'value'=>"1");
		$options[] = array('label'=>"No",'value'=>"2");
		$this->options = $options;
		
		return $this->options;
		
	}
}
<?php
namespace Kikinben\AdvancedCommission\Ui\Component\Listing\Column\Kikinbenadvancedcommissioncommissiontrackid;
use Magento\Framework\Escaper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;


class CommissionCharge extends Column
{
	protected $escaper;
	protected $_customer;
	public function __construct(
			ContextInterface $context,
			UiComponentFactory $uiComponentFactory,
			Escaper $escaper,
			array $components = [],
			array $data = []
			
			){
				$this->escaper = $escaper;
				parent::__construct($context, $uiComponentFactory, $components, $data);
				
	}
	
	/**
	 * Prepare Data Source
	 *
	 * @param array $dataSource
	 * @return array
	 */
	public function prepareDataSource(array $dataSource)
	{
		if (isset($dataSource['data']['items'])) {
			foreach ($dataSource['data']['items'] as & $item) {
				$item[$this->getData('name')] = $this->prepareItem($item);
			}
		}
		
		return $dataSource;
	}
	protected function prepareItem(array $item)
	{
		return $type = $item['commission_amount_to_seller'];
		
	}
	
	
}
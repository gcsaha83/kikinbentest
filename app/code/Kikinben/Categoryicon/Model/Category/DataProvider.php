<?php

namespace Kikinben\Categoryicon\Model\Category; 

class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{
	protected function addUseDefaultSettings($category, $categoryData)
	{
    	$data = parent::addUseDefaultSettings($category, $categoryData);
 
    	if (isset($data['kinkinbin_icon_thumb'])) {
            unset($data['kinkinbin_icon_thumb']);
 
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $helper        = $objectManager->get('\Kikinben\Categoryicon\Helper\Data');
 
            $data['kinkinbin_icon_thumb'][0]['name'] = $category->getData('kinkinbin_icon_thumb');
            $data['kinkinbin_icon_thumb'][0]['url']  = $helper->getCategoryThumbUrl($category);
    	}
 
    	return $data;
	}
 
	protected function getFieldsMap()
	{
    	$fields = parent::getFieldsMap();
        $fields['content'][] = 'kinkinbin_icon_thumb'; // NEW FIELD
    	
    	return $fields;
	}
}

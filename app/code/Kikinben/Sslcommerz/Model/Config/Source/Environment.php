<?php

namespace Kikinben\Sslcommerz\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Environment implements OptionSourceInterface
{

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $options[] = [
            'label' => __('Real'),
            'value' => 0,
        ];
        $options[] = [
            'label' => __('Test'),
            'value' => 1,
        ];        
        return $options;
    }
}

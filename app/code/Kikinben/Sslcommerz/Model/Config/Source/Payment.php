<?php

namespace Kikinben\Sslcommerz\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Payment implements OptionSourceInterface
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
            'label' => __('TODO'),
            'value' => 0,
        ];
        
        return $options;
    }
}

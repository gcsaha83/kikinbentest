<?php

namespace Kikinben\Sslcommerz\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Transaction implements OptionSourceInterface
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
            'label' => __('Test Url'),
            'value' => 0,
        ];
        $options[] = [
            'label' => __('Live Url'),
            'value' => 1,
        ];
        return $options;
    }
}

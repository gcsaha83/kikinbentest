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
            'label' => __('Authorization'),
            'value' => 0,
        ];
        $options[] = [
            'label' => __('Capture'),
            'value' => 1,
        ];
        return $options;
    }
}

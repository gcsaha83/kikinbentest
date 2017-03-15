<?php

namespace Kikinben\Sslcommerz\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Currency implements OptionSourceInterface
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
            'label' => __('EURO'),
            'value' => 'EUR',
        ];
        $options[] = [
            'label' => __('DOLAR'),
            'value' => 'DOL',
        ];
        return $options;
    }
}

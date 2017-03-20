<?php

namespace Kikinben\Sslcommerz\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Payment implements OptionSourceInterface
{
   
    public function toOptionArray()
    {
        $options = [];
        $options[] = [
            'label' => __('Todos'),
            'value' => 0,
        ];
        $options[] = [
            'label' => __('Solo tarjeta'),
            'value' => 1,
        ];
        $options[] = [
            'label' => __('Tarjeta y Iupay'),
            'value' => 2,
        ];
        return $options;
    }
}

<?php

namespace Kikinben\Sslcommerz\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;


final class ConfigProvider implements ConfigProviderInterface
{

    const CODE = 'sslcommerz';

    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    'transactionResults' => [
                        1 => __('Success'),
                        0 => __('Fraud')
                    ]
                ]
            ]
        ];
    }
}

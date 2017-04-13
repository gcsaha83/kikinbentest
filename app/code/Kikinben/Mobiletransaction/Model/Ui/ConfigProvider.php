<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Kikinben\Mobiletransaction\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Kikinben\Mobiletransaction\Gateway\Http\Client\ClientMock;

/**
 * Class ConfigProvider
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'sample_gateway';

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    'transactionResults' => [                        
			'Bkash'=>__('Bkash'),
			'Rocket'=>__('Rocket')
                    ],
		   
                ],
            'mobileNumbers' =>'',
            'transactionIds'=>''
            ]
        ];
    }
}

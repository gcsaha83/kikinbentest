<?php

namespace Kikinben\Sslcommerz\Logger;

use Monolog;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    protected $loggerType = Logger::INFO;    
    protected $fileName = '/var/log/Kikinben_Sslcommerz.log';
}

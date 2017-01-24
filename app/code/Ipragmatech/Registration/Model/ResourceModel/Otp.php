<?php

/** 
 * Copyright © 2016 iPragmatech solutions. All rights reserved.
 * @author: Ajay Mehta (iPragmatech solutions ) 
 **/
namespace Ipragmatech\Registration\Model\ResourceModel;

class Otp extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('ipragmatech_otp', 'id');
    }
}

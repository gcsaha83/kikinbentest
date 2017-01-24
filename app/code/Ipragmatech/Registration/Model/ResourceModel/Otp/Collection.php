<?php

/**
 * Copyright © 2016 iPragmatech solutions. All rights reserved.
 * @author: Ajay Mehta (iPragmatech solutions )
 **/
namespace Ipragmatech\Registration\Model\ResourceModel\Otp;

class Collection extends
    \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Initialize resource collection
     *
     * @return void
     *
     */
    public function _construct()
    {
        $this->_init('Ipragmatech\Registration\Model\Otp',
            'Ipragmatech\Registration\Model\ResourceModel\Otp');
    }
}

<?php

/**
 * Apptha
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.apptha.com/LICENSE.txt
 *
 * ==============================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * ==============================================================
 * This package designed for Magento COMMUNITY edition
 * Apptha does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Apptha does not provide extension support in case of
 * incorrect edition usage.
 * ==============================================================
 *
 * @category    Apptha
 * @package     Apptha_Marketplace
 * @version     1.1
 * @author      Apptha Team <developers@contus.in>
 * @copyright   Copyright (c) 2016 Apptha. (http://www.apptha.com)
 * @license     http://www.apptha.com/LICENSE.txt
 *
 */
namespace Apptha\Marketplace\Controller\Adminhtml\Review;

use Apptha\Marketplace\Controller\Adminhtml\Review;

/**
 * This class contains the seller review grid functionality
 */
class Grid extends Review {
    /**
     * Prepare seller review collection
     */
    protected function _prepareCollection() {
        /**
         * Get collection
         */
        $collection = $this->_gridFactory->create ()->getCollection ();
        /**
         * Set collection
         */
        $this->setCollection ( $collection );
        parent::_prepareCollection ();
        /**
         * Return object
         */
        return $this;
    }
    /**
     *
     * @return void
     */
    public function execute() {
        /**
         * Create factory for review
         */
        return $this->_resultPageFactory->create ();
    }
}

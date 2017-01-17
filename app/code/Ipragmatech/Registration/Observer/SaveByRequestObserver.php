<?php
namespace Ipragmatech\Registration\Observer;

use Magento\Framework\Event\ObserverInterface;

class SaveByRequestObserver implements ObserverInterface
{

    /**
     *
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_objectManager = $objectManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //code goes here
    }
}

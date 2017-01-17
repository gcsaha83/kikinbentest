<?php
namespace Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Back;

/**
 * Interceptor class for @see \Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Back
 */
class Interceptor extends \Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Back implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\UiComponent\Context $context, \Magento\Framework\Registry $registry)
    {
        $this->___init();
        parent::__construct($context, $registry);
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getButtonData');
        if (!$pluginInfo) {
            return parent::getButtonData();
        } else {
            return $this->___callPlugins('getButtonData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($route = '', $params = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getUrl');
        if (!$pluginInfo) {
            return parent::getUrl($route, $params);
        } else {
            return $this->___callPlugins('getUrl', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProduct()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProduct');
        if (!$pluginInfo) {
            return parent::getProduct();
        } else {
            return $this->___callPlugins('getProduct', func_get_args(), $pluginInfo);
        }
    }
}

<?php
namespace Magento\Catalog\Block\Product\ImageBlockBuilder;

/**
 * Interceptor class for @see \Magento\Catalog\Block\Product\ImageBlockBuilder
 */
class Interceptor extends \Magento\Catalog\Block\Product\ImageBlockBuilder implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\ConfigInterface $presentationConfig, \Magento\Catalog\Model\View\Asset\ImageFactory $viewAssetImageFactory, \Magento\Catalog\Block\Product\ImageFactory $imageBlockFactory, \Magento\Catalog\Model\Product\Image\ParamsBuilder $imageParamsBuilder, \Magento\Catalog\Model\Product\Image\SizeCache $sizeCache)
    {
        $this->___init();
        parent::__construct($presentationConfig, $viewAssetImageFactory, $imageBlockFactory, $imageParamsBuilder, $sizeCache);
    }

    /**
     * {@inheritdoc}
     */
    public function buildBlock($product, $displayArea)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'buildBlock');
        if (!$pluginInfo) {
            return parent::buildBlock($product, $displayArea);
        } else {
            return $this->___callPlugins('buildBlock', func_get_args(), $pluginInfo);
        }
    }
}

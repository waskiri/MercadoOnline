<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Block\Form\Fieldset;


/**
 * Seller new product form block
 *
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Image extends \Magento\Framework\View\Element\Template
{



    /**
     * @var array config
     */
    protected $layoutProcessors;


    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $layoutProcessors
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $layoutProcessors = [],
        array $data = []
    ) {
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->layoutProcessors = $layoutProcessors;
        parent::__construct($context);
    }


    /**
     * @return string
     */
    public function getJsLayout(){
        foreach ($this->layoutProcessors as $processor){
            $this->jsLayout = $processor->process($this->jsLayout);
        }
        return \Zend_Json::encode($this->jsLayout);
    }


}

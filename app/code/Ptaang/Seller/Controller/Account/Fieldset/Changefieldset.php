<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Controller\Account\Fieldset;


class Changefieldset extends \Magento\Framework\App\Action\Action {

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $_resultRawFactory;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    protected $_collectionAttributeFactory;

    /**
     * ChangeFieldset constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\Json\Helper\Data $helper
     * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionAttributeFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\Json\Helper\Data $helper,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionAttributeFactory ){

        $this->_collectionAttributeFactory = $collectionAttributeFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_resultRawFactory  = $resultRawFactory;
        $this->_helper            = $helper;

        return parent::__construct($context);
    }
    /**
    */
    public function execute(){
        $attributeRequest = null;
        $httpBadRequestCode = 400;

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->_resultRawFactory->create();

        try{
            $attributeRequest = $this->_helper->jsonDecode($this->getRequest()->getContent());
        } catch(\Exception $e){
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }

        if (!$attributeRequest || $this->getRequest()->getMethod() !== 'POST' || !$this->getRequest()->isXmlHttpRequest()) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }
        $response = [];
        $response["error"] = true;
        $response["attributes"] = [];
        $attributeSetId = isset($attributeRequest["setAttributeId"])?
                                    $attributeRequest["setAttributeId"] : 0;
        /** Get the additional attributes of the attribute set */
        if($attributeSetId > 0){
            $response["error"] = false;
            $response["attributes"] = $this->getAttributesGivenAttributeSetId($attributeSetId);
        }

        $result = $this->_resultJsonFactory->create();
        return $result->setData($response);
    }

    /**
     * Get attributes of and attribute set id, but returns the attributes not included in the deafult
     * @param int $attributeSetId
     * @return array
     */
    public function getAttributesGivenAttributeSetId($attributeSetId){

        /** Load the default attributes  */
        $attributesDefault = [];
        $nodeChildren = $this->_collectionAttributeFactory->create()->setAttributeSetFilter(
            \Ptaang\Seller\Constant\Product::ATTRIBUTE_SET_ID
        )->addVisibleFilter()->load();
        foreach ($nodeChildren->getItems() as $child) {
            $attributeData = $child->getData();
            array_push($attributesDefault, $attributeData["attribute_code"]);
        }

        /** Load the different attributes of default */
        $attributes = [];
        $nodeChildren = $this->_collectionAttributeFactory->create()->setAttributeSetFilter(
            $attributeSetId
        )->addVisibleFilter()->load();
        foreach ($nodeChildren->getItems() as $child) {
            $attributeData = $child->getData();
            $attributeCode = $attributeData["attribute_code"];
            if(!in_array($attributeCode, $attributesDefault)){
                array_push($attributes, $attributeData);
            }
        }
        return $attributes;
    }
}
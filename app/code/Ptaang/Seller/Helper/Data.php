<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Helper;



class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    CONST XML_PATH_TOKEN = "seller/api/key";

    /**
     * @var \Ptaang\Seller\Model\Seller
     */
    protected $seller;


    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    protected $_collectionAttributeFactory;


    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Ptaang\Seller\Model\SellerFactory $sellerFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionAttributeFactory){
        $this->_collectionAttributeFactory = $collectionAttributeFactory;
        $this->seller = $sellerFactory;
        parent::__construct($context);
    }

    /**
     * Get the tocken for use the Rest of Magento
     * @return string
     */
    public function getToken(){
        return $this->scopeConfig->getValue(
            self::XML_PATH_TOKEN,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Seller Id given a customer Id
     * @param int $customerId
     * @return int $sellerId
     */
    public function getSellerId($customerId){
        $sellerId =  null;
        $seller = $this->seller->create();
        $seller = $seller->loadByCustomerId($customerId);
        if($seller && $seller->getId()){
            $sellerId = $seller->getId();
        }
        return $sellerId;
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
                if($attributeData['frontend_input'] == "select"){
                    $attributeData["options"] = $child->getSource()->getAllOptions();
                }
                array_push($attributes, $attributeData);
            }
        }
        return $attributes;
    }
}
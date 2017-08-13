<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Helper;



class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    CONST XML_PATH_TOKEN = "seller/api/key";


    CONST XML_PATH_SENDER_SOLD_PRODUCT = "seller/email_sold/sender_email";
    CONST XML_PATH_EMAIL_SOLD_PRODUCT = "seller/email_sold/email_template";

    /**
     * @var \Ptaang\Seller\Model\Seller
     */
    protected $seller;


    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    protected $_collectionAttributeFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Customer\Model\GroupFactory
     */
    protected $_groupFactory;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Ptaang\Seller\Model\SellerFactory $sellerFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionAttributeFactory
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Ptaang\Seller\Model\SellerFactory $sellerFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionAttributeFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\GroupFactory $groupFactory){
        $this->_collectionAttributeFactory = $collectionAttributeFactory;
        $this->seller = $sellerFactory;
        $this->_customerSession = $customerSession;
        $this->_groupFactory = $groupFactory;
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
     * Get Email Template when a product is sold
     * @return string
     */
    public function getTemplateEmailSoldProduct(){
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_SOLD_PRODUCT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get sender of the Email
     * @return string
     */
    public function getSenderEmailSoldProduct(){
        return $this->scopeConfig->getValue(
            self::XML_PATH_SENDER_SOLD_PRODUCT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if the Customer is Seller
     * @return boolean
     */
    public function isSeller(){
        $isSeller = false;
        if($this->_customerSession->getCustomer() &&
            $customerId = $this->_customerSession->getCustomer()->getId()){
            $sellerId = $this->getSellerId($customerId);
            if($sellerId && $sellerId > 0) $isSeller = true;
        }
        return $isSeller;
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
     * Get Group Id
     * @return string group
     */
    public function getGroupName(){
        $groupCode = "";
        if($this->_customerSession->getCustomer() &&
            $customer = $this->_customerSession->getCustomer()){
            $groupId = $customer->getGroupId();
            $groupCode = $this->_groupFactory->create()->load($groupId)->getCode();
        }
        return $groupCode;
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
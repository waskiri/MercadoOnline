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
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Ptaang\Seller\Model\SellerFactory $sellerFactory){

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
}
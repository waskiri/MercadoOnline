<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Model\Seller;

class Product extends \Magento\Framework\Model\AbstractModel {

    /**
     * Initialize Seller model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ptaang\Seller\Model\ResourceModel\Seller\Product');
    }

    /**
     * Load the seller_product entity given a customerId and customerId
     * @param int $sellerId
     * @param int $productId
     * @return \Ptaang\Seller\Model\Seller\Product
     */
    public function loadBySellerProduct($sellerId, $productId){
        $this->_getResource()->loadBySellerProduct($this, $sellerId, $productId);
        return $this;
    }
}
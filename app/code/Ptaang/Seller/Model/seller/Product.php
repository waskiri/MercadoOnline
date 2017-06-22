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
     * Load the Seller given a customerId
     * @param int $customerId
     * @param int $productId
     * @return \Ptaang\Seller\Model\Seller\Product
     */
    public function loadBySellerProduct($customerId, $productId){
        $this->_getResource()->loadBySellerProduct($this, $customerId, $productId);
        return $this;
    }
}
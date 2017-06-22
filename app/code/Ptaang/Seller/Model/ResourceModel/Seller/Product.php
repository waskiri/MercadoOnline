<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Model\ResourceModel\Seller;

class Product extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('seller_product', 'entity_id');
    }
    /**
     * Load the seller_product entity given a customerId and customerId
     * @param \Ptaang\Seller\Model\Seller\Product $sellerProduct
     * @param int $customerId
     * @param int $productId
     * @return \Ptaang\Seller\Model\Seller\Product
     */

    public function loadBySellerProduct(\Ptaang\Seller\Model\Seller\Product $sellerProduct, $customerId)
    {
        $connection = $this->getConnection();
        $bind = ['customer_id' => $customerId];
        $select = $connection->select()->from(
            $this->getMainTable()
        )->where(
            'customer_id = :customer_id'
        );

        $sellerProductId = $connection->fetchOne($select, $bind);
        if ($sellerProductId) {
            $this->load($sellerProduct, $sellerProductId);
        } else {
            $sellerId->setData([]);
        }
        return $this;
    }

}
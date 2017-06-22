<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Model\ResourceModel;

class Seller extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('seller_entity', 'entity_id');
    }

    /**
     * Load the Seller given a customerId
     * @param \Ptaang\Seller\Model\Seller $seller
     * @param int $customerId
     * @return \Ptaang\Seller\Model\Seller
     */
    public function loadByCustomerId(\Ptaang\Seller\Model\Seller $seller, $customerId)
    {
        $connection = $this->getConnection();
        $bind = ['customer_id' => $customerId];
        $select = $connection->select()->from(
            $this->getMainTable()
        )->where(
            'customer_id = :customer_id'
        );

        $sellerId = $connection->fetchOne($select, $bind);
        if ($sellerId) {
            $this->load($seller, $sellerId);
        } else {
            $sellerId->setData([]);
        }
        return $this;
    }
}
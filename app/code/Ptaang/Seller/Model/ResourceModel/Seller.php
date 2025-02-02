<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Model\ResourceModel;

//use Ptaang\Seller\Model\ActiveSeller;

class Seller extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    
    protected $_request;   
    
    protected $_activeSeller;
    
    /**
     * Resource initialization
     *
     * @return void
     */
    public function _construct()
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
            $seller->setData([]);
        }
        return $this;
    }
}
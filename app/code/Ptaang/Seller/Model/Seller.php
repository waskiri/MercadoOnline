<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Model;

class Seller extends \Magento\Framework\Model\AbstractModel {

    /**
     * Initialize Seller model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ptaang\Seller\Model\ResourceModel\Seller');
    }

    /**
     * Load the Seller given a customerId
     * @param int $customerId
     * @return \Ptaang\Seller\Model\Seller
     */
    public function loadByCustomerId($customerId){
        $this->_getResource()->loadByCustomerId($this, $customerId);
        return $this;
    }
}
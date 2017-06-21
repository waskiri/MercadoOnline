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


}
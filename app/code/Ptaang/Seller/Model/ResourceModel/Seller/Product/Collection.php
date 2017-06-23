<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Model\ResourceModel\Seller;

/**
 * Seller collection
 *
 */

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
   /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Ptaang\Seller\Model\Seller\Product',
            'Ptaang\Seller\Model\ResourceModel\Seller\Product'
        );
    }
}
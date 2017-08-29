<?php

/**
 * Magestore.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Storepickup
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
namespace Magestore\Storepickup\Block\Adminhtml\Store;

/**
 * @category Magestore
 * @package  Magestore_Storepickup
 * @module   Storepickup
 * @author   Magestore Developer
 */
class Grid extends \Magestore\Storepickup\Block\Adminhtml\Widget\Grid
{
    /**
     * get selected row values.
     *
     * @return array
     */
    public function getSelectedRows()
    {
        $selectedStores = $this->_converter->toFlatArray(
            $this->_storepickupHelper->getTreeSelectedStores()
        );

        return array_values($selectedStores);
    }
}

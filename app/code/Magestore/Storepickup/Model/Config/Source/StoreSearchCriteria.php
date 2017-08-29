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

namespace Magestore\Storepickup\Model\Config\Source;

/**
 * @category Magestore
 * @package  Magestore_Storepickup
 * @module   Storepickup
 * @author   Magestore Developer
 */
class StoreSearchCriteria implements \Magento\Framework\Option\ArrayInterface
{
    const SEARCH_CRITERIA_NONE = 0;
    const SEARCH_CRITERIA_STORE_NAME = 1;
    const SEARCH_CRITERIA_COUNTRY = 2;
    const SEARCH_CRITERIA_STATE = 3;
    const SEARCH_CRITERIA_CITY = 4;
    const SEARCH_CRITERIA_ZIPCODE = 5;

    /**
     * Options getter.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::SEARCH_CRITERIA_NONE, 'label' => __('None')],
            ['value' => self::SEARCH_CRITERIA_STORE_NAME, 'label' => __('Store Name')],
            ['value' => self::SEARCH_CRITERIA_COUNTRY, 'label' => __('Country')],
            ['value' => self::SEARCH_CRITERIA_STATE, 'label' => __('State/ Province')],
            ['value' => self::SEARCH_CRITERIA_CITY, 'label' => __('City')],
            ['value' => self::SEARCH_CRITERIA_ZIPCODE, 'label' => __('Zip Code')],
        ];
    }

    /**
     * Get options in "key-value" format.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            self::SEARCH_CRITERIA_NONE => __('None'),
            self::SEARCH_CRITERIA_STORE_NAME => __('Store Name'),
            self::SEARCH_CRITERIA_COUNTRY => __('Country'),
            self::SEARCH_CRITERIA_STATE => __('State/ Province'),
            self::SEARCH_CRITERIA_CITY => __('City'),
            self::SEARCH_CRITERIA_ZIPCODE => __('Zip Code'),
        ];
    }
}

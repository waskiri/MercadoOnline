/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
        'jquery',
        'mage/translate',
        'ko'
], function ($, $t,ko) {
    return {
        formLoader: ko.observable(false),
        skuLoader: ko.observable(false),

        /** Sku Field*/
        showSkuWarning: ko.observable(false),
        skuWarning: ko.observable(""),
        skuValue: ko.observable(""),

        /** Category Field */
        categoryList: ko.observableArray([]),
        categorySelected: ko.observable(""),
        showCategoryList: ko.observable(false),

        /** Images */
        imagesFile: ko.observableArray([]),

        /** Product type Select */
        productTypes: ko.observableArray([]),
        selectedProductType: ko.observable(),

        /** Additional Attributes of the product */
        attributes: ko.observableArray([])
    }
});


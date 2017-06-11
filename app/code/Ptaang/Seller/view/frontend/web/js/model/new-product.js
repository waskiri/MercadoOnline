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
        categoryList: ko.observableArray([]),
        categorySelected: ko.observable(""),
        showCategoryList: ko.observable(false),
        imagesFile: ko.observableArray([]),
        productTypes: ko.observableArray([]),
        selectedProductType: ko.observable()
    }
});


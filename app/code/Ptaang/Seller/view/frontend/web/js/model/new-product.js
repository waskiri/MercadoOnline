define([
        'jquery',
        'mage/translate',
        'ko'
], function ($, $t,ko) {
    return {
        categoryList: ko.observableArray([]),
        categorySelected: ko.observable(""),
        showCategoryList: ko.observable(false),
        imagesFile: ko.observableArray([])
    }
});


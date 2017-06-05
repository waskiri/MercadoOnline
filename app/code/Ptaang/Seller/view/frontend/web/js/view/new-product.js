define([
    'ko',
    'uiComponent',
    'jquery',
    'Ptaang_Seller/js/model/new-product',
    'mage/translate'

], function (ko,Component, $ , newProduct, $t) {
    'use strict';

    return Component.extend({
        defaults: {
            categoryList: []
        },
        newProduct: newProduct,
        initObservable: function () {
            this._super().observe([]);
            this.newProduct.categoryList(this.categoryList);
            return this;
        },
        showCategoryList: function () {
            if(newProduct.showCategoryList()){
                newProduct.showCategoryList(false);
            }else{
                newProduct.showCategoryList(true);
            }
        },


    });
});
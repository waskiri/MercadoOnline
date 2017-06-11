/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'ko',
    'uiComponent',
    'Ptaang_Seller/js/model/new-product'

], function (ko, Component, newProduct) {
    'use strict';

    return Component.extend({

        newProduct: newProduct,
        addCategory: function(categoryId){
            var categoryString = newProduct.categorySelected();
            var categoryArray  = categoryString.length ===0 ? [] : categoryString.split(",");
            var i = categoryArray.indexOf(categoryId);
            /** Check the value of checkbox and save in the input */
            if( i != -1) {
                categoryArray.splice(i , 1);
            }else{
                categoryArray.push(categoryId);
            }
            newProduct.categorySelected(categoryArray.join(","));
            newProduct.showCategoryList(false);
            return true;
        }
    });
});
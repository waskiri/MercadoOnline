/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'ko',
    'uiComponent',
    'jquery',
    'mage/translate',
    'Ptaang_Seller/js/model/new-product',
    'Ptaang_Seller/js/action/change-attribute-set'

], function (ko,Component, $ , $t, newProduct, changeAttributeSet) {
    'use strict';

    return Component.extend({
        defaults: {
            categoryList: [],
            productTypes: []
        },
        newProduct: newProduct,

        /** This method populate the Field and initialize the observers */
        initObservable: function () {
            this._super().observe([]);
            /** populate category List */
            this.newProduct.categoryList(this.categoryList);

            /** populate the product Type Select */
            var ProductType = function(attribute_set_id, attribute_set_name) {
                this.id = attribute_set_id;
                this.name = attribute_set_name;
            };
            for(var i in this.productTypes){
                var productType = this.productTypes[i];
                this.newProduct.productTypes.push(
                    new ProductType(
                            productType.attribute_set_id,
                            productType.attribute_set_name)
                        );
            }
            return this;
        },

        /** Open and close the category List */
        showCategoryList: function () {
            if(newProduct.showCategoryList()){
                newProduct.showCategoryList(false);
            }else{
                newProduct.showCategoryList(true);
            }
        },

        /** Change the fieldset of the product */
        selectProductType: function(){
            var setAttributeId = this.newProduct.selectedProductType();
            if(setAttributeId > 0){
                newProduct.formLoader(true);
                /** Call the controller of changefieldset */
                changeAttributeSet(setAttributeId);
            }
        },

        /** Extract the data of the form and send it to the controller of create product */
        createProduct: function(createProductForm){
            var self = this;
            var createProductData = {},
                formDataArray = $(createProductForm).serializeArray();

            formDataArray.forEach(function (entry) {
                createProductData[entry.name] = entry.value;
            });
            console.log(createProductData);
            /** Call the controller of create product*/
            if ($(createProductForm).validation() && $(createProductForm).validation('isValid')) {
                //newProduct.formLoader(true);
                /*newsletterAction(newsletterData, true).always(function() {
                    self.isLoadingNewsletter(false);
                });*/
            }
        }


    });
});
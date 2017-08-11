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
    'Ptaang_Seller/js/action/change-attribute-set',
    'Ptaang_Seller/js/action/create-product',
    'Ptaang_Seller/js/action/search-sku'

], function (ko,Component, $ , $t, newProduct, changeAttributeSet, createProduct, searchSku) {
    'use strict';
    productId: productId;
    return Component.extend({
        defaults: {
            categoryList: [],
            productTypes: [],
            categorySelected: "",
            categoryArraySelected: [],
            imagesFile: []
        },
        newProduct: newProduct,

        /** This method populate the Field and initialize the observers */
        initObservable: function () {
            this._super().observe([]);
            /** populate category List */
            this.newProduct.categoryList(this.categoryList);
            this.newProduct.categorySelected(this.categorySelected);
            this.newProduct.categoryArraySelected(this.categoryArraySelected);

            /** populate the product gallery */
            this.newProduct.imagesFile(this.imagesFile);

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

            var createProductData = {}, customAttributesArray = [], extensionAttributes= {},
                formDataArray = $(createProductForm).serializeArray();

            /** Retrieve the values from the form */
            /** Regular Expressions for retrieve the value from the form */
            var expCustomAttr = /^custom_attributes\[/,
                expReplaceCustom = /^custom_attributes\[|\]$/gi,
                expExtensionAttr = /^extension_attributes\[/,
                expReplaceExt = /^extension_attributes\[|\]$/gi;

            /** Sort the attributes */
            formDataArray.forEach(function (entry) {
                var attributeCode = entry.name;
                if(expCustomAttr.exec(attributeCode)){
                    customAttributesArray.push({
                        attribute_code: attributeCode.replace(expReplaceCustom, ""), value: entry.value
                    });
                }else if(expExtensionAttr.exec(attributeCode)) {

                    if(attributeCode.replace(expReplaceExt, "") == "qty"){
                        extensionAttributes = {
                            stockItem : {
                                qty: entry.value,
                                isInStock: true
                            }
                        };
                        if(productId != 0){
                            extensionAttributes.stockItem.productId = productId;
                        }
                    }

                }else{
                    if(attributeCode == "category_ids"){
                        createProductData[attributeCode] = (entry.value).split(",");
                    }else{
                        createProductData[attributeCode] = entry.value;
                    }

                }
            });
            /** Generate the URL of the Product, because I got an error when send empty */
            if(createProductData.hasOwnProperty("sku") && createProductData.hasOwnProperty("name")){
                var url = this.getUrlProduct(createProductData["sku"], createProductData["name"]);
                customAttributesArray.push({
                    attribute_code: "url_key", value: url
                });
            }

            var gallery = this.getGallery();
            /** Call the controller of create product*/
            if ($(createProductForm).validation() && $(createProductForm).validation('isValid')) {
                newProduct.formLoader(true);
                createProduct(createProductData, customAttributesArray, extensionAttributes, gallery);
            }
        },
        /** Get the Gallery Details for sending through  by REST */
        getGallery: function(){
            var images = newProduct.imagesFile(), entries = [];
            for(var i in images){
                var image = images[i], imageType = image.type;
                entries[i] = {
                    position: i,
                    media_type: 'image',
                    disabled: false,
                    types: i == 0 ? ['image','small_image','thumbnail'] : [],
                    content: {
                        type: imageType,
                        name: image.name
                    }
                };
                if(image.entry_id == 0){
                    entries[i].content.base64_encoded_data = image.src.replace("data:"+ imageType+";base64,","")
                }else{
                    entries[i].id = image.entry_id;
                    entries[i].file = image.path;
                }
            }
            return entries;
        },

        /** Generate the Url of the Product Given a Name and an SKU */
        getUrlProduct: function(name, sku){
            var nameProduct = name.trim(), skuProduct = sku.trim();
            return nameProduct.toLowerCase()+"-"+skuProduct.toLowerCase();
        },

        /** Search Sku of the product*/
        searchSku: function(){
            newProduct.skuLoader(true);
            searchSku(newProduct.skuValue());
        },

        /** Add message css class */
        messageType: ko.computed(function(){
            var classVariable = "success";
            if(newProduct.messageError()){
                classVariable = "error";
            }
            return classVariable;
        })
    });
});
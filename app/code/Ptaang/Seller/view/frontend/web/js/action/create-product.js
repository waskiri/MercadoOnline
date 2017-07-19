
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'Ptaang_Seller/js/lib/storage',
        'Ptaang_Seller/js/model/url-builder',
        'Ptaang_Seller/js/model/new-product',
        'mage/translate'
    ],
    function (storage, urlBuilder, newProduct, $t) {
        'use strict';

        return function (createProductForm, customAttributes, extensionAttributes, gallery) {
            /** prepare the object for being send it by REST */
            var productObject = { product : createProductForm };
            productObject.product.extension_attributes = extensionAttributes;
            productObject.product.custom_attributes =  customAttributes;
            /** Add gallery */
            if(gallery.length > 0){
                productObject.product.media_gallery_entries = gallery;
            }

            var messageSuccess = function(){
                newProduct.messageError(false);
                newProduct.messageText($t("Create the product successfully"));
            };
            var messageError = function(){
                newProduct.messageError(true);
                newProduct.messageText($t("Error creating or updating the product"));
            };

            return storage.put(
                        urlBuilder.createUrl('/products/'+createProductForm.sku, {}),
                        JSON.stringify(productObject),
                        false
                    ).done(
                        function (response) {
                            newProduct.messagesVisible(true);
                            /** if have property Id the product has been Created successfully */
                            if(response.hasOwnProperty("id")){
                                var idProduct = response.id, sellerId = urlBuilder.getSellerId();
                                /** call the controller */
                                storage.post(
                                    'seller/account/saveproduct',
                                    JSON.stringify({
                                        productId: idProduct,
                                        sellerId: sellerId
                                    }),
                                    false
                                ).done(
                                    function (response) {
                                        messageSuccess();
                                        newProduct.formLoader(false);
                                    }
                                ).fail(
                                    function () {
                                        messageError();
                                        newProduct.formLoader(false);
                                    }
                                );
                            /** End call to the second controller */
                            }else{
                                messageError();
                                newProduct.formLoader(false);
                            }
                        }
                    ).fail(
                        function () {
                            messageError();
                            newProduct.formLoader(false);
                        }
                    );

        };
    }
);

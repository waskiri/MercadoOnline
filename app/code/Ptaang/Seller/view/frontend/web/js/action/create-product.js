
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'Ptaang_Seller/js/lib/storage',
        'Ptaang_Seller/js/model/url-builder',
        'Ptaang_Seller/js/model/new-product'
    ],
    function (storage, urlBuilder, newProduct) {
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
            return storage.put(
                        urlBuilder.createUrl('/products/'+createProductForm.sku, {}),
                        JSON.stringify(productObject),
                        false
                    ).done(
                        function (response) {
                            /** if have property Id the product has been Created successfully */
                            if(response.hasOwnProperty("id")){
                                var idProduct = response.id, customerId = urlBuilder.getCustomerId();
                                /** call the controller */
                                storage.post(
                                    'seller/account/saveproduct',
                                    JSON.stringify({
                                        productId: idProduct,
                                        customerId: customerId
                                    }),
                                    false
                                ).done(
                                    function (response) {
                                        console.log(response);
                                        newProduct.formLoader(false);
                                    }
                                ).fail(
                                    function () {
                                        newProduct.formLoader(false);
                                    }
                                );
                            /** End call to the second controller */
                            }else{
                                newProduct.formLoader(false);
                            }
                        }
                    ).fail(
                        function () {
                            newProduct.formLoader(false);
                        }
                    );
        };
    }
);


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

        return function (createProductForm, customAttributes, extensionAttributes) {
            /** prepare the object for be send it by REST */
            var productObject = { product : createProductForm };
            productObject.product.extension_attributes = extensionAttributes;
            productObject.product.custom_attributes =  customAttributes;

            return storage.put(
                        urlBuilder.createUrl('/products/'+createProductForm.sku, {}),
                        JSON.stringify(productObject),
                        false
                    ).done(
                        function (response) {
                            console.log(response);
                            newProduct.formLoader(false);
                        }
                    ).fail(
                        function () {
                            newProduct.formLoader(false);
                            console.log(urlBuilder.createUrl('/products', {}));
                        }
                    );
        };
    }
);

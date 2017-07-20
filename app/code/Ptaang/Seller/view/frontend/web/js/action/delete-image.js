
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'Ptaang_Seller/js/lib/storage',
        'Ptaang_Seller/js/model/new-product',
        'Ptaang_Seller/js/model/url-builder'
    ],
    function (storage, newProduct, urlBuilder) {
        'use strict';
        sku: sku;
        return function (itemId, imageFile) {
            return storage.delete(
                        urlBuilder.createUrl('/products/'+sku+'/media/'+itemId, {}),
                        JSON.stringify({
                        }),
                        false
                    ).done(
                        function (response) {
                            console.log(response);
                            newProduct.imagesFile.remove(imageFile);
                        }
                    ).always(
                        function () {
                            newProduct.loaderImages(false);
                        }
                    );
        };
    }
);

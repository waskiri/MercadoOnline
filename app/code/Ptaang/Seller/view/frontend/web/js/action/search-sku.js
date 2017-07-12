
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'mage/storage',
        'Ptaang_Seller/js/model/new-product'
    ],
    function (storage, newProduct) {
        'use strict';

        return function (sku) {

            return storage.post(
                        'seller/account_fieldset/searchsku',
                        JSON.stringify({sku : sku}),
                        false
                    ).done(
                        function (response) {
                            /** if the Sku exist show a warning error */
                            if(response.found){
                                newProduct.skuValue("");
                                newProduct.skuWarning(response.message);
                                newProduct.showSkuWarning(true);
                            }else{
                                newProduct.showSkuWarning(false);
                            }
                        }
                    ).always(
                        function () {
                            newProduct.skuLoader(false);
                        }
                    );
        };
    }
);

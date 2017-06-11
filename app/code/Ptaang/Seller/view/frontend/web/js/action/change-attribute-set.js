
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

        return function (setAttributeId) {
            return storage.post(
                        'seller/account_fieldset/changefieldset',
                        JSON.stringify({
                            setAttributeId: setAttributeId
                        }),
                        false
                    ).done(
                        function (response) {
                            console.log(response);
                            /** Populate the from with the new values */
                            if(response.true){

                            }
                            newProduct.formLoader(false);
                        }
                    ).fail(
                        function () {
                            newProduct.formLoader(false);
                        }
                    );
        };
    }
);

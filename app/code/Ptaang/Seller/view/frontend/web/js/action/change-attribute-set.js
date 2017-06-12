
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
                            /** Populate the form with the new attributes */
                            newProduct.attributes([]);
                            if(response.error == false ){
                                var attributes = response.attributes;
                                if(attributes.constructor === Array){
                                    newProduct.attributes(attributes);
                                }
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

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

        newProduct: newProduct
    });
});
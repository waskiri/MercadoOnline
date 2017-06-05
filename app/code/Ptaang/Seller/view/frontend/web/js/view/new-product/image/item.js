define([
    'ko',
    'uiComponent',
    'Ptaang_Seller/js/model/new-product'

], function (ko, Component, newProduct) {
    'use strict';

    return Component.extend({

        newProduct: newProduct,
    });
});
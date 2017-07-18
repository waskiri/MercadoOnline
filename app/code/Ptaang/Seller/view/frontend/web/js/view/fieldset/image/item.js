/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'ko',
    'uiComponent',
    'Ptaang_Seller/js/model/new-product',
    'Ptaang_Seller/js/action/delete-image'

], function (ko, Component, newProduct, deleteImage) {
    'use strict';

    ko.observableArray.fn.swap = function(index1, index2) {
        this.valueWillMutate();
        var temp = this()[index1];
        this()[index1] = this()[index2];
        this()[index2] = temp;
        this.valueHasMutated();
    };

    return Component.extend({

        newProduct: newProduct,
        removeImage: function (imageFile) {
            if(imageFile.entry_id != 0){ // remove an existing image
                newProduct.loaderImages(true);
                deleteImage(imageFile.entry_id, imageFile);
            }else{
                newProduct.imagesFile.remove(imageFile);
            }
        },
        /** Up the position of Image */
        upImage: function(imageFile){
            var i = newProduct.imagesFile.indexOf(imageFile);
            if(i > 0){
                newProduct.imagesFile.swap(i, i-1);
            }
        },
        /** Down the position of Image */
        downImage: function(imageFile){
            var i = newProduct.imagesFile.indexOf(imageFile),
                lastItemIndex = newProduct.imagesFile().length - 1;
            if(i > 0 && i != lastItemIndex){
                newProduct.imagesFile.swap(i, i+1);
            }
        }

    });
});
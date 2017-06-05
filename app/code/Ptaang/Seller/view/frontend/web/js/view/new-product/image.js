define([
    'ko',
    'uiComponent',
    'Ptaang_Seller/js/model/new-product'

], function (ko, Component, newProduct) {
    'use strict';

    return Component.extend({

        newProduct: newProduct,
        fileSelect: function(element, event){
            var files =  event.target.files;
            for (var i = 0, f; f = files[i]; i++) {

                if (!f.type.match('image.*')) {
                    continue;
                }
                var reader = new FileReader();
                reader.onload = (function(theFile) {
                    return function(e) {
                        newProduct.imagesFile.push({
                            "name": theFile.name,
                            "src" : e.target.result
                        });
                    };
                })(f);
                reader.readAsDataURL(f);
            }
        }

    });
});
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    ['jquery'],
    function($) {
        return {
            method: "rest",
            storeCode: storeCode,
            version: 'V1',
            serviceUrl: ':method/:version',

            createUrl: function(url, params) {
                var completeUrl = this.serviceUrl + url;
                return this.bindParams(completeUrl, params);
            },
            bindParams: function(url, params) {
                params.method = this.method;
                params.storeCode = this.storeCode;
                params.version = this.version;

                var urlParts = url.split("/");
                urlParts = urlParts.filter(Boolean);

                $.each(urlParts, function(key, part) {
                    part = part.replace(':', '');
                    if (params[part] != undefined) {
                        urlParts[key] = params[part];
                    }
                });
                return urlParts.join('/');
            }
        };
    }
);

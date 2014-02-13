var  Config = function($http) {
    var config = {};

    config.load = function(callback) {
        $http.get(Routing.generate('yandex_fotki_config')).success(function(response) {
            if (typeof response != 'object') {
                response = JSON.parse(response);
            }

            config = response;

            var generateLink = function(link, options) {
                options = options || {};

                link = link.replace(/:\w+/g, function(placeholder) {
                    placeholder = placeholder.slice(1);

                    return options[placeholder];
                });

                var delimiter = '?';
                if (link.match(/\?/)) {
                    delimiter = '&';
                }

                link += delimiter + 'format=' + config.format;

                return link;
            };

            var getServiceDocLink = function() {
                return generateLink(
                    config.serviceDocumentLink,
                    {username: config.username}
                );
            };
            $http.get(Routing.generate('common_proxy', {url: getServiceDocLink()})).success(function(response) {
                var serviceDocument = response;
                if (typeof response != 'object') {
                    serviceDocument = JSON.parse(response);
                }

                if (serviceDocument.hasOwnProperty('collections')) {
                    var collections = serviceDocument.collections;
                    if (collections.hasOwnProperty('photo-list')) {
                        config.imageCollectionLink = generateLink(collections['photo-list'].href);
                    }
                    if (collections.hasOwnProperty('album-list')) {
                        config.albumCollectionLink = generateLink(collections['album-list'].href);
                    }
                    if (collections.hasOwnProperty('tag-list')) {
                        config.tagCollectionLink = generateLink(collections['tag-list'].href);
                    }

                    delete config.format;
                    delete config.username;
                    delete config.serviceDocumentLink;
                }

                callback(config);
            });
        });
    };

    return config;
};

Config($http).load(function(data) {
    config = data;
});

var Collection = function($http, config) {

}

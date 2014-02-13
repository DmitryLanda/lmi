/**
 * @author Dmitry Landa <dmitry.landa@opensoftdev.ru>
 */
var selectedPhotos = [];

var yandexApp = angular.module('yandexApp', ['ngResource', 'ngRoute']);

yandexApp.config(function($routeProvider) {
//    $interpolateProvider.startSymbol('$$');
//    $interpolateProvider.endSymbol('$$');

    $routeProvider
        .when('/', {
            controller:'AlbumListController',
            templateUrl:'/bundles/lmischool/js/angular/templates/albumList.html'
        })
        .when('/:albumId', {
            controller:'AlbumController',
            templateUrl:'/bundles/lmischool/js/angular/templates/album.html'
        })
        .otherwise({
            redirectTo:'/'
        });
});

yandexApp.factory('YandexDataProvider', ['$http', function(http) {
    var yandexDataProvider = {};

    var config = {
        username: 'lmi-images',
        serviceDocumentLink: 'http://api-fotki.yandex.ru/api/users/:username/',
        albumCollectionLink: '',
        imageCollectionLink: '',
        tagCollectionLink: '',
        format: 'json'
    };

    /**
     * Generates link by mean of replacing placeholders in link with values from options
     *
     * @example
     *      link - http://example.com/:param1/:param2?:param3
     *      options - {param1: 1, param2: 'a', param3: 3}
     *
     *      resulting string will be http://example.com/1/a?3
     *      if any parameter missed - it becomes 'undefined'
     *
     * @param link {String}
     * @param options {Object}
     * @returns {String}
     */
    var generateLink = function(link, options) {
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

    /**
     * Returns well formed link to yandex.fotki service document
     *
     * @returns {String}
     */
    var getServiceDocLink = function() {
        return generateLink(config.serviceDocumentLink, {username: config.username});
    };

    /**
     * Gets all collection links from service document
     * and sets it to config variable
     */
    yandexDataProvider.initConfig = function() {
        var serviceDocument = {},
            collections = {};

        return http({
            method: 'jsonp',
            url: getServiceDocLink() + '&callback=JSON_CALLBACK'
        })
        .success(function(data) {
            serviceDocument = data;
            if (typeof data != 'object') {
                serviceDocument = JSON.parse(data);
            }

            if (serviceDocument.hasOwnProperty('collections')) {
                collections = serviceDocument.collections;
                if (collections.hasOwnProperty('photo-list')) {
                    config.imageCollectionLink = collections['photo-list'].href;
                }
                if (collections.hasOwnProperty('album-list')) {
                    config.albumCollectionLink = collections['album-list'].href;
                }
                if (collections.hasOwnProperty('tag-list')) {
                    config.tagCollectionLink = collections['tag-list'].href;
                }
            }
        })
        .error(function(data, status) {
            console.log({
                level: 'error',
                type: 'HttpError',
                status: status,
                message: data
            });
        });
    };

    /**
     * @returns {string}
     */
    yandexDataProvider.getAlbumCollectionLink = function() {
        return config.albumCollectionLink;
    };

    /**
     * @returns {string}
     */
    yandexDataProvider.getPhotoCollectionLink = function() {
        return config.imageCollectionLink;
    };

    /**
     * @returns {string}
     */
    yandexDataProvider.getTagCollectionLink = function() {
        return config.tagCollectionLink;
    };

    yandexDataProvider.getAlbumBaseLink = function() {
        return 'http://api-fotki.yandex.ru/api/users/' + config.username + '/album/';
    };

    return yandexDataProvider;
}]);

yandexApp.controller('AlbumListController', function AlbumListController(YandexDataProvider, $scope, $resource) {
    YandexDataProvider.initConfig().then(function() {
        var AlbumCollection = $resource(null, {},
            {
                query: {
                    method: 'JSONP',
                    isArray: true,
                    url: YandexDataProvider.getAlbumCollectionLink() + '?format=json&callback=JSON_CALLBACK',
                    transformResponse: function(response) {
                        var album, albums = [];
                        for (var i = 0; i < response.entries.length; i++) {
                            album = response.entries[i];
                            album.id = album.id.split(':')[5];
                            albums.push(album);
                        }
                        return albums;
                    }
                }
            }
        );
        $scope.albums = AlbumCollection.query();

        $scope.processSelectedPhotos = function() {
            console.log(selectedPhotos);
        }
    });
});

yandexApp.controller('AlbumController', function AlbumController(YandexDataProvider, $resource, $scope, $routeParams) {
    var createCollection = function(url) {
        return $resource(null, null,
            {
                query: {
                    url: url,
                    method: 'JSONP',
                    isArray: true,
                    params: {
                        callback: 'JSON_CALLBACK'
                    },
                    transformResponse: function(response) {
                        var photo, photos = [];
                        for (var i = 0; i < response.entries.length; i++) {
                            photo = response.entries[i];
                            photo.id = photo.id.split(':')[5];
                            photo.selected = null;
                            photo.save = function() {
                                console.log(this.title);
                            };
                            photos.push(photo);
                        }

                        return photos;
                    }
                }
            }
        );
    };

    var Album = $resource(
        YandexDataProvider.getAlbumBaseLink() + ':id/?format=json',
        {id: '@id'},
        {
            get: {
                method: 'JSONP',
                isArray: false,
                params: {
                    callback: 'JSON_CALLBACK'
                },
                transformResponse: function(response) {
                    var PhotoCollection = createCollection(response.links.photos);

                    return {title: response.title, photos: PhotoCollection.query()};
                }
            }
        }
    );

    $scope.album = Album.get({id: $routeParams.albumId});
    $scope.selectedPhotos = [];

    $scope.toggleSelection = function(photo) {
        if (photo.selected == 'selected') {
            photo.selected = null;
        } else {
            photo.selected = 'selected';
        }

        var index = selectedPhotos.indexOf(photo.id);
        if (index == -1) {
            selectedPhotos.push(photo.id);
        } else {
            selectedPhotos.splice(index, 1);
        }

        return false;
    };
});

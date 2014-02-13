/**
 * @author Dmitry Landa <dmitry.landa@opensoftdev.ru>
 */

'use strict';

angular.module('yandexFotkiApp', [
        'ngRoute',
//        'myApp.filters',
        'yandexFotkiApp.services',
//        'yandexFotkiApp.directives',
        'yandexFotkiApp.controllers'
    ])
    .config(['$routeProvider', function($routeProvider) {
        $routeProvider.when('/albums', {
            templateUrl: '/bundles/lmischool/js/angular/templates/albumList.html',
            controller: 'AlbumListCtrl'
        });
        $routeProvider.when('/albums/:albumId', {
            templateUrl: '/bundles/lmischool/js/angular/templates/album.html',
            controller: 'AlbumCtrl'
        });
        $routeProvider.otherwise({redirectTo: '/albums'});
    }]);

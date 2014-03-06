/**
 * @author Dmitry Landa <dmitry.landa@opensoftdev.ru>
 */

'use strict';

var app = angular.module('yandexFotkiApp.controllers', ['ui.bootstrap.modal'])
    .controller('AlbumListCtrl', ['AlbumCollection', '$scope', function(AlbumCollection, $scope) {
        $scope.albums = AlbumCollection.getItems();
    }])
    .controller('AlbumCtrl', [
        '$routeParams',
        'AlbumCollection',
        '$scope',

        function($routeParams, AlbumCollection, $scope) {
            var album, photos;
            album = AlbumCollection.get($routeParams.albumId);
            photos = album.photos.getItems();

            $scope.album = album;
            $scope.photos = photos;
    }])

    .controller('ModalCtrl', ['$scope', '$modal', '$log', function ($scope, $modal, $log) {
        $scope.items = ['item1', 'item2', 'item3'];

        $scope.open = function () {
            var modalInstance = $modal.open({
                templateUrl: 'myModalContent.html',
                controller: 'ModalInstanceCtrl',
                resolve: {
                    items: function () {
                        return $scope.items;
                    }
                }
            });

            modalInstance.result.then(function (selectedItem) {
                $scope.selected = selectedItem;
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        };
    }])
    .controller('ModalInstanceCtrl', ['$scope', '$modalInstance', 'items',function($scope, $modalInstance, items) {
        $scope.items = items;
        $scope.selected = {
            item: $scope.items[0]
        };

        $scope.ok = function () {
            $modalInstance.close($scope.selected.item);
        };

        $scope.cancel = function () {
            $modalInstance.dismiss('cancel');
        };
    }]);

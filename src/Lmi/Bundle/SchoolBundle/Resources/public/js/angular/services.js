/**
 * @author Dmitry Landa <dmitry.landa@opensoftdev.ru>
 */

'use strict';

angular.module('yandexFotkiApp.services', [
        'ngResource'
    ])
    .factory('Configuration', [function() {
        return new Configuration();
    }])
    .factory('AlbumCollection', ['Configuration', function( Configuration) {
        return new Collection(
            Configuration.getAlbumCollectionLink(),
            function(album) {
                album.id = album.id.split(':')[5];

                album.photos = new Collection(
                    album.links.photos,
                    function(photo) {
                        photo.id = photo.id.split(':')[5];

                        return photo;
                    }
                );

                return album;
            }
        );
    }]);

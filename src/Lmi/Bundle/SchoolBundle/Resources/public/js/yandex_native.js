'use strict';

var Proxy = {
    generate: function(url) {
        return Routing.generate('common_proxy', {url: url})
    }
};

var Configuration = function() {
    var config = {}, createUrl, initConfig, loadDefaults, load, isLoaded = false;

    createUrl = function(link, options) {
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

    initConfig = function() {
        var url = createUrl(config.serviceDocumentLink, {
            username: config.username
        });

        $.ajax({
            async: false,
            url: Proxy.generate(url),
            type: 'GET',
            beforeSend: function() {
                $(document).trigger('configuration:initialization:start');
            },
            success: function(data) {
                var collections;

                if (typeof data != 'object') {
                    data = JSON.parse(data);
                }

                if (data.hasOwnProperty('collections')) {
                    collections = data.collections;
                    if (collections.hasOwnProperty('photo-list')) {
                        config.imageCollectionLink = createUrl(collections['photo-list'].href);
                    }
                    if (collections.hasOwnProperty('album-list')) {
                        config.albumCollectionLink = createUrl(collections['album-list'].href);
                    }
                    if (collections.hasOwnProperty('tag-list')) {
                        config.tagCollectionLink = createUrl(collections['tag-list'].href);
                    }
                }
            },
            complete: function() {
                $(document).trigger('configuration:initialization:finish');
            }
        });
    };

    loadDefaults = function() {
        $.ajax({
            async: false,
            url: Routing.generate('yandex_fotki_config'),
            type: 'GET',
            beforeSend: function() {
                $(document).trigger('configuration:load:start');
            },
            success: function(data) {
                if (typeof data != 'object') {
                    data = JSON.parse(data);
                }

                config = data;
            },
            complete: function() {
                $(document).trigger('configuration:load:finish');
            }
        });
    };

    load = function() {
        loadDefaults();
        initConfig();
        isLoaded = true;
    };

    return {
        getAlbumCollectionLink: function() {
            if (!isLoaded) {
                load();
            }

            return config.albumCollectionLink;
        },

        getPhotoCollectionLink: function() {
            if (!isLoaded) {
                load();
            }

            return config.imageCollectionLink;
        },

        getTagCollectionLink: function() {
            if (!isLoaded) {
                load();
            }

            return config.tagCollectionLink;
        }
    };
};

var Collection = function(url, parseData) {
    var isLoaded = false,
        items = [],
        map = {};

    var append = function(item) {
        items.push(item);
        map[item.id] = items.length - 1;
    };

    var load = function() {
        return $.ajax({
            async: false,
            url: Proxy.generate(url),
            type: 'GET',
            beforeSend: function() {
                $(document).trigger('collection:load:start');
            },
            success: function(data) {
                var item, itemData;

                if (typeof data != 'object') {
                    data = JSON.parse(data);
                }
                if (!data.hasOwnProperty('entries')) {
                    console.log('Smth went wrong. Unexpected response');

                    return false;
                }

                for (var i = 0; i < data.entries.length; i++) {
                    itemData = data.entries[i];
                    //call user func to process response for each item
                    item = parseData(itemData);

                    append(item);
                }
            },
            complete: function() {
                isLoaded = true;
                $(document).trigger('collection:load:finish');
            }
        });
    };

    var addItem = function(item) {
        return $.ajax({
            url: Proxy.generate(url),
            data: item,
            type: 'POST',
            beforeSend: function() {
                $(document).trigger('collection:add:start');
            },
            success: function(data) {
                if (typeof data != 'object') {
                    data = JSON.parse(data);
                }
                data.id = data.id.split(':')[5];
                append(data);
            },
            error: function(xhr) {
                console.log('error', xhr.responseText);
            },
            complete: function() {
                $(document).trigger('collection:add:finish');
            }
        });
    };

    var removeItem = function(item) {
        return $.ajax({
            url: Proxy.generate(item.links.self),
            type: 'DELETE',
            beforeSend: function() {
                $(document).trigger('collection:delete:start');
            },
            success: function() {
                items = items.splice(map[item.id]);
                delete map[item.id];
            },
            error: function(xhr) {
                console.log('error', xhr.responseText);
            },
            complete: function() {
                $(document).trigger('collection:delete:finish');
            }
        });
    };

    return {
        add: function(item) {
            if (!isLoaded) {
                load();
            }

            // missing existence check looks bad, but I have no idea how to add it
            addItem(item);
        },

        remove: function(item) {
            if (!isLoaded) {
                load();
            }

            // if only id was given - try to get it from collection first
            if (!item.hasOwnProperty('id') && typeof item != 'object') {
                item = this.get(item);
            }

            if (item && map.hasOwnProperty(item.id)) {
                removeItem(item);
            }
        },

        get: function(id) {
            if (!isLoaded) {
                load();
            }

            if (map.hasOwnProperty(id)) {
                return items[map[id]];
            } else {
                return null;
            }
        },

        getItems: function() {
            if (!isLoaded) {
                load();
            }

            return items;
        },

        count: function() {
            if (!isLoaded) {
                load();
            }

            return items.length;
        }
    };
};

var loadDataFromHosting = function (url, successCallback, errorCallback) {
    return $.ajax({
        url: url,
        crossDomain: true,
        type: 'GET',
        dataType: 'jsonp',
        success: successCallback,
        error: errorCallback
    });
};

var container = $('body');

var Album = function(data) {
    var album = {
        id: /:(\d+)$/.exec(data.id)[1],
        title: data.title,
        created: new Date(data.published),
        summary: data.summary,
        author: 'ЛМИ',//todo update
        thumb: data.img.S.href,
        images: [],
        photoUrl: data.links.photos,
        loadPhotos: function() {
            return loadDataFromHosting(
                data.links.photos,
                function(resp) {
                    for (var i = 0, l = resp.entries.length; i < l; i++) {
                        album.images.push(new Image(resp.entries[i]));
                    }
                },
                function() {
                    console.log(arguments);
                }
            );
        },
        render: function() {
            return new AlbumView(album);
        }
    };

    return album;
};

var AlbumView = function(album) {
    var albumView = $('[data-template="album"]').first().clone().removeAttr('data-template');
    albumView.find('[data-template="title"]').text(album.title);
    var image = albumView.find('[data-template="thumbnail"]');
    image.attr('src', album.thumb);
    image.click(function() {

        $.when(album.loadPhotos()).done(function() {
            container.html('');
            container.append('<div class="grid-sizer"></div>');
            $.each(album.images, function() {
                container.append(this.render());
            });
        });
    });
    albumView.find('[data-template="author"]').text(album.author);
    albumView.find('[data-template="created"]').text(album.created.toLocaleDateString());
    albumView.removeClass('hidden');

    return albumView;
};

var Image = function(data) {
    var image = {
        id: /:(\d+)$/.exec(data.id)[1],
        title: data.title,
        created: new Date(data.published),
        summary: data.summary,
        author: 'ЛМИ',//todo update
        thumb: data.img.S.href,
        render: function() {
            return new ImageView(image);
        }
    };

    return image;
};

var ImageView = function(model) {
    var imageView = $('[data-template="photo"]').first().clone().removeAttr('data-template');
    var image = imageView.find('[data-template="thumbnail"]');
    image.attr('src', model.thumb);
    image.attr('data-id', model.id);

    image.click(function(e) {
        image.toggleClass('selected');
    });
    imageView.find('[data-template="author"]').text(model.author);
    imageView.find('[data-template="created"]').text(model.created.toLocaleDateString());
    imageView.removeClass('hidden');

    return imageView;
};

var Widget = function(containerSelector) {
    container = $(containerSelector);

    this.render = function() {
        container.html('');
        var renderAlbums = function(response) {
            for (var i = 0, l = response.entries.length; i < l; i++) {
                var album = new Album(response.entries[i]);
                container.append(album.render());
            }
        };
        loadDataFromHosting(
            'http://api-fotki.yandex.ru/api/users/lmi-images/albums/?format=json',
            renderAlbums,
            function() {
                console.log(arguments);
            }
        );
    };
};
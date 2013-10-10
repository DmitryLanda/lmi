/**
 * Created with JetBrains PhpStorm.
 * User: dmitry
 * Date: 25.09.13
 * Time: 0:58
 * To change this template use File | Settings | File Templates.
 */
function handleFileUpload(event) {
    $('#files-preview').html('');

    var files = event.target.files;

    for (var i = 0, file; file = files[i]; i++) {
        if (!file.type.match('image')) {
            continue;
        }

        var reader = new FileReader();
        reader.onload = (function(theFile) {
            var container = $('#files-preview');

            return function(e) {
                var image = $('<img>')
                    .addClass('thumbnail')
                    .addClass('pull-left')
                    .attr('src', e.target.result)
                    .attr('title', theFile.name);

                container.append(image);
            }
        })(file);

        reader.readAsDataURL(file);

        $('#files-preview').masonry();
    }
}

function handleFotkiSelection() {
    var container = $('#ya-fotki-preview');
    container.html('');

    $('[name*="selected_images"] option').each(function(i, option) {
        var image;

        $.when(loadDataFromHosting(
            'http://api-fotki.yandex.ru/api/users/lmi-images/photo/' + option.value + '/?format=json',
            function(response) {
                image = new Image(response);
            },
            function() {
                console.log(arguments);
            }
        )).done(function() {
            var imageEl = $('<img>')
                .addClass('thumbnail')
                .addClass('pull-left')
                .attr('src', image.thumb)
                .attr('title', image.title);

            container.append(imageEl);
        });
    });
}

$(document).ready(function() {
    $('#image-container').on('shown', function() {
        var yaFotkiWidget = new Widget('.modal-body:first');
        yaFotkiWidget.render();
    });

    $('#image-container').on('hidden', function() {
        $('.modal-body:first').html('');
    });

    $('[name="open-loader"]').click(function() {
        var imageContainer = $('#image-container');
        imageContainer.modal();
    });

    $('[name="select-images"]').click(function() {
        var container = $('.modal-body:first');
        var selectElement = $('[name*="selected_images"]').first();
        $.each(container.find('.selected'), function() {
            var imageId = $(this).attr('data-id');
            if (!selectElement.find('option[value="' + imageId + '"]').length) {
                var option = $('<option value="' + imageId + '">' + imageId + '</option>');
                selectElement.append(option);
            }
        });
        $('[name="select-images"]').trigger('change');

        $('#image-container').modal('hide');
    });

    $('#lmi_school_news_form_image_image').on('change', handleFileUpload);
    $('[name*="selected_images"]').on('change', handleFotkiSelection);
});

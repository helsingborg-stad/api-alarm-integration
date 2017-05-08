window.addEventListener('load', function () {
    var $ = jQuery;
    var requestUrl = disturbances.apiUrl + 'wp/v2/big-disturbance';
    var data = {};

    if (disturbances.places.join(',').length > 0) {
        data.place = disturbances.places.join(',');
    }

    if (!disturbances.inited) {
        $(document).on('click', '.notice-disturbance [data-action="toggle-notice-content"]', function (e) {
            $(this).parents('.notice-disturbance').find('.notice-content').toggleClass('open').slideToggle();

            if ($(this).parents('.notice-disturbance').find('.notice-content').hasClass('open')) {
                $(this).text(disturbances.less_info);
            } else {
                $(this).text(disturbances.more_info);
            }
        });

        disturbances.inited = true;
    }

    $.getJSON(requestUrl, data, function (response) {
        $.each(response, function (index, item) {
            if ($('#disturbance-' + item.id).length > 0) {
                return;
            }

            var $notice = $('\
                <div class="notice notice-disturbance notice-lg notice-fullwidth warning" id="disturbance-' + item.id + '" style="display:none;">\
                    <div class="container">\
                        <div class="grid grid-table">\
                            <div class="grid-auto">\
                                <i class="pricon pricon-notice-warning"></i> <strong>' + item.title.plain_text + '</strong>\
                            </div>\
                            <div class="grid-fit-content">\
                                <button type="button" class="btn btn-sm btn-contrasted" data-action="toggle-notice-content">' + disturbances.more_info + '</button>\
                            </div>\
                        </div>\
                        <div class="grid notice-content" style="display:none;">\
                            <div class="grid-md-12">' + item.content.rendered + '</div>\
                        </div>\
                    </div>\
                </div>\
            ').prependTo(disturbances.output_big).slideDown();
        });
    });
});

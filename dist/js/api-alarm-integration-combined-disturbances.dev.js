window.addEventListener('load', function () {
    var $ = jQuery;
    var requestUrl = disturbances.apiUrl + 'wp/v2/disturbances';
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

    $.ajax({
            type: "GET",
            url: requestUrl,
            cache: false,
            dataType: "json",
            data : data,
            crossDomain: true,
            success: function( response ) {
                if (disturbances.output_small_active) {
                    $.each(response.small, function (index, item) {
                        if ($('#disturbance-' + item.ID).length > 0) {
                            return;
                        }

                        var $notice = $('\
                            <div class="notice notice-disturbance notice-fullwidth info" id="disturbance-' + item.ID + '" style="display:none;">\
                                <div class="container">\
                                    <div class="grid grid-table">\
                                        <div class="grid-auto">\
                                            <i class="pricon pricon-notice-info"></i> <strong>' + item.post_title + '</strong>\
                                        </div>\
                                        <div class="grid-fit-content">\
                                            <button type="button" class="btn btn-sm btn-contrasted" data-action="toggle-notice-content">' + disturbances.more_info + '</button>\
                                        </div>\
                                    </div>\
                                    <div class="grid notice-content" style="display:none;">\
                                        <div class="grid-md-12">' + item.post_content + '</div>\
                                    </div>\
                                </div>\
                            </div>\
                        ').prependTo(disturbances.output_small).slideDown();
                    });
                }

                if (disturbances.output_big_active) {
                    $.each(response.big, function (index, item) {
                        if ($('#disturbance-' + item.ID).length > 0) {
                            return;
                        }

                        var $notice = $('\
                            <div class="notice notice-disturbance notice-lg notice-fullwidth warning" id="disturbance-' + item.ID + '" style="display:none;">\
                                <div class="container">\
                                    <div class="grid grid-table">\
                                        <div class="grid-auto">\
                                            <i class="pricon pricon-notice-warning"></i> <strong>' + item.post_title + '</strong>\
                                        </div>\
                                        <div class="grid-fit-content">\
                                            <button type="button" class="btn btn-sm btn-contrasted" data-action="toggle-notice-content">' + disturbances.more_info + '</button>\
                                        </div>\
                                    </div>\
                                    <div class="grid notice-content" style="display:none;">\
                                        <div class="grid-md-12">' + item.post_content + '</div>\
                                    </div>\
                                </div>\
                            </div>\
                        ').prependTo(disturbances.output_big).slideDown();
                    });
                }
            },
            error: function( response, status, error) {
                console.log('API Alarm Integration plugin: Request failed!');
            }
        });

});

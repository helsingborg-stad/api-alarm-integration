window.onload = function () {
    var $ = jQuery;
    var requestUrl = disturbances.apiUrl + 'wp/v2/small-disturbance';
    var data = {};

    console.log(requestUrl);

    if (disturbances.places.join(',').length > 0) {
        data.places = disturbances.places.join(',');
    }

    $(document).on('click', '.notice-disturbance [data-action="toggle-notice-content"]', function (e) {
        $(this).parents('.notice-disturbance').find('.notice-content').toggleClass('open').slideToggle();

        if ($(this).parents('.notice-disturbance').find('.notice-content').hasClass('open')) {
            $(this).text(disturbances.less_info);
        } else {
            $(this).text(disturbances.more_info);
        }
    });

    $.getJSON(requestUrl, data, function (response) {
        $.each(response, function (index, item) {
            var $notice = $('\
                <div class="notice notice-disturbance notice-fullwidth info" style="display:none;">\
                    <div class="container">\
                        <div class="grid grid-table">\
                            <div class="grid-auto">\
                                <i class="pricon pricon-notice-info"></i> <strong>' + item.title.plain_text + '</strong>\
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
            ').prependTo('body').slideDown();
        });
    });
};

var ApiAlarmIntegration = {};
ApiAlarmIntegration.FetchAlarms = (function ($) {

    function FetchAlarms() {
        $('[data-api-alarm-integration="load"]').each(function (index, element) {
            this.init(element);
            $(element).removeAttr('data-api-alarm-integration');
        }.bind(this));
    }

    /**
     * Init alarms for each module/widget
     * @param  {object} element
     * @return {void}
     */
    FetchAlarms.prototype.init = function(element) {
        var $element = $(element);
        var apiUrl = $element.attr('data-alarm-api');
        var perPage = $element.attr('data-alamrs-per-page');
        var currentPage = $element.attr('data-alamrs-current-page');

        this.loadAlarms(element, apiUrl, perPage, currentPage);
    };

    /**
     * Load alarms
     * @param  {object} element
     * @param  {string} apiUrl
     * @param  {int}    perPage
     * @param  {int}    currentPage
     * @return {void}
     */
    FetchAlarms.prototype.loadAlarms = function(element, apiUrl, perPage, currentPage) {
        var requestUrl = apiUrl + 'wp/v2/alarm';
        var data = {
            per_page: perPage,
            page: currentPage + 1
        };

        $.getJSON(requestUrl, data, function (response) {
            $.each(response, function (index, item) {
                this.addAlarmToList(element, item);
            }.bind(this));
        }.bind(this));
    };

    /**
     * Add alarm to alarm list
     * @param {element} element
     * @param {object}  item
     */
    FetchAlarms.prototype.addAlarmToList = function(element, item) {
        var date = new Date(item.date);
        item.date = date.toLocaleFormat('%Y-%m-%d %H:%M');

        var html = ApiAlarmIntegration.Helper.Template.render('api-alarm-integration-row', item);
        $(element).append(html);
    };

    return new FetchAlarms();

})(jQuery);

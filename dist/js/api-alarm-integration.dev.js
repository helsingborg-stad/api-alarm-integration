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
            console.log(response);
        });
    };

    return new FetchAlarms();

})(jQuery);

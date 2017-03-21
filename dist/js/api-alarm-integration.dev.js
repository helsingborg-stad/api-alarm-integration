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

ApiAlarmIntegration = ApiAlarmIntegration || {};
ApiAlarmIntegration.Helper = ApiAlarmIntegration.Helper || {};

ApiAlarmIntegration.Helper.Template = (function () {

    var _templates = [];

    function Template() {
        this.getTemplates();
    }

    Template.prototype.render = function(key, data) {
        // Get template html
        var template = _templates[key];

        // Replace template strings
        template = template.replace(/{{\s*([\w\.]+)\s*}}/g, function($1, $2) {
            var value = data;
            var objKey = $2.split('.');

            for (var i = 0; i < objKey.length; i++) {
                value = value[objKey[i]];
            }

            return value;
        });

        // Handle if-statements
        template = template.replace(/{%\s*if(.*)\s*%}([\s\S]*?){%\s*endif\s*%}/gm, function ($1, $2, $3) {
            if (!eval($2)) {
                return '';
            }

            return $3;
        });

        // Return the new html
        return template;
    };

    /**
     * Store templates in _templates array and removes it from the DOM
     * @return {void}
     */
    Template.prototype.getTemplates = function() {
        var templateElements = document.querySelectorAll('[data-template]');

        for (var i = 0; i < templateElements.length; i++) {
            var el = templateElements[i];
            var key = el.getAttribute('data-template');
            var html = el.outerHTML.replace(/data-template="([^\"]*)"/ig, '');

            _templates[key] = html;
            el.parentElement.removeChild(el);
        }
    };

    return new Template();

})();

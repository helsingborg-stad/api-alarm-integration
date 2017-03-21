var ApiAlarmIntegration = {};

ApiAlarmIntegration = ApiAlarmIntegration || {};
ApiAlarmIntegration.FetchAlarms = (function ($) {

    function FetchAlarms() {
        $(document).on('ready', function () {
            $('[data-api-alarm-integration="load"]').each(function (index, element) {
                this.init(element);
                $(element).removeAttr('data-api-alarm-integration');
            }.bind(this));

            $(document).on('click', '[data-action="api-alarm-integration-load-more"]', function (e) {
                this.loadMore(e.target);
            }.bind(this));

            // Toggle filters
            $(document).on('click', '[data-action="api-alarm-integration-toggle-filters"]', function (e) {
                var $filters = $(this).siblings('.filters');

                if ($filters.hasClass('hidden')) {
                    $filters.removeClass('hidden').hide();
                    $filters.addClass('open');
                }

                $filters.slideToggle();

                if ($filters.hasClass('open')) {
                    $(this).find('i.pricon').removeClass('pricon-caret-down').addClass('pricon-caret-up');
                    $(this).find('span').text(ApiAlarmIntegrationLang.hide_filters);
                    $filters.removeClass('open');
                } else {
                    $(this).find('i.pricon').removeClass('pricon-caret-up').addClass('pricon-caret-down');
                    $(this).find('span').text(ApiAlarmIntegrationLang.show_filters);
                    $filters.addClass('open');
                }
            });

            $(document).on('click', '[data-alarm-filter="search"]', function (e) {
                var element = $(e.target).parents('.box-content').parent().find('.accordion');
                this.loadAlarms(element, true);
            }.bind(this));
        }.bind(this));
    }

    /**
     * Init alarms for each module/widget
     * @param  {object} element
     * @return {void}
     */
    FetchAlarms.prototype.init = function(element) {
        this.loadAlarms(element);
    };

    FetchAlarms.prototype.getFilters = function(element) {
        var textFilter = $(element).parents('.box-content').parent().find('[data-alarm-filter="text"]').val();
        var placeFilter = $(element).parents('.box-content').parent().find('[data-alarm-filter="place"]').val();
        var dateFromFilter = $(element).parents('.box-content').parent().find('[data-alarm-filter="date-from"]').val();
        var dateToFilter = $(element).parents('.box-content').parent().find('[data-alarm-filter="date-to"]').val();

        return {
            search: textFilter,
            place: placeFilter,
            date_from: dateFromFilter,
            date_to: dateToFilter
        };
    };

    /**
     * Load alarms
     * @param  {object} element
     * @param  {string} apiUrl
     * @param  {int}    perPage
     * @param  {int}    currentPage
     * @return {void}
     */
    FetchAlarms.prototype.loadAlarms = function(element, isSearch) {
        if (typeof isSearch === 'undefined') {
            isSearch = false;
        }

        var apiUrl = $(element).attr('data-alarm-api');
        var perPage = $(element).attr('data-alamrs-per-page');
        var currentPage = $(element).attr('data-alamrs-current-page');

        var requestUrl = apiUrl + 'wp/v2/alarm';
        var data = {
            per_page: perPage,
            page: parseInt(currentPage) + 1
        };

        // Get filters and put them in data object
        var filters = this.getFilters(element);

        for (var attrname in filters) {
            data[attrname] = filters[attrname];
        }

        if (isSearch) {
            $(element).empty();
        }

        var loading = ApiAlarmIntegration.Helper.Template.render('api-alarm-integration-loading');
        $(element).append(loading);

        $.getJSON(requestUrl, data, function (response) {
            $.each(response, function (index, item) {
                $(element).find('[data-alarms-loading]').remove();
                this.addAlarmToList(element, item);
            }.bind(this));

            $(element).find('[data-api-alarms-load-more]').remove();

            // Append load more button
            var button = ApiAlarmIntegration.Helper.Template.render('api-alarm-integration-load-more');
            $(element).append(button);
        }.bind(this));
    };

    /**
     * Load more alarms
     * @param  {elemetn} element Clicked button
     * @return {void}
     */
    FetchAlarms.prototype.loadMore = function(element) {
        var baseElement = $(element).parents('.alarms-container');
        var apiUrl = baseElement.attr('data-alarm-api');
        var perPage = baseElement.attr('data-alamrs-per-page');
        var currentPage = baseElement.attr('data-alamrs-current-page') + 1;

        // Update current page
        baseElement.attr('data-alamrs-current-page', currentPage);

        $(element).closest('[data-api-alarms-load-more]').remove();

        this.loadAlarms(baseElement);
    };

    /**
     * Add alarm to alarm list
     * @param {element} element
     * @param {object}  item
     */
    FetchAlarms.prototype.addAlarmToList = function(element, item) {
        var date = new Date(item.date);
        item.date = date.toLocaleFormat('%Y-%m-%d %H:%M');

        // Append alarm
        var alarm = ApiAlarmIntegration.Helper.Template.render('api-alarm-integration-row', item);
        $(element).append(alarm);
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

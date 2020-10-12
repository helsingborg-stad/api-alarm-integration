import Template from '../Helper/Template';

let ApiAlarmIntegration = {};
ApiAlarmIntegration.Helper = {};
ApiAlarmIntegration.Helper.Template = Template;
export default (function ($) {

    function FetchAlarms() {
        const self = this;
        document.addEventListener("DOMContentLoaded", function () {

            $('[data-api-alarm-integration="load"]').each(function (index, element) {
                this.init(element);
                $(element).removeAttr('data-api-alarm-integration');
            }.bind(this));

            $(document).on('click', '[data-action="api-alarm-integration-load-more"]', function (e) {
                this.loadMore(e.target);
                self.selectActiveItem();
            }.bind(this));

            // Toggle filters
            $(document).on('click', '[data-action="api-alarm-integration-toggle-filters"]', function (e) {
                let $filters = $(this).siblings('.filters');

                if ($filters.hasClass('hidden')) {
                    $filters.removeClass('hidden').hide();
                    $filters.addClass('open');
                }

                $filters.slideToggle();

                if ($filters.hasClass('open')) {

                    $(this).find('span').text(ApiAlarmIntegrationLang.hide_filters);
                    $filters.removeClass('open');
                } else {

                    $(this).find('span').text(ApiAlarmIntegrationLang.show_filters);
                    $filters.addClass('open');
                }
                self.selectActiveItem();
            });

            $(document).on('click', '[data-alarm-filter="search"]', function (e) {
                let element = $(e.target).parents('.box-content').parent().find('.accordion');
                this.loadAlarms(element, true);
                self.selectActiveItem();
            }.bind(this));

            $(document).on('alarms:loaded', function (e) {
                let $alarmslist = $(e.target);
                let hash = window.location.hash;
                window.location.hash = '';
                window.location.hash = hash;
                self.selectActiveItem();
            });


        }.bind(this));
    }

    /**
     * Accordion arrow cion
     * Change on state
     */
    FetchAlarms.prototype.selectActiveItem= function () {
        for (const openItem of document.querySelectorAll(
            '.modularity-mod-alarms .c-accordion__section .c-accordion__button')
            ) {
            openItem.addEventListener("click", function () {
                let contentID = this.getAttribute('data-js-toggle-trigger');
                let thisItem = this.querySelector('i');

                if (thisItem.classList.contains('keyboard_arrow_down')) {
                    thisItem.innerHTML = 'keyboard_arrow_up';
                    thisItem.classList.remove('keyboard_arrow_down');
                    thisItem.classList.add('keyboard_arrow_up');
                    document.getElementById(contentID).classList.remove('u-display--none');
                } else {
                    thisItem.innerHTML = 'keyboard_arrow_down';
                    thisItem.classList.remove('keyboard_arrow_up');
                    thisItem.classList.add('keyboard_arrow_down');
                    document.getElementById(contentID).classList.add('u-display--none');
                }
            });
        }
    }

    /**
     * Init alarms for each module/widget
     * @param  {object} element
     * @return {void}
     */
    FetchAlarms.prototype.init = function (element) {
        this.loadAlarms(element);
    };

    FetchAlarms.prototype.getFilters = function (element) {
        let textFilter = $(element).parents('.box-content').parent().find('[data-alarm-filter="text"]').val();
        let placeFilter = $(element).parents('.box-content').parent().find('[data-alarm-filter="place"]').val();
        let dateFromFilter = $(element).parents('.box-content').parent().find('[data-alarm-filter="date-from"]').val();
        let dateToFilter = $(element).parents('.box-content').parent().find('[data-alarm-filter="date-to"]').val();

        let filters = {};

        if (textFilter.length > 0) {
            filters.search = textFilter;
        }

        if (placeFilter.length > 0) {
            filters.place = placeFilter;
        }

        if (dateFromFilter.length > 0) {
            filters.date_from = dateFromFilter;
        }

        if (dateToFilter.length > 0) {
            filters.date_to = dateToFilter;
        }

        return filters;
    };

    /**
     * Load alarms
     * @param  {object} element
     * @param  {string} apiUrl
     * @param  {int}    perPage
     * @param  {int}    currentPage
     * @return {void}
     */
    FetchAlarms.prototype.loadAlarms = function (element, isSearch) {
        if (typeof isSearch === 'undefined') {
            isSearch = false;
        }

        let apiUrl = $(element).attr('data-alarm-api');
        let perPage = $(element).attr('data-alamrs-per-page');
        let currentPage = $(element).attr('data-alamrs-current-page');

        let requestUrl = apiUrl + 'wp/v2/alarm';
        let data = {
            per_page: perPage,
            page: parseInt(currentPage) + 1
        };

        // Get filters and put them in data object
        let filters = this.getFilters(element);

        for (let attrname in filters) {
            data[attrname] = filters[attrname];
        }

        if (isSearch) {
            $(element).empty();
        }

        let loading = ApiAlarmIntegration.Helper.Template.render('api-alarm-integration-loading');
        $(element).append(loading);

        $.getJSON(requestUrl, data, function (response) {
            $.each(response, function (index, item) {
                $(element).find('[data-alarms-loading]').remove();
                this.addAlarmToList(element, item);
            }.bind(this));

            $(element).find('[data-api-alarms-load-more]').remove();

            // Append load more button
            let button = ApiAlarmIntegration.Helper.Template.render('api-alarm-integration-load-more');
            $(element).append(button);
            $(element).trigger('alarms:loaded');
        }.bind(this));
    };

    /**
     * Load more alarms
     * @param  {elemetn} element Clicked button
     * @return {void}
     */
    FetchAlarms.prototype.loadMore = function (element) {
        let baseElement = $(element).parents('.alarms-container');
        let apiUrl = baseElement.attr('data-alarm-api');
        let perPage = baseElement.attr('data-alamrs-per-page');
        let currentPage = baseElement.attr('data-alamrs-current-page') + 1;

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
    FetchAlarms.prototype.addAlarmToList = function (element, item) {
        item.date = item.date.replace("T", " ").substring(0, item.date.length - 3);

        // Append alarm
        let alarm = ApiAlarmIntegration.Helper.Template.render('api-alarm-integration-row', item);
        $(element).append(alarm);
    };

    return new FetchAlarms();

})(jQuery);
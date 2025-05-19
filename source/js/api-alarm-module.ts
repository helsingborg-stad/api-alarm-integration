import Template from './Helper/Template';

let ApiAlarmIntegration = {};
ApiAlarmIntegration.Helper = {};
ApiAlarmIntegration.Helper.Template = Template;

class AlarmModule {
    constructor(alarmList) {
        this.AlarmList  = alarmList;
        this.perPage    = alarmList.getAttribute('data-alarms-per-page');
        this.requestUrl = alarmList.getAttribute('data-alarm-api') + 'wp/v2/alarm';

        this.Templates = {
            loader:     ApiAlarmIntegration.Helper.Template.render('api-alarm-integration-loader'),
            loadMore:   ApiAlarmIntegration.Helper.Template.render('api-alarm-integration-load-more'),
            error:      ApiAlarmIntegration.Helper.Template.render('api-alarm-integration-error'),
            noResults:  ApiAlarmIntegration.Helper.Template.render('api-alarm-integration-no-results')
        }

        this.loadAlarms();
        this.searchListener();
    }

    loadAlarms() {
        this.AlarmList.querySelector('[data-api-alarms-container').removeAttribute('is-loaded')
        this.AlarmList.insertAdjacentHTML('beforeend', this.Templates.loader);

        // Get filters and make a query string of them
        let dataQuery = this.serialize(Object.assign({
            per_page: this.perPage,
            page: parseInt(this.getCurrentPage()) + 1
        }, this.getFilters()));

        async function postData(url = '') {
            const response = await fetch(url, {
                cache: 'no-cache',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                redirect: 'follow',
                referrerPolicy: 'no-referrer', 
            });

            if(response.status !== 200) {
                return false;
            }
            
            return response.json();
        }
        
        postData(`${this.requestUrl}?${dataQuery}`).then((response) => {

            if(!response) {
                this.requestFailed();
            }

            if(response) {
                if(response.length <= 0) {
                    this.showNoResults()
                    this.noMoreLoadMore()
                }

                if(response.length > 0) {
                    response.forEach(item => {
                        this.addAlarmToList(item);
                    });

                    if(this.AlarmList.querySelector('#mod-alarm-no-result')) {
                        this.AlarmList.querySelector('#mod-alarm-no-result').remove()
                    }

                    this.addAccordionListener();
                }

                if(response.length >= this.perPage) {
                    //Add load more button if there is none
                    if(!this.AlarmList.querySelector('[data-api-alarms-load-more]')) {
                        this.AlarmList.insertAdjacentHTML('beforeend', this.Templates.loadMore);

                        this.AlarmList.querySelector('[data-api-alarms-load-more]').addEventListener('click', () => {
                            this.AlarmList.setAttribute('data-alarms-current-page', parseInt(this.getCurrentPage()) +1);
                            this.loadAlarms()
                        })
                    }
                    // this.loadMoreListener() 
                }

                if(response.length > 0 && response.length < this.perPage) {
                    this.noMoreLoadMore()
                }

                this.AlarmList.querySelector('[data-api-alarms-container]').setAttribute('is-loaded', '')
                this.AlarmList.querySelector('[data-api-alarms-loader]').remove()
            }
        })
        .catch((e) => {
            this.requestFailed()
        });
    }

    getFilters() {
        let filters = {
            'search'      : this.getInputValue(this.AlarmList.querySelector('#input_data-alarm-filter-text')),
            'place'       : this.getInputValue(this.AlarmList.querySelector('[data-alarm-filter="place"]')),
            'date_from'   : this.getInputValue(this.AlarmList.querySelector('#data-alarm-filter-date-from').querySelector('input')),
            'date_to'     : this.getInputValue(this.AlarmList.querySelector('#data-alarm-filter-date-to').querySelector('input'))
        };

        //TODO: Handle dates in a more reliable way
        filters.date_to     = filters.date_to.split('/').reverse().join('-')
        filters.date_from   = filters.date_from.split('/').reverse().join('-')

        for (let key in filters) {
            filters[key].length <= 0 ? delete filters[key] : '';
        }

        return filters;
    };

    getInputValue(element) {
        if(element !== null) {
            return element.value;
        }
        return '';
    }

    addAlarmToList (item) {
        item.date = item.date.replace("T", " ").substring(0, item.date.length - 3);

        // Append alarm
        let alarm = ApiAlarmIntegration.Helper.Template.render('api-alarm-integration-row', item);
        this.AlarmList.querySelector('[data-api-alarms-container]').insertAdjacentHTML('beforeend', alarm);
    };

    loadMoreListener() {
        //Add load more button if there is none
        if(!this.AlarmList.querySelector('[data-api-alarms-load-more]')) {
            this.AlarmList.insertAdjacentHTML('beforeend', this.Templates.loadMore);

            this.AlarmList.querySelector('[data-api-alarms-load-more]').addEventListener('click', () => {
                this.AlarmList.setAttribute('data-alarms-current-page', parseInt(this.getCurrentPage()) +1);
                this.loadAlarms()
            })
        }
    }
    /**
     * Listens to submission of form
     */
    searchListener() {
        this.AlarmList.querySelector('[data-alarm-filter-search]').addEventListener('click', () => {
            this.search();
        })

        this.AlarmList.querySelector('#input_data-alarm-filter-text').onkeydown = (e) => {
            if(e.keyCode == 13){
                this.search();
            }
        };
    }

    search() {
        this.AlarmList.setAttribute('data-alarms-current-page', 0);
        this.AlarmList.querySelector('[data-api-alarms-container]').innerHTML = '';
        this.loadAlarms()
    }

    addAccordionListener() {
        const alarmRow = this.AlarmList.querySelectorAll('[data-api-alarms-row]');

        alarmRow.forEach(btn => {
            if(!btn.hasAttribute('has-accordion-listener')) {
                btn.setAttribute('has-accordion-listener', '');
                btn.addEventListener('click', (e) => {
                    e.target.closest('[data-api-alarms-row]').toggleAttribute('is-expanded');
                })
            }
        });
    }

    noMoreLoadMore() {
        if(this.AlarmList.querySelector('[data-api-alarms-load-more]')) {
            this.AlarmList.querySelector('[data-api-alarms-load-more]').remove()
        }
    }

    getCurrentPage() {
        return this.AlarmList.getAttribute('data-alarms-current-page');
    }
    
    showNoResults() {
        if(!this.AlarmList.querySelector('#mod-alarm-no-result')){
            this.AlarmList.querySelector('.c-card__body').insertAdjacentHTML('beforeend', this.Templates.noResults);
        }
    }

    serialize(obj) {
        var str = [];
        for (var p in obj)
          if (obj.hasOwnProperty(p)) {
            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
          }
        return str.join("&");
    }

    requestFailed() {
        this.AlarmList.querySelector('[data-api-alarms-container').setAttribute('is-loaded', '')
        this.AlarmList.querySelector('[data-api-alarms-loader]').remove()
        this.AlarmList.querySelector('.c-card__body').innerHTML = "";
        this.AlarmList.querySelector('.c-card__body').insertAdjacentHTML('beforeend', this.Templates.error);
    }
}

export default AlarmModule;

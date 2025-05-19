import { ApiResponse, DisturbanceSettings, Services } from "./types";

export const NoticeModule = (settings: Partial<DisturbanceSettings>) => {

    const php = {
        htmlSmall: '',
        htmlBig: '',
        htmlFirelevel: '',
        inited: false,
        apiUrl: '',
        places: [],
        more_info: '',
        less_info: '',
        output_small_active: false,
        output_big_active: false,
        output_firedangerlevel_active: false,
        output_small: '',
        output_big: '',
        output_firelevel: '',
        ...settings
    };

    return {
        render: ({ http, template, transform}: Services) => 
            http.fetch<ApiResponse | ApiResponse['disturbances']>(php.apiUrl,{
                place: php.places.join(',')
            }).then((response) => {
                // Upgrade response
                const data = transform.map(response);

                // Render Small
                if (php.output_small_active) {
                    data.disturbances.small.forEach(item => {
                        if (document.querySelectorAll('#disturbance-' + item.ID).length > 0) {
                            return;
                        }
                        document.querySelector(php.output_small)?.insertAdjacentHTML('afterbegin', 
                            template.getSmallTemplate(php.htmlSmall, item));
                    });
                }
                // Render Big
                if (php.output_big_active) {
                    data.disturbances.big.forEach(item => {
                        if (document.querySelectorAll('#disturbance-' + item.ID).length > 0) {
                            return;
                        }
                        document.querySelector(php.output_big)?.insertAdjacentHTML('afterbegin', 
                            template.getBigTemplate(php.htmlBig, item));
                    });
                }
                // Render Firedanger
                if (php.output_firedangerlevel_active) {
                    const strict = data.firedangerlevel.places.filter(
                        (item) => item.level === '2');
                        
                    strict.forEach(item => {
                        document.querySelector(php.output_firelevel)?.insertAdjacentHTML('afterbegin', 
                            template.getFirelevelTemplate(php.htmlFirelevel, item)); 
                    });
        
                }
        }).catch((e) => {
                console.log(e)
                console.log('API Alarm Integration plugin: Request failed!');
            })
        }
    
    }


export default NoticeModule

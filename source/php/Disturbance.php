<?php

declare(strict_types=1);

namespace ApiAlarmIntegration;

use ApiAlarmIntegration\Helper\CacheBust;

class Disturbance
{
    public function __construct()
    {
        add_action('acf/init', [$this, 'addOptionsPage']);
        add_action(
            'acf/load_field/name=disturbances_places',
            [$this, 'addPlaces'],
        );

        add_action(
            'wp_footer',
            static function () {
                if (
                    !get_field('disturbnaces_enabled', 'options')
                    || empty(get_field(
                        'disturbances_output_automatically',
                        'options',
                    ))
                ) {
                    return;
                }

                echo '<script>';

                $outputBigSelector = get_field('output_selector_big_disturbance', 'option') ? get_field('output_selector_big_disturbance', 'option') : 'body';
                $outputSmallSelector = get_field('output_selector_small_disturbance', 'option') ? get_field('output_selector_small_disturbance', 'option') : 'body';
                $outputFirelevelSelector = get_field('output_selector_firelevel', 'option') ? get_field('output_selector_firelevel', 'option') : 'body';

                $fireBanTextStrict = _x('Strict fire ban', 'fire danger level', 'api-alarm-integration');

                $disturbanceMarkup = (object) [
                    'small' => disturbance_render_blade_view(
                        'js.small',
                        ['title' => '{DISTURBANCE_TITLE}', 'text' => '{DISTURBANCE_TEXT}'],
                    ),
                    'big' => disturbance_render_blade_view(
                        'js.big',
                        ['title' => '{DISTURBANCE_TITLE}', 'text' => '{DISTURBANCE_TEXT}'],
                    ),
                    'firelevel' => disturbance_render_blade_view(
                        'js.firelevel',
                        ['title' => '{DISTURBANCE_TITLE}', 'text' => $fireBanTextStrict],
                    ),
                ];
                // phpcs:disable
                echo
                    '
            const settings = {
                htmlSmall: \''
                        . $disturbanceMarkup->small
                        . '\',
                htmlBig: \''
                        . $disturbanceMarkup->big
                        . '\',
                htmlFirelevel: \''
                        . $disturbanceMarkup->firelevel
                        . '\',
                inited: false,
                apiUrl: \''
                        . trailingslashit(get_field('disturbances_api_url', 'option'))
                        . '\',
                places: '
                        . json_encode(get_field('disturbances_places', 'option'))
                        . ',
                more_info: \''
                        . __('Show more information', 'api-alarm-integration')
                        . '\',
                less_info: \''
                        . __('Show less information', 'api-alarm-integration')
                        . '\',
                output_small_active: \''
                        . in_array('small', get_field(
                            'disturbances_output_automatically',
                            'options',
                        ))
                        . '\',
                output_big_active: \''
                        . in_array('big', get_field(
                            'disturbances_output_automatically',
                            'options',
                        ))
                        . '\',
                output_firedangerlevel_active: \''
                        . in_array('firelevel', get_field(
                            'disturbances_output_automatically',
                            'options',
                        ))
                        . '\',
                output_small: \''
                        . $outputSmallSelector
                        . '\',
                output_big: \''
                        . $outputBigSelector
                        . '\',
                output_firelevel: \''
                        . $outputFirelevelSelector
                        . '\'
            };
            '
                ;

                echo file_get_contents(APIALARMINTEGRATION_PATH . '/dist/' . CacheBust::name('js/api-alarm-index.js'));

                echo '</script>';

                // phpcs:enable
            },
            100,
        );
    }

    /**
     * Adds options page for disturbances
     */
    public function addOptionsPage()
    {
        if (!function_exists('acf_add_options_page')) {
            return;
        }

        $option_page = acf_add_options_page(
            [
                'page_title' => __('Disturbances', 'api-alarm-integration'),
                'menu_slug' => 'api-alarm-integration-disturbances',
                'parent_slug' => 'tools.php',
            ],
        );
    }

    /**
     * Add places to places field
     *
     * @param array $field
     */
    public function addPlaces($field)
    {
        if (is_admin()) {
            $placesApiUrl = get_field('places_api_url', 'option');
            $disturbancesApiUrl = get_field('disturbances_api_url', 'option');

            if (!$disturbancesApiUrl) {
                return $field;
            }

            $url = \ApiAlarmIntegration\Module::getPlacesUrl($placesApiUrl, $disturbancesApiUrl);
            $places = \ApiAlarmIntegration\Module::getPlaces($url);

            if (is_array($places) && !empty($places)) {
                foreach ($places as $place) {
                    $field['choices'][$place->id] = $place->name;
                }
            }
        }
        return $field;
    }
}

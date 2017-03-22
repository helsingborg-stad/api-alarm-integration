<?php

namespace ApiAlarmIntegration;

class Disturbance
{
    public function __construct()
    {
        add_action('acf/init', array($this, 'addOptionsPage'));
        add_action('acf/load_field/name=disturbances_places', array($this, 'addPlaces'));

        add_action('wp_footer', function () {
            if (empty(get_field('disturbances_output_automatically', 'options'))) {
                return;
            }

            echo '<script defer>';

            $outputBigSelector = get_field('output_selector_big_disturbance', 'option') ? get_field('output_selector_big_disturbance', 'option') : 'body';
            $outputSmallSelector = get_field('output_selector_small_disturbance', 'option') ? get_field('output_selector_small_disturbance', 'option') : 'body';

            echo '
            var disturbances = {};
            disturbances.inited = false;
            disturbances.apiUrl = \'' . trailingslashit(get_field('disturbances_api_url', 'option')) . '\';
            disturbances.places = ' . json_encode(get_field('disturbances_places', 'option')) . ';
            disturbances.more_info = \'' . __('Show more information', 'api-alarm-integration') . '\';
            disturbances.less_info = \'' . __('Show less information', 'api-alarm-integration') . '\';
            disturbances.output_big = \'' . $outputBigSelector . '\';
            disturbances.output_small = \'' . $outputSmallSelector . '\';
            ';

            if (in_array('small', get_field('disturbances_output_automatically', 'options'))) {
                echo file_get_contents(APIALARMINTEGRATION_PATH . 'dist/js/api-alarm-integration-small-disturbances.min.js');
            }

            if (in_array('big', get_field('disturbances_output_automatically', 'options'))) {
                echo file_get_contents(APIALARMINTEGRATION_PATH . 'dist/js/api-alarm-integration-big-disturbances.min.js');
            }

            echo '</script>';
        }, 100);
    }

    /**
     * Adds options page for disturbances
     */
    public function addOptionsPage()
    {
        if (!function_exists('acf_add_options_page')) {
            return;
        }

        $option_page = acf_add_options_page(array(
            'page_title'    => __('Disturbances', 'api-alarm-integration'),
            'menu_slug'     => 'api-alarm-integration-disturbances',
            'parent_slug'   => 'tools.php'
        ));
    }

    /**
     * Add places to places field
     * @param array $field
     */
    public function addPlaces($field)
    {
        $apiUrl = trailingslashit(get_field('disturbances_api_url', 'option'));

        if (!$apiUrl) {
            return $field;
        }

        $places = \ApiAlarmIntegration\Module::getPlaces($apiUrl);

        foreach ($places as $place) {
            $field['choices'][$place->id] = $place->name;
        }

        return $field;
    }
}

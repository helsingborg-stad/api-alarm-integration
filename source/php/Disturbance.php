<?php

namespace ApiAlarmIntegration;

class Disturbance
{
    public function __construct()
    {
        add_action('acf/init', array($this, 'addOptionsPage'));
    }

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
}

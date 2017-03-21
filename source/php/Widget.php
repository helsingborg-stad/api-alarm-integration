<?php

namespace ApiAlarmIntegration;

class Widget extends \WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'api-alarm-widget',
            'Alarms',
            array(
                "description" => __('Displays a list with alarms', 'api-alarm-integration')
            )
        );
    }

    public function update($new, $old)
    {
    }

    public function widget($args, $instance)
    {
        wp_enqueue_script('api-alarm-integration', APIALARMINTEGRATION_URL . '/dist/js/api-alarm-integration.min.js', array('jquery'), '1.0.0', true);
        wp_localize_script('api-alarm-integration', 'ApiAlarmIntegrationLang', array(
            'show_filters' => __('Show filters', 'api-alarm-integration'),
            'hide_filters' => __('Hide filters', 'api-alarm-integration')
        ));

        require APIALARMINTEGRATION_TEMPLATE_PATH . '/widget.php';
    }

    public function form($instance)
    {
    }
}

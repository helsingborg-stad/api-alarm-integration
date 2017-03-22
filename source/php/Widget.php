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
        \ApiAlarmIntegration\App::enqueueAlarmScripts();
        require APIALARMINTEGRATION_TEMPLATE_PATH . '/widget.php';
    }

    public function form($instance)
    {
    }
}

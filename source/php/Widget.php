<?php

declare(strict_types=1);

namespace ApiAlarmIntegration;

class Widget extends \WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'api-alarm-widget',
            'Alarms',
            [
                'description' => __('Displays a list with alarms', 'api-alarm-integration'),
            ],
        );
    }

    /**
     * Widget update (handled by ACF)
     * @param  array $new
     * @param  array $old
     * @return void
     */
    public function update($new, $old)
    {
    }

    /**
     * Widget template
     * @param  array $args
     * @param  array $instance
     * @return void
     */
    public function widget($args, $instance)
    {
        \ApiAlarmIntegration\App::enqueueAlarmScripts();
        require APIALARMINTEGRATION_TEMPLATE_PATH . '/widget.php';
    }

    /**
     * Widget form (handled by ACF)
     * @param  array $instance
     * @return void
     */
    public function form($instance)
    {
    }
}

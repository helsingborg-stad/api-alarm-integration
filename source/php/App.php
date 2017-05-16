<?php

namespace ApiAlarmIntegration;

class App
{
    public function __construct()
    {
        modularity_register_module(
            APIALARMINTEGRATION_PATH . 'source/php/', // The directory path of the module
            'Module' // The class' file and class name (should be the same) withot .php extension
        );

        add_action('widgets_init', array($this, 'registerWidget'));

        new \ApiAlarmIntegration\Disturbance();
    }

    /**
     * Registers module
     */
    public function addModule()
    {
        new \ApiAlarmIntegration\Module();
    }

    /**
     * Registers widget
     * @return void
     */
    public function registerWidget()
    {
        register_widget('\ApiAlarmIntegration\Widget');
    }

    /**
     * Enqueues widget/module assets
     * @return void
     */
    public static function enqueueAlarmScripts()
    {
        wp_enqueue_script('api-alarm-integration', APIALARMINTEGRATION_URL . '/dist/js/api-alarm-integration.min.js', array('jquery'), '1.0.0', true);
        wp_localize_script('api-alarm-integration', 'ApiAlarmIntegrationLang', array(
            'show_filters' => __('Show filters', 'api-alarm-integration'),
            'hide_filters' => __('Hide filters', 'api-alarm-integration')
        ));
    }
}

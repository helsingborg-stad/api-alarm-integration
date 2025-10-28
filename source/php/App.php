<?php

namespace ApiAlarmIntegration;

use ApiAlarmIntegration\Helper\CacheBust;

class App
{
    public function __construct()
    {
        add_action('init', function () {
            if (function_exists('modularity_register_module')) {
                modularity_register_module(
                    APIALARMINTEGRATION_PATH . 'source/php/', // The directory path of the module
                    'Module' // The class' file and class name (should be the same) withot .php extension
                );
                modularity_register_module(
                    APIALARMINTEGRATION_PATH . 'source/php/Module/FireDangerLevels', // The directory path of the module
                    'FireDangerLevels' // The class' file and class name (should be the same) withot .php extension
                );
                modularity_register_module(
                    APIALARMINTEGRATION_PATH . 'source/php/Module/AlarmList', // The directory path of the module
                    'AlarmList' // The class' file and class name (should be the same) withot .php extension
                );
            }
        });

        add_action('widgets_init', array($this, 'registerWidget'));

        new \ApiAlarmIntegration\Disturbance();
    }

    /**
     * Registers module
     */
    public function addModule()
    {
        new \ApiAlarmIntegration\Module();
        new \ApiAlarmIntegration\Module\AlarmList\AlarmList();
        new \ApiAlarmIntegration\Module\FireDangerLevels\FireDangerLevels();
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
     * Enqueues widget/module assets Scripts
     * @return void
     */
    public static function enqueueAlarmScripts()
    {
        wp_enqueue_script('api-alarm-integration', APIALARMINTEGRATION_URL . '/dist/'. CacheBust::name('js/api-alarm-integration.js'), array('jquery'), '1.0.0', true);
        wp_localize_script('api-alarm-integration', 'ApiAlarmIntegrationLang', array(
            'show_filters' => __('Show filters', 'api-alarm-integration'),
            'hide_filters' => __('Hide filters', 'api-alarm-integration')
        ));
    }

    /**
     * Enqueues widget/module assets CSS
     * @return void
     */
    public static function enqueueStyle()
    {
        wp_register_style('api-alarm-integration-css', APIALARMINTEGRATION_URL . '/dist/'. CacheBust::name('css/api-alarm-integration.css'), null, '1.0.0');
        wp_enqueue_style('api-alarm-integration-css');
    }
}

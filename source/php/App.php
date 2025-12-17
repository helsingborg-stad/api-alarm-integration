<?php

declare(strict_types=1);

namespace ApiAlarmIntegration;

use WpUtilService\Features\Enqueue\EnqueueManager;

class App
{
    static EnqueueManager $wpEnqueue;
    public function __construct(EnqueueManager $wpEnqueue)
    {
        self::$wpEnqueue = $wpEnqueue;

        add_action('init', static function () {
            if (function_exists('modularity_register_module')) {
                modularity_register_module(
                    APIALARMINTEGRATION_PATH . 'source/php/', // The directory path of the module
                    'Module', // The class' file and class name (should be the same) withot .php extension
                );
                modularity_register_module(
                    APIALARMINTEGRATION_PATH . 'source/php/Module/FireDangerLevels', // The directory path of the module
                    'FireDangerLevels', // The class' file and class name (should be the same) withot .php extension
                );
                modularity_register_module(
                    APIALARMINTEGRATION_PATH . 'source/php/Module/AlarmList', // The directory path of the module
                    'AlarmList', // The class' file and class name (should be the same) withot .php extension
                );
            }
        });

        add_action('widgets_init', [$this, 'registerWidget']);

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
        self::$wpEnqueue->add('js/api-alarm-integration.js', ['jquery'], '1.0.0', true)->with()->translation('ApiAlarmIntegrationLang', [
            'show_filters' => __('Show filters', 'api-alarm-integration'),
            'hide_filters' => __('Hide filters', 'api-alarm-integration'),
        ]);
    }

    /**
     * Enqueues widget/module assets CSS
     * @return void
     */
    public static function enqueueStyle()
    {
        self::$wpEnqueue->add('css/api-alarm-integration.css', [], '1.0.0');
    }
}

<?php

namespace ApiAlarmIntegration;

class App
{
    public function __construct()
    {
        add_action('Modularity', array($this, 'addModule'));
        add_action('widgets_init', array($this, 'registerWidget'));
    }

    public function addModule()
    {
        new \ApiAlarmIntegration\Module();
    }

    public function registerWidget()
    {
        register_widget('\ApiAlarmIntegration\Widget');
    }
}

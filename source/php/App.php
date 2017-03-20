<?php

namespace ApiAlarmIntegration;

class App
{
    public function __construct()
    {
        add_action('Modularity', array($this, 'addModule'));
    }

    public function addModule()
    {
        new \ApiAlarmIntegration\Module();
    }
}

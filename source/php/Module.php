<?php

namespace ApiAlarmIntegration;

class Module extends \Modularity\Module
{
    /**
     * Module args
     * @var array
     */
    public $args = array(
        'id' => 'alarms',
        'nameSingular' => 'Alarm',
        'namePlural' => 'Alarms',
        'description' => 'Lists alarms from API Alarm Manager',
        'supports' => array(),
        'icon' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iNzJweCIgaGVpZ2h0PSI3MnB4IiB2aWV3Qm94PSIwIDAgNzIgNzIiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgICA8IS0tIEdlbmVyYXRvcjogU2tldGNoIDQyICgzNjc4MSkgLSBodHRwOi8vd3d3LmJvaGVtaWFuY29kaW5nLmNvbS9za2V0Y2ggLS0+CiAgICA8dGl0bGU+bm91bl80NjYzMjBfY2M8L3RpdGxlPgogICAgPGRlc2M+Q3JlYXRlZCB3aXRoIFNrZXRjaC48L2Rlc2M+CiAgICA8ZGVmcz48L2RlZnM+CiAgICA8ZyBpZD0iUGFnZS0xIiBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSIgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj4KICAgICAgICA8ZyBpZD0ibm91bl80NjYzMjBfY2MiIGZpbGwtcnVsZT0ibm9uemVybyIgZmlsbD0iIzAwMDAwMCI+CiAgICAgICAgICAgIDxnIGlkPSJHcm91cCI+CiAgICAgICAgICAgICAgICA8cG9seWdvbiBpZD0iU2hhcGUiIHBvaW50cz0iMzYgMjUgNDYgOSA0MCA5IDQ1IDAgMzggMCAzMSAxNCAzNyAxNCI+PC9wb2x5Z29uPgogICAgICAgICAgICAgICAgPHBvbHlnb24gaWQ9IlNoYXBlIiBwb2ludHM9IjIxLjE1MDc2IDE5LjczNjUxIDI4LjIyMTggMjguMjIxOCAyMy45NzkxOSA5LjgzNzA0IDE5LjczNjU3IDE0LjA3OTY1IDE2LjkwODE0IDQuMTgwMTggMTEuOTU4MzcgOS4xMjk5NCAxNi45MDgxNCAyMy45NzkxOSI+PC9wb2x5Z29uPgogICAgICAgICAgICAgICAgPHBvbHlnb24gaWQ9IlNoYXBlIiBwb2ludHM9IjE0IDM1IDI1IDM2IDkgMjYgOSAzMiAwIDI3IDAgMzQgMTQgNDEiPjwvcG9seWdvbj4KICAgICAgICAgICAgICAgIDxwb2x5Z29uIGlkPSJTaGFwZSIgcG9pbnRzPSIyOC4yMjE4IDQzLjc3ODE0IDkuODM3MDQgNDguMDIwODEgMTQuMDc5NzEgNTIuMjYzNDMgNC4xODAxOCA1NS4wOTE4NiA5LjEyOTk0IDYwLjA0MTYzIDIzLjk3OTE5IDU1LjA5MTg2IDE5LjczNjU3IDUwLjg0OTI0Ij48L3BvbHlnb24+CiAgICAgICAgICAgICAgICA8cG9seWdvbiBpZD0iU2hhcGUiIHBvaW50cz0iMzYgNDcgMjYgNjMgMzIgNjMgMjcgNzIgMzQgNzIgNDEgNTggMzUgNTgiPjwvcG9seWdvbj4KICAgICAgICAgICAgICAgIDxwb2x5Z29uIGlkPSJTaGFwZSIgcG9pbnRzPSI1MC44NDkyNCA1Mi4yNjM0MyA0My43NzgyIDQzLjc3ODE0IDQ4LjAyMDgxIDYyLjE2Mjk2IDUyLjI2MzQzIDU3LjkyMDI5IDU1LjA5MTg2IDY3LjgxOTc2IDYwLjA0MTYzIDYyLjg3MDA2IDU1LjA5MTg2IDQ4LjAyMDgxIj48L3BvbHlnb24+CiAgICAgICAgICAgICAgICA8cG9seWdvbiBpZD0iU2hhcGUiIHBvaW50cz0iNTggMzEgNTggMzcgNDcgMzYgNjMgNDYgNjMgNDAgNzIgNDUgNzIgMzgiPjwvcG9seWdvbj4KICAgICAgICAgICAgICAgIDxwb2x5Z29uIGlkPSJTaGFwZSIgcG9pbnRzPSI0My43NzgyIDI4LjIyMTggNjIuMTYyOTYgMjMuOTc5MTkgNTcuOTIwMjkgMTkuNzM2NTEgNjcuODE5ODIgMTYuOTA4MDggNjIuODcwMDYgMTEuOTU4MzcgNDguMDIwODEgMTYuOTA4MDggNTIuMjYzNDMgMjEuMTUwNzYiPjwvcG9seWdvbj4KICAgICAgICAgICAgICAgIDxjaXJjbGUgaWQ9Ik92YWwiIGN4PSIzNiIgY3k9IjM2IiByPSI2Ij48L2NpcmNsZT4KICAgICAgICAgICAgPC9nPgogICAgICAgIDwvZz4KICAgIDwvZz4KPC9zdmc+'
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        // This will register the module
        $this->register(
            $this->args['id'],
            $this->args['nameSingular'],
            $this->args['namePlural'],
            $this->args['description'],
            $this->args['supports'],
            $this->args['icon']
        );

        // Add our template folder as search path for templates
        add_filter('Modularity/Module/TemplatePath', function ($paths) {
            $paths[] = APIALARMINTEGRATION_TEMPLATE_PATH;
            return $paths;
        });
    }

    public function script()
    {
        if (!$this->hasModule()) {
            return;
        }

        wp_enqueue_script('api-alarm-integration', APIALARMINTEGRATION_URL . '/dist/js/api-alarm-integration.dev.js', array('jquery'), '1.0.0', true);
    }
}

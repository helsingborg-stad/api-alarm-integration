<?php

declare(strict_types=1);

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group([
        'key' => 'group_58cfe8b6985c1',
        'title' => __('Alarm options', 'event-manager'),
        'fields' => [
            0 => [
                'default_value' => '',
                'placeholder' => '',
                'key' => 'field_58cfe8da63a69',
                'label' => __('Rest API url', 'event-manager'),
                'name' => 'api_url',
                'type' => 'url',
                'instructions' => __('Example: http://myalarmapi.tld/json', 'event-manager'),
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
            ],
            1 => [
                'default_value' => 10,
                'min' => 1,
                'max' => 100,
                'step' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'key' => 'field_58cfe93963a6a',
                'label' => __('Alarms per page', 'event-manager'),
                'name' => 'alarms_per_page',
                'type' => 'number',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
            ],
        ],
        'location' => [
            0 => [
                0 => [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'mod-alarms',
                ],
            ],
            1 => [
                0 => [
                    'param' => 'widget',
                    'operator' => '==',
                    'value' => 'api-alarm-widget',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ]);
}

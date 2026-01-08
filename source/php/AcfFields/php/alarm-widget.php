<?php

declare(strict_types=1);

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group([
        'key' => 'group_58d1432296838',
        'title' => __('Alarm widget', 'event-manager'),
        'fields' => [
            0 => [
                'default_value' => '',
                'maxlength' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'key' => 'field_58d1432574c5d',
                'label' => __('Titel', 'event-manager'),
                'name' => 'title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
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
                    'param' => 'widget',
                    'operator' => '==',
                    'value' => 'api-alarm-widget',
                ],
            ],
        ],
        'menu_order' => -10,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ]);
}

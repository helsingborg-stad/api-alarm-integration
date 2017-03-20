<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_58cfe8b6985c1',
    'title' => __('Alarm options', 'event-manager'),
    'fields' => array(
        0 => array(
            'default_value' => '',
            'placeholder' => '',
            'key' => 'field_58cfe8da63a69',
            'label' => __('Rest API url', 'event-manager'),
            'name' => 'api_url',
            'type' => 'url',
            'instructions' => __('Example: http://myalarmapi.tld/json', 'event-manager'),
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
        ),
        1 => array(
            'default_value' => 10,
            'min' => '',
            'max' => '',
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
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));
}
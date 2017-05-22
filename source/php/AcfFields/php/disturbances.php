<?php

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_58d24fce7d85a',
    'title' => __('Disturbances', 'event-manager'),
    'fields' => array(
        0 => array(
            'default_value' => 0,
            'message' => __('Enable disturbance messages', 'event-manager'),
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
            'key' => 'field_58d2501c0f51d',
            'label' => __('Activate', 'event-manager'),
            'name' => 'disturbnaces_enabled',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
        ),
        1 => array(
            'default_value' => '',
            'placeholder' => '',
            'key' => 'field_58d250070f51c',
            'label' => __('Api url', 'event-manager'),
            'name' => 'disturbances_api_url',
            'type' => 'url',
            'instructions' => __('The base url to the api alarm manager', 'event-manager'),
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_58d2501c0f51d',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
        ),
        2 => array(
            'multiple' => 1,
            'allow_null' => 0,
            'choices' => array(
            ),
            'default_value' => array(
            ),
            'ui' => 1,
            'ajax' => 0,
            'placeholder' => '',
            'return_format' => 'value',
            'key' => 'field_58d26663441cc',
            'label' => __('Filter places', 'event-manager'),
            'name' => 'disturbances_places',
            'type' => 'select',
            'instructions' => __('You will first need to set your api url and save this page before places will be filled with alternatives. Leave blank to get disturbances from all places.', 'event-manager'),
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_58d2501c0f51d',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
        ),
        3 => array(
            'layout' => 'vertical',
            'choices' => array(
                'big' => __('Big disturbances', 'event-manager'),
                'small' => __('Small disturbances', 'event-manager'),
            ),
            'default_value' => array(
            ),
            'allow_custom' => 0,
            'save_custom' => 0,
            'toggle' => 0,
            'return_format' => 'value',
            'key' => 'field_58d250a30f51e',
            'label' => __('Output automatically', 'event-manager'),
            'name' => 'disturbances_output_automatically',
            'type' => 'checkbox',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_58d2501c0f51d',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
        ),
        4 => array(
            'default_value' => '',
            'maxlength' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'key' => 'field_58d2760046254',
            'label' => __('Output selector for big disturbance', 'event-manager'),
            'name' => 'output_selector_big_disturbance',
            'type' => 'text',
            'instructions' => __('CSS Selector for where to output big disturbances. Defaults to body.', 'event-manager'),
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_58d250a30f51e',
                        'operator' => '==',
                        'value' => 'big',
                    ),
                    1 => array(
                        'field' => 'field_58d2501c0f51d',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
        ),
        5 => array(
            'default_value' => '',
            'maxlength' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'key' => 'field_58d2764846255',
            'label' => __('Output selector for small disturbance', 'event-manager'),
            'name' => 'output_selector_small_disturbance',
            'type' => 'text',
            'instructions' => __('CSS Selector for where to output small disturbances. Defaults to body.', 'event-manager'),
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_58d250a30f51e',
                        'operator' => '==',
                        'value' => 'small',
                    ),
                    1 => array(
                        'field' => 'field_58d2501c0f51d',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
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
                'param' => 'options_page',
                'operator' => '==',
                'value' => 'api-alarm-integration-disturbances',
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

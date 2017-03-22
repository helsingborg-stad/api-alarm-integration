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
            'name' => 'api_url',
            'type' => 'url',
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
        2 => array(
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
            'name' => 'output_automatically',
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
        3 => array(
            'message' => __('You can output disturbance messages manually with shortcode(s): [disturbances type="small"] or [disturbances type="big"].', 'event-manager'),
            'esc_html' => 0,
            'new_lines' => 'wpautop',
            'key' => 'field_58d250d40f51f',
            'label' => __('Output with shortcode', 'event-manager'),
            'name' => '',
            'type' => 'message',
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
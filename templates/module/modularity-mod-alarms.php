<?php

$classes = apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args);
$title = $module->post_title;

if ($module->hideTitle) {
    $title = false;
}

$apiUrl = trailingslashit(get_field('api_url', $module->ID));
$alarmsPerPage = get_field('alarms_per_page', $module->ID);

include APIALARMINTEGRATION_TEMPLATE_PATH . 'alarm-list.php';

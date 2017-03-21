<?php

$classes = array('box', 'box-panel');
$title = get_field('title', 'widget_' . $args['widget_id']);

$apiUrl = trailingslashit(get_field('api_url', 'widget_' . $args['widget_id']));
$places = ApiAlarmIntegration\Module::getPlaces($apiUrl);
$alarmsPerPage = get_field('alarms_per_page', 'widget_' . $args['widget_id']);

require APIALARMINTEGRATION_TEMPLATE_PATH . '/alarm-list.php';

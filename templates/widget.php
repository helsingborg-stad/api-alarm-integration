<?php

$classes = array('box', 'box-panel');
$title = 'titel';

$apiUrl = trailingslashit(get_field('api_url', 'widget_' . $args['widget_id']));
$places = ApiAlarmIntegration\Module::getPlaces($apiUrl);
$alarmsPerPage = get_field('alarms_per_page', 'widget_' . $args['widget_id']);

require APIALARMINTEGRATION_TEMPLATE_PATH . '/alarm-list.php';

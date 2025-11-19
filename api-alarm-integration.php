<?php

/**
 * Plugin Name:       API Alarm Integration
 * Plugin URI:        https://github.com/helsingborg-stad/api-alarm-integration
 * Description:       Shows alarms from API Alarm Manager
 * Version: 5.0.2
 * Author:            Kristoffer Svanmark
 * Author URI:        https://github.com/helsingborg-stad
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       api-alarm-integration
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('APIALARMINTEGRATION_PATH', plugin_dir_path(__FILE__));
define('APIALARMINTEGRATION_URL', plugins_url('', __FILE__));
define('APIALARMINTEGRATION_TEMPLATE_PATH', APIALARMINTEGRATION_PATH . 'templates/');
define('APIALARMINTEGRATION_MODULE_VIEW_PATH', APIALARMINTEGRATION_PATH . 'templates/');

load_plugin_textdomain('api-alarm-integration', false, plugin_basename(dirname(__FILE__)) . '/languages');

if (file_exists(APIALARMINTEGRATION_PATH . 'vendor/autoload.php')) {
    require_once APIALARMINTEGRATION_PATH . 'vendor/autoload.php';
}
require_once APIALARMINTEGRATION_PATH . 'Public.php';

add_filter( '/Modularity/externalViewPath', function($arr) 
    {
        $arr['mod-fire-danger-leve']    = APIALARMINTEGRATION_MODULE_VIEW_PATH;
        $arr['mod-alarms']              = APIALARMINTEGRATION_MODULE_VIEW_PATH;
        $arr['mod-alarm-list']          = APIALARMINTEGRATION_MODULE_VIEW_PATH;
        return $arr;
    }, 10, 3
);

/** Icon fill **/
add_filter('Municipio/Admin/Acf/PrefillIconChoice', function (array $items) {
    $items[] = 'icon';
    return $items;
});

// Acf auto import and export
add_action('plugins_loaded', function () {
    $acfExportManager = new \AcfExportManager\AcfExportManager();
    $acfExportManager->setTextdomain('event-manager');
    $acfExportManager->setExportFolder(APIALARMINTEGRATION_PATH . 'source/php/AcfFields/');
    $acfExportManager->autoExport(array(
        'alarm-module' => 'group_58cfe8b6985c1',
        'alarm-widget' => 'group_58d1432296838',
        'disturbances' => 'group_58d24fce7d85a',
        'alarm-list'   => 'group_686e3d0e359d8',
    ));
    $acfExportManager->import();
});

// Start application
new ApiAlarmIntegration\App();

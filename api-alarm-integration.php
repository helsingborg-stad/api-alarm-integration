<?php

/**
 * Plugin Name:       API Alarm Integration
 * Plugin URI:        https://github.com/helsingborg-stad/api-alarm-integration
 * Description:       Shows alarms from API Alarm Manager
 * Version:           1.0.0
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

load_plugin_textdomain('api-alarm-integration', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once APIALARMINTEGRATION_PATH . 'vendor/autoload.php';
require_once APIALARMINTEGRATION_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once APIALARMINTEGRATION_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new ApiAlarmIntegration\Vendor\Psr4ClassLoader();
$loader->addPrefix('ApiAlarmIntegration', APIALARMINTEGRATION_PATH);
$loader->addPrefix('ApiAlarmIntegration', APIALARMINTEGRATION_PATH . 'source/php/');
$loader->register();

// Acf auto import and export
$acfExportManager = new \AcfExportManager\AcfExportManager();
$acfExportManager->setTextdomain('event-manager');
$acfExportManager->setExportFolder(APIALARMINTEGRATION_PATH . 'source/php/AcfFields/');
$acfExportManager->autoExport(array(

));
$acfExportManager->import();

// Start application
new ApiAlarmIntegration\App();

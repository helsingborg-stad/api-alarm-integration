<?php

namespace ApiAlarmIntegration;

class FireDangerLevels extends \Modularity\Module
{
    public $slug = 'fire-danger-levels';
    public $icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPg0KPHN2ZyB3aWR0aD0iODAwcHgiIGhlaWdodD0iODAwcHgiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNNS45MjYgMjAuNTc0YTcuMjYgNy4yNiAwIDAgMCAzLjAzOSAxLjUxMWMuMTA3LjAzNS4xNzktLjEwNS4xMDctLjE3NS0yLjM5NS0yLjI4NS0xLjA3OS00Ljc1OC0uMTA3LTUuODczLjY5My0uNzk2IDEuNjgtMi4xMDcgMS42MDgtMy44NjUgMC0uMTc2LjE4LS4zMTcuMzIyLS4yMTEgMS4zNTkuNzAzIDIuMjg4IDIuMjUgMi41MzggMy41MTUuMzk0LS4zODYuNTM3LS45ODQuNTM3LTEuNTExIDAtLjE3Ni4yMTQtLjMxNy4zOTMtLjE3NiAxLjI4NyAxLjE2IDMuNTAzIDUuMDk3LS4wNzIgOC4xOS0uMDcxLjA3MSAwIC4yMTIuMDcyLjE3N2E4Ljc2MSA4Ljc2MSAwIDAgMCAzLjAwMy0xLjQ0MmM1LjgyNy00LjUgMi4wMzctMTIuNDgtLjQzLTE1LjExNi0uMzIxLS4zMTctLjg5My0uMTA2LS44OTMuMzUxLS4wMzYuOTUtLjMyMiAyLjAwNC0xLjA3MiAyLjcwNy0uNTcyLTIuMzktMi40NzgtNS4xMDUtNS4xOTUtNi40NDEtLjM1Ny0uMTc2LS43ODYuMTA1LS43NS40OTIuMDcgMy4yNy0yLjA2MyA1LjM1Mi0zLjkyMiA4LjA1OS0xLjY0NSAyLjQyNS0yLjcxNyA2Ljg5LjgyMiA5LjgwOHoiIGZpbGw9IiMwMDAwMDAiLz48L3N2Zz4=';
    public $supports = array();
    public $plugin = array();
    public $cacheTtl = MINUTE_IN_SECONDS * 15;
    public $hideTitle  = false;
    public $isDeprecated = false;
    private $apiUrl = null;
    public $templateDir = APIALARMINTEGRATION_TEMPLATE_PATH;
    public $isBlockCompatible = false;

    public function init()
    {
        $this->nameSingular = __('Fire Danger Levels', 'api-alarm-integration');
        $this->namePlural = __('Fire Danger Levels', 'api-alarm-integration');
        $this->description = __('Outputs a list of fire danger levels from Alarm API', 'api-alarm-integration');
    }

    public function data(): array
    {
        $options = $this->getFields();
        $this->apiUrl = $options['api_url'];

        $apiDateTimeChanged = $this->getDateTimeChanged();
        $timeFormat = get_option('time_format');
        $dateFormat = get_option('date_format');
        $dateTimeFormat = "{$dateFormat} {$timeFormat}";
        $dateTimeChanged = wp_date($dateTimeFormat, $apiDateTimeChanged);

        $data['refreshInterval'] = MINUTE_IN_SECONDS * 15;
        $data['notices'] = $this->getNoticesData();
        $data['dateTimeChangedLabel'] = sprintf(__('Updated at %s', 'api-alarm-integration'), $dateTimeChanged);
        $data['isAjaxRequest'] = wp_doing_ajax() || defined('REST_REQUEST');
        $data['ID'] = $this->ID;

        return $data;
    }

    private function getNoticesData()
    {
        $data = $this->getDataFromApi();

        return array_map(function ($fdl) {
            return [
                'title' => $fdl['place'],
                'text' => $this->getNoticeTextFromLevel($fdl['level']),
                'type' => $this->getNoticeTypeFromLevel($fdl['level']),
                'iconName' => $this->getIconNameFromLevel($fdl['level']),
            ];
        }, $data['places'] ?? []);
    }

    private function getDateTimeChanged()
    {
        $data = $this->getDataFromApi();
        return $data['dateTimeChanged'];
    }

    private function getDataFromApi()
    {
        $response = wp_remote_get($this->apiUrl);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        return $data;
    }

    private function getNoticeTypeFromLevel($level)
    {
        switch ($level) {
            case '4':
            case '5':
                return 'danger';
            case '5E': 
                return 'danger-dark';
            default:
                return 'success';
        }
    }

    private function getIconNameFromLevel($level)
    {
        switch ($level) {
            case '4':
            case '5':
            case '5E':
                return 'error';
            default:
                return 'check_circle';
        }
    }

    private function getNoticeTextFromLevel($level)
    {
        $prefix = "$level -";
        switch ($level) {
            case '4':
            case '5':
            case '5E':
                return sprintf(__('%s Eldningsförbud', 'api-alarm-integration'), $prefix);
            default:
                return sprintf(__('%s Ingen varning', 'api-alarm-integration'), $prefix);
        }
    }

    /**
     * Available "magic" methods for modules:
     * init()            What to do on initialization (if you must, use __construct with care, this will probably break stuff!!)
     * data()            Use to send data to view (return array)
     * style()           Enqueue style only when module is used on page
     * script()          Enqueue script only when module is used on page
     * adminEnqueue()    Enqueue scripts for the module edit/add page in admin
     * template()        Return the view template (blade) the module should use when displayed
     */
}
<?php

namespace ApiAlarmIntegration\Module\AlarmList;

class AlarmList extends \Modularity\Module
{
    public $slug = 'alarm-list';
    public $icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPg0KPHN2ZyB3aWR0aD0iODAwcHgiIGhlaWdodD0iODAwcHgiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNNS45MjYgMjAuNTc0YTcuMjYgNy4yNiAwIDAgMCAzLjAzOSAxLjUxMWMuMTA3LjAzNS4xNzktLjEwNS4xMDctLjE3NS0yLjM5NS0yLjI4NS0xLjA3OS00Ljc1OC0uMTA3LTUuODczLjY5My0uNzk2IDEuNjgtMi4xMDcgMS42MDgtMy44NjUgMC0uMTc2LjE4LS4zMTcuMzIyLS4yMTEgMS4zNTkuNzAzIDIuMjg4IDIuMjUgMi41MzggMy41MTUuMzk0LS4zODYuNTM3LS45ODQuNTM3LTEuNTExIDAtLjE3Ni4yMTQtLjMxNy4zOTMtLjE3NiAxLjI4NyAxLjE2IDMuNTAzIDUuMDk3LS4wNzIgOC4xOS0uMDcxLjA3MSAwIC4yMTIuMDcyLjE3N2E4Ljc2MSA4Ljc2MSAwIDAgMCAzLjAwMy0xLjQ0MmM1LjgyNy00LjUgMi4wMzctMTIuNDgtLjQzLTE1LjExNi0uMzIxLS4zMTctLjg5My0uMTA2LS44OTMuMzUxLS4wMzYuOTUtLjMyMiAyLjAwNC0xLjA3MiAyLjcwNy0uNTcyLTIuMzktMi40NzgtNS4xMDUtNS4xOTUtNi40NDEtLjM1Ny0uMTc2LS43ODYuMTA1LS43NS40OTIuMDcgMy4yNy0yLjA2MyA1LjM1Mi0zLjkyMiA4LjA1OS0xLjY0NSAyLjQyNS0yLjcxNyA2Ljg5LjgyMiA5LjgwOHoiIGZpbGw9IiMwMDAwMDAiLz48L3N2Zz4=';
    public $supports = array();
    public $plugin = array();
    public $cacheTtl = MINUTE_IN_SECONDS * 1;
    public $refreshInterval = MINUTE_IN_SECONDS * 15;
    public $hideTitle  = false;
    public $isDeprecated = false;
    private $apiUrl = null;
    public $templateDir = APIALARMINTEGRATION_TEMPLATE_PATH;
    public $isBlockCompatible = false;

    public function init()
    {
        $this->nameSingular = __('Alarm List', 'api-alarm-integration');
        $this->namePlural = __('Alarm Lists', 'api-alarm-integration');
        $this->description = __('Outputs a alarm list from the api', 'api-alarm-integration');
    }

    public function data(): array
    {
        $this->apiUrl = 'https://alarm.helsingborg.io/json/wp/v2/alarm/?per_page=100&page=1';
        $apiDateTimeChanged = $this->getDateTimeChanged();
        $dateTimeChanged = $this->formatApiDateTime($apiDateTimeChanged);

        $data['refreshInterval']        = $this->refreshInterval ?? 0;
        $data['alarm']                  = $this->getData();


        $data['dateTimeChangedLabel']   = sprintf(__('Updated at %s', 'api-alarm-integration'), $dateTimeChanged);
        $data['isAjaxRequest']          = wp_doing_ajax() || defined('REST_REQUEST');
        $data['ID']                     = $this->ID ?? null;

        return $data;
    }

    private function formatApiDateTime($apiDateTime)
    {
        $timeFormat = get_option('time_format');
        $dateFormat = get_option('date_format');
        $dateTimeFormat = "{$dateFormat} {$timeFormat}";
        return wp_date($dateTimeFormat, $apiDateTime);
    }

    private function getData()
    {
        try {
            $data = $this->getDataFromApi();

            $alarm = array_map(function ($item) {
                return (object) [
                    'title'         => $item['title']['rendered'] ?? '',
                    'icon'          => $this->getIcon($item['title']['rendered'] ?? ''),
                    'place'         => $item['place']['name'] ?? '',
                    'time'          => $this->formatIncidentTime($item['date'] ?? ''),
                    'date'          => $this->formatIncidentDate($item['date'] ?? ''),
                    'date_time'     => $this->formatIncidentDateTime($item['date'] ?? ''),
                    'unit'          => $item['station']['title'] ?? '',
                    'level'         => $item['extend'] ?? '',
                    'streetname'    => $item['address'] ?? 'Okänd adress',
                    'city'          => $item['place']['name'] ?? '',
                    'location_geo'  => (object) [
                        'lat' => $item['coordinate_x'] ?? '',
                        'lng' => $item['coordinate_y'] ?? ''
                    ],
                ];
            }, $data ?? []);


            return $this->consolidateAlarmData($alarm);

        } catch (\Throwable $error) {
            error_log("Could not get fire danger levels from Alarm API.");
            return [];
        }
    }

    private function consolidateAlarmData($alarm)
    {
        $consolidatedMap = [];

        foreach ($alarm as $item) {
            $key = metaphone($item->title . $item->streetname) . $item->date;

            if (!isset($consolidatedMap[$key])) {
                // Initialize group and add current item as the first in moredetails
                $item->moredetails = [$item];
                $consolidatedMap[$key] = $item;
            } else {
                // Add to existing group
                $consolidatedMap[$key]->moredetails[] = $item;
            }
        }

        return array_values($consolidatedMap);
    }

    private function formatIncidentTime($incidentTime)
    {
        $timeFormat = get_option('time_format');
        return wp_date($timeFormat, strtotime($incidentTime));
    }

    private function formatIncidentDate($incidentTime)
    {
        return wp_date("Ymd", strtotime($incidentTime));
    }

    private function formatIncidentDateTime($incidentDateTime)
    {
        $timeFormat = get_option('time_format');
        $dateFormat = get_option('date_format');
        $dateTimeFormat = "{$dateFormat} {$timeFormat}";
        return wp_date($dateTimeFormat, strtotime($incidentDateTime));
    }

    private function getIcon($title) {
        $iconMap = [
            'automatlarm'  => 'detector',
            'brand'        => 'emergency_heat',
            'rök'          => 'detector_smoke',
            'trafikolycka' => 'car_crash',
            'lyfthjälp'    => 'exercise',
            'räddning'     => 'support',
            'drunkning'    => 'pool',
            'ambulans'     => 'e911_emergency',
            'övrigt'       => 'miscellaneous',
            'gaslarm'      => 'detector_co',
            'gas'          => 'detector_co',
            'drivmedel'    => 'local_gas_station',
            'hinder'       => 'alt_route',
            
        ];

        foreach ($iconMap as $keyword => $icon) {
            if (stripos($title, $keyword) !== false) {
                return $icon;
            }
        }

        return 'info';
    }

    private function getDateTimeChanged(): string
    {
        $data = $this->getDataFromApi();
        return $data['dateTimeChanged'] ?? '';
    }

    private function getDataFromApi()
    {
        $response = wp_remote_get(
            $this->appendCacheBustQueryParam(
                $this->apiUrl,
                $this->cacheTtl
            )
        );
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        return $data;
    }

    private function getCacheBustKey($url, $ttl = 5)
    {
        return md5($url . ceil(time() / $ttl));
    }

    private function appendCacheBustQueryParam($url, $ttl = 5)
    {
        return add_query_arg(
            'cache_bust',
            $this->getCacheBustKey($url, $ttl),
            $url
        );
    }

    private function getNoticeTypeFromLevel($level): string
    {
        return [
            '2' => 'danger',
            '3' => 'danger-dark',
        ][$level] ?? 'success';
    }

    private function getIconNameFromLevel($level): string
    {
        return [
            '2' => 'info',
            '3' => 'error',
        ][$level] ?? 'check_circle';
    }

    private function getNoticeTextFromLevel($level): string
    {
        $fireBanText = _x('Fire ban', 'fire danger level', 'api-alarm-integration');
        $fireBanTextStrict = _x('Strict fire ban', 'fire danger level', 'api-alarm-integration');
        $noRiskText = _x('No fire ban', 'fire danger level', 'api-alarm-integration');

        $text = [
            '2' => $fireBanText,
            '3' => $fireBanTextStrict
        ][$level] ?? $noRiskText;

        return $text;
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

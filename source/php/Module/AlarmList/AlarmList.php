<?php

namespace ApiAlarmIntegration\Module\AlarmList;

class AlarmList extends \Modularity\Module
{
    public $slug = 'alarm-list';
    public $icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPg0KPHN2ZyB3aWR0aD0iODAwcHgiIGhlaWdodD0iODAwcHgiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNNS45MjYgMjAuNTc0YTcuMjYgNy4yNiAwIDAgMCAzLjAzOSAxLjUxMWMuMTA3LjAzNS4xNzktLjEwNS4xMDctLjE3NS0yLjM5NS0yLjI4NS0xLjA3OS00Ljc1OC0uMTA3LTUuODczLjY5My0uNzk2IDEuNjgtMi4xMDcgMS42MDgtMy44NjUgMC0uMTc2LjE4LS4zMTcuMzIyLS4yMTEgMS4zNTkuNzAzIDIuMjg4IDIuMjUgMi41MzggMy41MTUuMzk0LS4zODYuNTM3LS45ODQuNTM3LTEuNTExIDAtLjE3Ni4yMTQtLjMxNy4zOTMtLjE3NiAxLjI4NyAxLjE2IDMuNTAzIDUuMDk3LS4wNzIgOC4xOS0uMDcxLjA3MSAwIC4yMTIuMDcyLjE3N2E4Ljc2MSA4Ljc2MSAwIDAgMCAzLjAwMy0xLjQ0MmM1LjgyNy00LjUgMi4wMzctMTIuNDgtLjQzLTE1LjExNi0uMzIxLS4zMTctLjg5My0uMTA2LS44OTMuMzUxLS4wMzYuOTUtLjMyMiAyLjAwNC0xLjA3MiAyLjcwNy0uNTcyLTIuMzktMi40NzgtNS4xMDUtNS4xOTUtNi40NDEtLjM1Ny0uMTc2LS43ODYuMTA1LS43NS40OTIuMDcgMy4yNy0yLjA2MyA1LjM1Mi0zLjkyMiA4LjA1OS0xLjY0NSAyLjQyNS0yLjcxNyA2Ljg5LjgyMiA5LjgwOHoiIGZpbGw9IiMwMDAwMDAiLz48L3N2Zz4=';
    public $supports = array();
    public $plugin = array();
    public $cacheTtl = MINUTE_IN_SECONDS * 1;
    public $refreshInterval = 15;
    public $hideTitle  = false;
    public $isDeprecated = false;
    private $apiUrl = null;
    public $templateDir = APIALARMINTEGRATION_TEMPLATE_PATH;
    public $isBlockCompatible = false;

    public function init()
    {
        $this->nameSingular = __('Alarm List', 'api-alarm-integration');
        $this->namePlural = __('Alarm Lists', 'api-alarm-integration');
        $this->description = __('Outputs an alarm list from the API', 'api-alarm-integration');
    }

    public function data(): array
    {
        //Get module config
        $fields = $this->getFields();

        //Get remote resource
        if($fields && isset($fields['mod_alarm_api_url']) && !empty($fields['mod_alarm_api_url'])) {
            $this->apiUrl = $fields['mod_alarm_api_url'];
        }

        $data['refreshInterval']        = $this->refreshInterval ?? 0;
        $data['alarm']                  = $this->getData($fields);

        $data['dateTimeChangedLabel']   = sprintf(__('Last updated at %s', 'api-alarm-integration'), $this->getDateTimeChanged() ?: '--:--');
        $data['isAjaxRequest']          = $this->isAjaxRequest();
        $data['ID']                     = $this->ID ?? null;

        $data['communicationError']     = is_wp_error($data['alarm']) ? $data['alarm'] : false;

        return $data;
    }

    /*
     * Return true if the request is an AJAX request.
     */
    private function isAjaxRequest()
    {
        return wp_doing_ajax() || defined('REST_REQUEST');
    }

    /**
     * Formats the API date and time to the WordPress date and time format.
     *
     * @param int $apiDateTime The API date and time in Unix timestamp format.
     * @return string The formatted date and time.
     */
    private function formatApiDateTime($apiDateTime)
    {
        $timeFormat = get_option('time_format');
        $dateFormat = get_option('date_format');
        $dateTimeFormat = "{$dateFormat} {$timeFormat}";
        return wp_date($dateTimeFormat, $apiDateTime);
    }

    /**
     * Fetches data from the API and decodes it from JSON.
     *
     * @return array The decoded data from the API.
     */
    private function getData($fields)
    {
        try {
            $data = $this->getDataFromApi();

            if(is_wp_error($data)) {
                return $data;
            }

            $alarm = array_map(function ($item) use ($fields) {
                return (object) [
                    'title'         => $item['title']['rendered'] ?? '',
                    'icon'          => $this->getIcon($item['title']['rendered'] ?? '', $fields['mod_alarm_icons_map'] ?? []),
                    'time'          => $this->formatIncidentTime($item['date_gmt'] ?? ''),
                    'date'          => $this->formatIncidentDate($item['date_gmt'] ?? ''),
                    'date_time'     => $this->formatIncidentDateTime($item['date_gmt'] ?? ''),
                    'date_day'      => $this->formatIncidentDay($item['date_gmt'] ?? ''),
                    'unit'          => $item['station']['title'] ?? '',
                    'level'         => $item['extend'] ?? '',
                    'level_numeric' => $this->convertExtendToNumeric($item['extend'] ?? '', $fields),
                    'level_color'   => $this->convertExtendToColor($item['extend'] ?? '', $fields),
                    'level_label'   => $this->getExtendLabel($item['extend'] ?? ''),
                    'streetname'    => $this->formatAdress($item['address'] ?? __("Unknown address", 'api-alarm-integration')),
                    'city'          => $item['place'][0]['name'] ?? '',
                    'location_geo'  => (object) [
                        'lat' => $item['coordinate_x'] ?? '',
                        'lng' => $item['coordinate_y'] ?? ''
                    ],
                ];
            }, $data ?? []);

            return $this->consolidateAlarmData($alarm);

        } catch (\Throwable $error) {
            error_log("Could not get alarms from Alarm API: " . $error->getMessage());
            return [];
        }
    }

    /**
     * Returns a formatted label for the extend level.
     *
     * @param string $extend The extend level.
     * @return string The formatted label.
     */
    private function getExtendLabel($extend)
    {
        if($extend === null || $extend === '') {
            return __('No priority', 'api-alarm-integration');
        }
        return __('Priority', 'api-alarm-integration') . ": " . $extend; 
    }

    /**
     * Formats the incident day for display.
     *
     * @param string $incidentTime The incident time in ISO 8601 format.
     * @return string The formatted day.
     */
    private function formatIncidentDay($incidentTime)
    {
        if (wp_date('Y-m-d', strtotime($incidentTime)) == wp_date('Y-m-d')) {
            return __('Today', 'api-alarm-integration');
        }
        if (wp_date('Y-m-d', strtotime($incidentTime)) === wp_date('Y-m-d', strtotime('-1 day'))) {
            return __('Yesterday', 'api-alarm-integration');
        }
        return wp_date('l j/n', strtotime($incidentTime));
    }

    /**
     * Converts the extend level to a numeric value.
     *
     * @param string $extend The extend level.
     * @return int|null The numeric value or null if not found.
     */
    private function convertExtendToNumeric($extend, $fields)
    {
        $severityMap = [
            ($fields['mod_alarm_severity_keyword_level_1'] ?: __("Low", 'api-alarm-integration'))     => 1,
            ($fields['mod_alarm_severity_keyword_level_2'] ?: __("Medium", 'api-alarm-integration'))  => 2,
            ($fields['mod_alarm_severity_keyword_level_3'] ?: __("High", 'api-alarm-integration'))    => 3,
        ];

        foreach ($severityMap as $keyword => $level) {
            if (stripos($extend, $keyword) !== false) {
                return $level;
            }
        }

        return 1;
    }

    /**
     * Converts the extend level to a color string.
     *
     * @param string $level The extend level.
     * @return string The color string.
     */
    private function convertExtendToColor($level, $fields)
    {
        $level = $this->convertExtendToNumeric($level, $fields);
        switch ($level) {
            case 2:
                return 'warning';
            case 3:
                return 'danger';
            default:
                return $fields['mod_alarm_severity_level_1_color'] ? 'primary' : 'info';
        }
    }

    /**
     * Formats the address by removing single characters and extra spaces.
     *
     * @param string $address The address to format.
     * @return string The formatted address.
     */
    private function formatAdress($address)
    {
        $address = preg_replace('/(?<=\s|^)[\p{L}]{1}(?=\s|$)/u', '', $address);
        $address = str_replace('-', '', $address);
        return trim($address);
    }

    /**
     * Consolidates alarm data by grouping items that sound the same.
     *
     * @param array $alarm The array of alarm items to consolidate.
     * @return array The consolidated array of alarm items.
     */
    private function consolidateAlarmData($alarm)
    {
        $consolidatedMap = [];
        foreach ($alarm as $item) {
            $key = metaphone($item->title . $item->streetname) . $item->date;
            if (!isset($consolidatedMap[$key])) {
                $item->moredetails      = [$item];
                $consolidatedMap[$key]  = $item;
            } else {
                $consolidatedMap[$key]->moredetails[] = $item;
            }
        }
        return array_values($consolidatedMap);
    }

    /**
     * Formats the incident time.
     *
     * @param string $incidentTime The incident time in ISO 8601 format.
     * @return string The formatted time.
     */
    private function formatIncidentTime($incidentTime)
    {
        $timeFormat = get_option('time_format');
        return wp_date($timeFormat, strtotime($incidentTime));
    }

    /**
     * Formats the incident date.
     *
     * @param string $incidentTime The incident time in ISO 8601 format.
     * @return string The formatted date in Ymd format.
     */
    private function formatIncidentDate($incidentTime)
    {
        return wp_date("Ymd", strtotime($incidentTime));
    }

    /**
     * Formats the incident date and time.
     *
     * @param string $incidentDateTime The incident date and time in ISO 8601 format.
     * @return string The formatted date and time.
     */
    private function formatIncidentDateTime($incidentDateTime)
    {
        $timeFormat = get_option('time_format');
        $dateFormat = get_option('date_format');
        $dateTimeFormat = "{$dateFormat} {$timeFormat}";
        return wp_date($dateTimeFormat, strtotime($incidentDateTime));
    }

    /**
     * Returns an icon based on the title of the alarm.
     *
     * @param string $title The title of the alarm.
     * @param array $icons The icon map array with keyword/icon pairs.
     * @return string The icon name.
     */
    private function getIcon($title, array $icons) {
        foreach ($icons as $item) {
            if (!isset($item['keyword'], $item['icon'])) {
                continue;
            }

            if (stripos($title, $item['keyword']) !== false) {
                return $item['icon'];
            }
        }
        return 'info';
    }

    /**
     * Gets the date and time when the data was last changed.
     *
     * @return string The formatted date and time.
     */
    private function getDateTimeChanged(): string
    {
        return wp_date('H:i:s', time());
    }

    /**
     * Fetches data from the API and decodes it from JSON.
     *
     * @return array The decoded data from the API.
     */
    private function getDataFromApi()
    {
        $response = wp_remote_get(
            $this->appendCacheBustQueryParam(
                $this->apiUrl,
                $this->cacheTtl
            )
        );

        if (is_wp_error($response)) {
            return new \WP_Error(
                'api_alarm_integration_error',
                __('Could not connect to the source', 'api-alarm-integration'),
                [
                    'status' => 500,
                    'details' => __('Please try again at a later time.', 'api-alarm-integration')
                ]
            );
        }

        if (wp_remote_retrieve_response_code($response) !== 200) {
            return new \WP_Error(
                'api_alarm_integration_error',
                __('Unexpected answer from the resource', 'api-alarm-integration'),
                [
                    'status' => 500,
                    'details' => __('Please try again at a later time.', 'api-alarm-integration')
                ]
            );
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        return $data;
    }

    /**
     * Generates a cache busting key based on the URL and TTL.
     *
     * @param string $url The URL to generate the key for.
     * @param int $ttl The time-to-live in seconds.
     * @return string The generated cache busting key.
     */
    private function getCacheBustKey($url, $ttl = 5)
    {
        return md5($url . ceil(time() / $ttl));
    }

    /**
     * Appends a cache busting query parameter to the URL.
     *
     * @param string $url The URL to append the query parameter to.
     * @param int $ttl The time-to-live in seconds.
     * @return string The URL with the cache busting query parameter appended.
     */
    private function appendCacheBustQueryParam($url, $ttl = 5)
    {
        return add_query_arg(
            'cache_bust',
            $this->getCacheBustKey($url, $ttl),
            $url
        );
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

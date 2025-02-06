<?php

namespace LocalClasses\Lib;

use \LocalClasses\Interfaces\GeoIpInterface,
    \Bitrix\Main\Web\HttpClient,
    \Bitrix\Main\Config\Configuration;

class IpStackProvider implements GeoIpInterface
{

    private static array|null $geoIpServerInfo = [];
    private static object|null $instance = null;

    private function __construct()
    {
    }

    public static function getInfo(string $ip): array
    {
        if (empty($ip)) {
            throw new \Exception('empty ip address');
        }
        $httpClient = new HttpClient();
        $url = self::$geoIpServerInfo['server'] . $ip .
            '?access_key=' . self::$geoIpServerInfo['secret_token'];

        return json_decode($httpClient->get($url), true);
    }


    public static function getErrors(array $response): array
    {
        if (!empty($response['error'])) {
            return [
                'success' => false,
                'error' => $response['error']['info'],
            ];
        } elseif (empty($response)) {
            return [
                'success' => false,
                'error' => 'empty response',
            ];
        }
        return ['success' => true];
    }


    public static function init(): object
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        self::$geoIpServerInfo = Configuration::getValue("local_geo_ip_info");
        if (empty(self::$geoIpServerInfo['secret_token'])) {
            throw new \Exception('empty secret token');
        } elseif (empty(self::$geoIpServerInfo['server'])) {
            throw new \Exception('empty server url for geo ip');
        }
        return self::$instance;
    }

}
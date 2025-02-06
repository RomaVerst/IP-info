<?php
namespace LocalClasses\ORM;

use Bitrix\Main\Entity;

class GeoIpInfoLocalTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'geo_ip_info_local';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),
            new Entity\StringField('UF_IP_ADDRESS', [
                'required' => true
            ]),
            new Entity\StringField('UF_TYPE'),
            new Entity\StringField('UF_CONTINENT_NAME'),
            new Entity\StringField('UF_COUNTRY_NAME'),
            new Entity\StringField('UF_REGION_NAME'),
            new Entity\StringField('UF_CITY'),
            new Entity\StringField('UF_LATITUDE'),
            new Entity\StringField('UF_LONGITUDE'),
        ];
    }
}
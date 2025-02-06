<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

/** @var array $arCurrentValues */

$arComponentParameters = [
    'PARAMETERS' => [
        'CACHE_TIME' => ['DEFAULT' => 36000000],
        'GEO_IP_PROVIDER' => [
            'NAME' => Loc::getMessage('NIGHT_PILGRIM.PARAMETERS_GEO_IP_PROVIDER_NAME'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'IP_STACK' => 'ipstack.com'
            ],
        ],
    ],
];

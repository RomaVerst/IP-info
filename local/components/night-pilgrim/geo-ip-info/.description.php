<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Localization\Loc;

$arComponentDescription = [
    "NAME" => Loc::getMessage("NIGHT_PILGRIM.COMPONENT_NAME"),
    "DESCRIPTION" => Loc::getMessage("NIGHT_PILGRIM.COMPONENT_DESCRIPTION"),
    "ICON" => "/images/system.empty.png",
    "PATH" => [
        "ID" => "night-pilgrim",
        "SORT" => 100,
        "NAME" => Loc::getMessage("NIGHT_PILGRIM.COMPONENTS_FOLDER_NAME"),
    ],
    "CACHE_PATH" => "Y"
];


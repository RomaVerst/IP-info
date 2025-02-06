<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Error,
    \LocalClasses\Lib\IpStackProvider,
    \Bitrix\Main\ErrorCollection,
    \LocalClasses\ORM\GeoIpInfoLocalTable;

class GeoIpInfoComponent extends \CBitrixComponent
    implements \Bitrix\Main\Engine\Contract\Controllerable, \Bitrix\Main\Errorable
{
    /** @var ErrorCollection */
    protected $errorCollection;

    private $geoIpProvider;

    /**
     * @param CBitrixComponent $component
     */
    public function __construct($component = null)
    {
        parent::__construct($component);

        \Bitrix\Main\UI\Extension::load("ui.bootstrap4");

        if (empty($this->arParams['GEO_IP_PROVIDER'])) {
            $this->arParams['GEO_IP_PROVIDER'] = 'IP_STACK';
        }
        try {
            switch ($this->arParams['GEO_IP_PROVIDER']) {
                case 'IP_STACK':
                    $this->geoIpProvider = IpStackProvider::init();
                    break;
            }
        } catch (Throwable $t) {
            die('<div class="alert alert-danger" role="alert">' . $t->getMessage() . '</div>');
        }

    }


    public function configureActions()
    {
        return [];
    }

    public function onPrepareComponentParams($arParams)
    {
        $this->errorCollection = new ErrorCollection();
        $arParams['CACHE_TIME'] = (int)($arParams['CACHE_TIME'] ?? 36000000);
        $arParams['GEO_IP_PROVIDER'] = $arParams['GEO_IP_PROVIDER'] ?? 'IP_STACK';

        return $arParams;
    }

    public function executeComponent()
    {
        if ($this->startResultCache()) {

            $this->setResultCacheKeys([]);
            $this->includeComponentTemplate();
        }
    }


    /**
     * Получение информации по ip
     * @param array $inputFields
     * @return string|null
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getIpInfoAction(array $inputFields): null|string
    {
        if (empty($inputFields)) {
            $this->errorCollection[] = new Error('empty form');
            return null;
        }

        $inputs = $this->prepareInputFields($inputFields);

        if (empty($inputs['inputIp'])) {
            $this->errorCollection[] = new Error('field inputIp is empty');
            return null;
        }

        if (!check_bitrix_sessid()) {
            $this->errorCollection[] = new Error('not current session');
            return null;
        }

        $result = GeoIpInfoLocalTable::getList([
            'filter' => ['UF_IP_ADDRESS' => $inputs['inputIp']],
            'cache' => ['ttl' => $this->arParams['CACHE_TIME']]
        ])->fetch();

        if (empty($result['ID'])) {
            try {
                $response = $this->geoIpProvider::getInfo($inputs['inputIp']);

                $checkResponse = $this->geoIpProvider::getErrors($response);
                $result = [
                    'UF_IP_ADDRESS' => $response['ip'],
                    'UF_TYPE' => $response['type'],
                    'UF_CONTINENT_NAME' => $response['continent_name'],
                    'UF_COUNTRY_NAME' => $response['country_name'],
                    'UF_REGION_NAME' => $response['region_name'],
                    'UF_CITY' => $response['city'],
                    'UF_LATITUDE' => $response['latitude'],
                    'UF_LONGITUDE' => $response['longitude'],
                ];
                $result['ID'] = GeoIpInfoLocalTable::add($result);
                if ($checkResponse['success'] === false) {
                    throw new \Exception($checkResponse['error']);
                }
                if (empty($result['ID'])) {
                    throw new \Exception('could not write data to hl block');
                }
            } catch (Throwable $t) {
                $this->errorCollection[] = new Error($t->getMessage());
                return null;
            }
        }

        return json_encode($result);
    }

    /**
     * Подгтовка/валидация полученных полей от пользователя
     * @param array $inputFields
     * @return array
     */
    private function prepareInputFields(array $inputFields): array
    {
        $inputs = [];
        foreach ($inputFields as $field) {
            $inputs[htmlspecialchars(trim($field['name']))] = htmlspecialchars(trim($field['value']));
        }

        return $inputs;
    }

    /**
     * Getting array of errors.
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errorCollection->toArray();
    }

    /**
     * Getting once error with the necessary code.
     * @param string $code Code of error.
     * @return Error
     */
    public function getErrorByCode($code)
    {
        return $this->errorCollection->getErrorByCode($code);
    }
}
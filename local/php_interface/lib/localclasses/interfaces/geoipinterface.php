<?php
namespace LocalClasses\Interfaces;


interface GeoIpInterface
{
    /**
     * Инициализация объекта класса
     * @return object
     * @throws \Exception
     */
    public static function init();


    /**
     * Запрос по апи провайдера
     * @param string $ip
     * @return array
     * @throws \Exception
     */
    public static function getInfo(string $ip);

    /**
     * Обработка ошибок полученного ответа
     * @param array $response
     * @return array
     */
    public static function getErrors(array $response);
}

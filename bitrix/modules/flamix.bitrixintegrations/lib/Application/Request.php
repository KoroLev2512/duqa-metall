<?php

namespace Flamix\BitrixIntegrations\Application;

use Bitrix\Main\Localization\Loc;
use Exception;

/**
 * Класс для организации запросов
 */
class Request
{
    /**
     * Проверка hash запроса
     *
     * @param array $arData - массив данных
     *
     * @return bool - результат
     * @example \Flamix\BitrixIntegrations\Request::checkHash();
     */
    public static function checkHash(array $arData): bool
    {
        if ($arData["flamix_status"] !== "Y"){
            return false;
        }

        if (!$arData["status"]) {
            return false;
        }

        if (!$arData["order_id"]) {
            return false;
        }

        if (!$arData["hash"]) {
            return false;
        }

        $sApiKey = \Flamix\BitrixIntegrations\Option::get('bitrix24_api_key');

        if(!$sApiKey){
            return false;
        }

        if ($arData["hash"] !== md5($sApiKey . '_' . strtoupper($arData["status"]))) {
            return false;
        }

        return true;
    }
}

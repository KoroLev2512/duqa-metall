<?php

namespace Flamix\BitrixIntegrations;

use Bitrix\Main\Config\Option as BitrixOption;
use Bitrix\Main\Localization\Loc;
use Flamix\Bitrix24\Lead;
use Bitrix\Main\Loader;
use Exception;

/**
 * Класс для работы с параметрами модуля
 */
class Option
{
    /**
     * Получение значения параметра модуля
     *
     * @param string $sCode - код параметра
     * @return mixed - значение параметра
     * @example \Flamix\BitrixIntegrations\Option::get('bitrix24_domain');
     */
    public static function get(string $sCode)
    {
        $value = BitrixOption::get('flamix.bitrixintegrations', $sCode);
        if ($unserializedValue = unserialize($value)) {
            return $unserializedValue;
        }

        return $value;
    }

    /**
     * Получение значения параметра модуля
     *
     * @param string $sCode - код параметра
     * @param mixed $value - код параметра
     * @return void
     * @example \Flamix\BitrixIntegrations\Option::set('bitrix24_domain', 'b24.test.com');
     */
    public static function set(string $sCode, $value)
    {
        if (is_array($value)) {
            $value = serialize($value);
        }

        BitrixOption::set('flamix.bitrixintegrations', $sCode, $value);
    }

    /**
     * Проверка параметров модуля
     *
     * @return array - результат проверки
     * @example \Flamix\BitrixIntegrations\Option::getStatus();
     */
    public static function getStatus(): array
    {
        Loc::loadMessages(__FILE__);
        $arRes = [
        	'status' => 'error',
        	'mess' => Loc::getMessage('FX_BI_UNDEFINED_ERROR')
        ];

        try {
            $sDomain = static::get('bitrix24_domain');
            if (!$sDomain) {
                throw new Exception(Loc::getMessage('FX_BI_NO_DOMAIN'));
            }

            $sApiKey = static::get('bitrix24_api_key');
            if (!$sApiKey) {
                throw new Exception(Loc::getMessage('FX_BI_NO_API_KEY'));
            }

            $obLead = Lead::getInstance()
                ->changeSubDomain('leadbitrix')
                ->setDomain($sDomain)
                ->setToken($sApiKey);

            $arRes = $obLead->send(['status' => 'check'], 'check');

            if (!empty($arRes) && $arRes['status'] == 'success') {
                return [
                    'status' => 'ok',
                    'mess' => Loc::getMessage('FX_BI_OPTION_CHECK_SUCCESS')
                ];
            }

            throw new Exception(Loc::getMessage('FX_BI_WRONG_CREDENTIALS'));

        } catch (Exception $e) {
            $arRes['mess'] = $e->getMessage();
        }

        return $arRes;
    }

    /**
     * Проверка библиотеки cURL
     *
     * @return array - результат проверки
     * @example \Flamix\BitrixIntegrations\Option::getCurlStatus();
     */
    public static function getCurlStatus(): array
    {
        Loc::loadMessages(__FILE__);

        if (extension_loaded('curl')) {
            return [
                'status' => 'ok',
                'mess' => Loc::getMessage('FX_BI_CURL_INSTALLED')
            ];
        }

        return [
            'status' => 'error',
            'mess' => Loc::getMessage('FX_BI_NO_CURL')
        ];
    }

    /**
     * Проверка версии PHP
     *
     * @return array - результат проверки
     * @example \Flamix\BitrixIntegrations\Option::getPhpStatus();
     */
    public static function getPhpStatus(): array
    {
        Loc::loadMessages(__FILE__);

        if (version_compare(PHP_VERSION, '7.2.0') >= 0) {
            return [
                'status' => 'ok',
                'mess' => Loc::getMessage('FX_BI_PHP_VERSION_OK', [
                    '#VERSION#' => PHP_VERSION
                ])
            ];
        }

        return [
            'status' => 'error',
            'mess' => Loc::getMessage('FX_BI_WRONG_PHP_VERSION', [
                '#VERSION#' => PHP_VERSION
            ])
        ];
    }

    /**
     * Проверка, настроен ли модуль и все требования
     *
     * @return bool - результат
     * @example \Flamix\BitrixIntegrations\Option::isConfigured();
     */
    public static function isConfigured(): bool
    {
        $arStatus = static::getStatus();
        if ($arStatus['status'] != 'ok') {
            return false;
        }

        $arStatus = static::getCurlStatus();
        if ($arStatus['status'] != 'ok') {
            return false;
        }

        $arStatus = static::getPhpStatus();
        if ($arStatus['status'] != 'ok') {
            return false;
        }

        return true;
    }

    /**
     * Проверка версии PHP
     *
     * @param string $sModuleName - код модуля
     * @return bool - результат проверки
     * @example \Flamix\BitrixIntegrations\Option::isModule('form');
     */
    public static function isModule(string $sModuleName):bool
    {
        return (bool) Loader::includeModule($sModuleName);
    }

    /**
     * When saving email - check
     *
     * @param $option
     * @return bool|string
     */
    public static function parseDomain($option)
    {
        $tmp = parse_url($option);
        if(!empty($tmp['host']))
            return $tmp['host'];

        return $option;
    }
}

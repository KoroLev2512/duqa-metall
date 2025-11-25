<?php

namespace Flamix\BitrixIntegrations;


use Flamix\Bitrix24\Lead as FlamixLead;
use Bitrix\Main\Localization\Loc;
use Exception;

/**
 * Класс для работы с лидами через SDK flamix
 */
class Lead
{
    /**
     * Обработчик при добавлении нового результата веб-формы
     *
     * @param array $arData - массив данных лида
     * @param string $actions - строка с действием
     * @return void
     * @throws Exception - ошибка
     * @example \Flamix\BitrixIntegrations\Lead::add($arData);
     */
    public static function send(array $arData, $actions = "lead/add")
    {
        if (!Option::isConfigured()) {
            Loc::loadMessages(__FILE__);
            throw new \Exception(Loc::getMessage('FX_BI_MODULE_IS_NOT_CONFIGURED'));
        }

        $sDomain = Option::get('bitrix24_domain');
        $sApiKey = Option::get('bitrix24_api_key');

        $obLead = FlamixLead::getInstance()
            ->changeSubDomain('leadbitrix')
            ->setDomain($sDomain)
            ->setToken($sApiKey);

        $obLead->send($arData, $actions);
    }
}

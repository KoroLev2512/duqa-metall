<?php

namespace Flamix\BitrixIntegrations\Application;

use \Bitrix\Main\Application;

/**
 * Класс для организации основных обработчиков системы
 */
class Event
{
    /**
     * Обработчик на епилоге
     *
     * @return void
     * @example вешается на событие
     */
    public static function OnEpilog()
    {
        //инициализация трейса и utm
        global $APPLICATION;
        if(!empty($APPLICATION->GetTitle()) && $APPLICATION->GetTitle() != NULL)
            \Flamix\Bitrix24\Trace::init($APPLICATION->GetTitle());
    }

    /**
     * Обработчик на прологе
     *
     * @return void
     * @throws \Bitrix\Main\LoaderException
     * @example вешается на событие
     */
    public static function OnProlog()
    {
        $request = Application::getInstance()->getContext()->getRequest();

        $getParamsValue = $request->getQueryList()->toArray();

        if (!Request::checkHash($getParamsValue)) {
            return;
        }

        \Flamix\BitrixIntegrations\Order::setStatus(
            $getParamsValue["order_id"],
            $getParamsValue["status"]
        );
    }
}

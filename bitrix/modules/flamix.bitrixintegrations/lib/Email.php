<?php

namespace Flamix\BitrixIntegrations;

use Bitrix\Main\Mail\Event;

/**
 * Класс для отправки емейл уведомлений
 */
class Email
{
    const EVENT_TYPE = 'FLAMIX_B24_SYNC_NOTIFICATION';

    /**
     * Отправка уведомления
     *
     * @param array $arData - данные уведомления
     * @param string $sSiteId - ID сайта
     * @return void
     * @example Flamix\BitrixIntegrations\Email::send($arData);
     */
    public static function send(array $arData, string $sSiteId = SITE_ID)
    {
        $sEmail = Option::get('notifications_email');
        if (!$sEmail) {
            return;
        }

        $arData['EMAIL'] = $sEmail;

        Event::send([
        	'EVENT_NAME' => static::EVENT_TYPE,
        	'LID' => $sSiteId,
        	'C_FIELDS' => $arData
        ]);
    }
}

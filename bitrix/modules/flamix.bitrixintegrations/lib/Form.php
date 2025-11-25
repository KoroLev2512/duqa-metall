<?php

namespace Flamix\BitrixIntegrations;

/**
 * Класс для работы с веб формами в рамках модуля
 */
class Form
{
    /**
     * Проверка, нужно ли отправлять формы в Bitrix24
     *
     * @return bool - результат
     * @example \Flamix\BitrixIntegrations\Form::isSendEnabled();
     */
    public static function isSendEnabled(): bool
    {
        return Option::get('form_send_enabled') == 'Y';
    }
}

<?php

/**
 * Main module events
 */

namespace Artamonov\Bitrix24\Event;


use Artamonov\Bitrix24\Config;
use Artamonov\Bitrix24\Crm;
use Artamonov\Bitrix24\Helper;
use Artamonov\Bitrix24\Settings;

class Main
{
    public static function OnEndBufferContent(&$buffer)
    {
        if (
            Helper::getInstance()->isAdminSection() ||
            !Config::getInstance()->get('useCustomizationWidget') ||
            mb_strpos(Helper::getInstance()->getSentContentType(), '/html') === false
        ) return;

        $settings = [
            'whatsApp' => [
                'active' => Config::getInstance()->get('useChannelWhatsApp') ? true : false,
                'account' => Config::getInstance()->get('whatsAppAccount'),
                'title' => Config::getInstance()->get('whatsAppTitle'),
                'sort' => Config::getInstance()->get('whatsAppSort'),
            ]
        ];

        $buffer .= '<script>';
        $buffer .= 'window.ArtamonovBitrix24Widget = {
            settings: ' . Helper::getInstance()->phpToJs($settings) . '
        }';
        $buffer .= '</script>';
        $buffer .= '<script defer src="/bitrix/js/' . Settings::getInstance()->get('module')['id'] . '/widget.min.js"></script>';
    }

    public static function OnAfterUserRegister(&$fields)
    {
    }

    public static function OnAfterUserAdd(&$fields)
    {
        if (Config::getInstance()->get('useExportRegisteredUsers') && Config::getInstance()->get('usersExportPeriod') < 1) {
            Crm::getInstance()->addContact($fields);
        }
    }
}

<?php


namespace Webcomp\Market;


class Constants
{

    static function init()
    {
        self::getSettings();
        self::getAllIblocks();
        self::getAllHightLoadBlocks();
        self::getContacts();
    }

    public static function getContacts()
    {
        global $APPLICATION;
        $GLOBALS['WEBCOMP']['CONSTANTS']['CONTACTS']
            = $APPLICATION->IncludeComponent(
            "webcomp:element.getList",
            ".default",
            [
                "CACHE_FILTER" => "N",
                "CACHE_TIME" => "0",
                "CACHE_TYPE" => "N",
                "ELEMENTS_COUNT"        => "1",
                "FIELD_CODE"            => [
                    0 => "ID",
                    1 => "IBLOCK_ID",
                    2 => "NAME",
                    3 => "PREVIEW_PICTURE",
                    4 => "PREVIEW_TEXT",
                ],
                "FILTER_NAME"           => "",
                "IBLOCK_ID"             => $GLOBALS['WEBCOMP']['IBLOCKS']['content']['webcomp_market_content_contacts'],
                "IBLOCK_TYPE"           => "content",
                "PROPERTY_CODE"         => [
                    0 => "ADDRESS",
                    1 => "WORKTIME",
                    2 => "PHONE",
                    3 => "METRO",
                    4 => "EMAIL",
                    5 => "MAP",
                    6 => "BOTTOM_BLOCKS",
                ],
                "SHOW_ONLY_ACTIVE"      => "Y",
                "SORT_BY1"              => "ACTIVE_FROM",
                "SORT_BY2"              => "SORT",
                "SORT_ORDER1"           => "DESC",
                "SORT_ORDER2"           => "ASC",
                "COMPONENT_TEMPLATE"    => ".default",
                "PAGINATION"            => "Y",
                "SHOW_ARROW"            => "Y",
                "AUTO_PLAY"             => "Y",
                "AUTO_PLAY_SPEED"       => "500",
                "AUTO_PLAY_DELAY_SPEED" => "7000",
                "DONT_INCLUDE_TEMPLATE" => "Y",
                "USE_FILTER"            => "N",
            ],
            false
        )['ITEMS'][0];
    }

    public static function getSettings($arParams = [])
    {

        $arResult = [];

        if ( ! is_array($arParams)) {
            $arParams = (array)$arParams;
        }

        // get all settings if empty $arParams
        if (empty($arParams)) {
            $arResult
                = \Bitrix\Main\Config\Option::getForModule(WEBCOMP_MARKET_MODULE_ID);
            // get current settings if $arParams is not empty
        } else {
            foreach ($arParams as $key => $option) {
                $arResult[$option] = \Bitrix\Main\Config\Option::get(WEBCOMP_MARKET_MODULE_ID, $option);
            }
        }

        $GLOBALS['WEBCOMP']["SETTINGS"] = $arResult;
    }

    public static function getAllIblocks() {
        \Bitrix\Main\Loader::includeModule('iblock');
        $arIblock = \Bitrix\Iblock\IblockTable::getList(array(
            'select' => array('ID','NAME','CODE','API_CODE','IBLOCK_TYPE_ID'),
            'cache' => array(
                'ttl' => 3600,
                'cache_joins' => true,
            )
        ));
        foreach ($arIblock->fetchAll() as $item) {
            $GLOBALS['WEBCOMP']['IBLOCKS'][$item['IBLOCK_TYPE_ID']][$item['CODE']] = $item['ID'];
        }
    }

    public static function getAllHightLoadBlocks() {
        \Bitrix\Main\Loader::includeModule('highloadblock');
        $arIblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
            'select' => array('ID','NAME','TABLE_NAME'),
            'cache' => array(
                'ttl' => 3600,
                'cache_joins' => true,
            )
        ));
        foreach ($arIblock->fetchAll() as $item) {
            $GLOBALS['WEBCOMP']['HLBLOCKS'][$item['NAME']] = $item['ID'];
        }
    }
}

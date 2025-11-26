<?php
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\IO\File;

/**
 * Class CMarketEvent
 * Класс для обработки событий
 */

class CMarketEvent extends CMarket
{

    public static function OnBeforeUpdateHLMarketOrders(
        \Bitrix\Main\Entity\Event $event
    ) {
        //id добавляемого элемента
        $arFields = $event->getParameter("fields");
        $result = new \Bitrix\Main\Entity\EventResult();
        CMarketFormOrder::updateOrder($arFields['UF_PRODUCTS_LIST_JSON']);
        $arFields['UF_PRODUCTS_LIST_JSON'] = '';
        $result->modifyFields($arFields);

        return $result;
    }

    public static function OnAdminHighloadBlockTabHandler(&$form)
    {
        \Webcomp\Market\Constants::init();
        $HL_ORDER_ID = $GLOBALS['WEBCOMP']['HLBLOCKS']['WebCompMarketOrders'];
        $HL_ORDER_LIST_ID
            = $GLOBALS['WEBCOMP']['HLBLOCKS']['WebCompMarketOrderPosition'];
        global $APPLICATION, $arrFilter;
        if (self::getCurrentPage() == "/bitrix/admin/highloadblock_row_edit.php"
            && ! empty($HL_ORDER_ID)
            && ! empty($HL_ORDER_LIST_ID)
        ) {
            if ($_REQUEST["ENTITY_ID"] == $HL_ORDER_ID) {
                $orderList = [];

                $arrFilter = ["UF_ORDER_ID" => (int)$_REQUEST["ID"]];

                $arParams = $APPLICATION->IncludeComponent(
                    "webcomp:highload.getList",
                    ".default",
                    array(
                        "CACHE_TIME"            => "0",
                        "CACHE_TYPE"            => "A",
                        "ELEMENTS_COUNT"        => "20",
                        "FIELD_CODE"            => array(
                            0 => "ID",
                            1 => "UF_NAME",
                            2 => "UF_ORDER_ID",
                            3 => "UF_ELEMENT_ID",
                            4 => "UF_PHOTO",
                            5 => "UF_QUANTITY",
                            6 => "UF_PRICE",
                        ),
                        "HLBLOCK_ID"            => $HL_ORDER_LIST_ID,
                        "USE_FILTER"            => "Y",
                        "USE_SORT"              => "Y",
                        "COMPONENT_TEMPLATE"    => ".default",
                        "SORT_FILED"            => "ID",
                        "SORT_ORDER"            => "ASC",
                        "FILTER_NAME"           => "arrFilter",
                        "DONT_INCLUDE_TEMPLATE" => "Y",
                    ),
                    false
                )["ITEMS"];

                if(!empty($arParams)) {

                    if(File::isFileExists($listOrderFile = self::getModulePath()."include/orderList.php")) {

                        if(is_array($arParams))
                            extract($arParams, EXTR_SKIP);

                        ob_start();

                        include($listOrderFile);

                        $orderList = ob_get_contents();
                        ob_end_clean();

                    } else {
                        CMarketLog::Log(2, __FILE__, __LINE__, ["#FILE_NAME#" => "orderList.php"]);
                    }


                }

                $form->tabs[1] = [
                    "DIV"     => "edit2",
                    "TAB"     => Loc::getMessage("WEBCOMP_HL_ORDERLIST_TAB"),
                    "ICON"    => "main_user_edit",
                    "TITLE"   => Loc::getMessage("WEBCOMP_HL_ORDERLIST_TAB"),
                    "CONTENT" => $orderList
                ];
            }
        }
    }

    public static function OnBeforeUserUpdateHandler(&$arFields) {
        if ($arFields["LOGIN"] != 'admin'
            && $arFields["LOGIN"] != 'administrator'
        ) {
            $arFields["LOGIN"] = $arFields["EMAIL"];
        }

        return $arFields;
    }
}
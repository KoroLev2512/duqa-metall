<?php

/**
 * Class CMarketTools
 * Класс содержит полезные методы для работы
 */
class CMarketTools extends CMarket {

    /**
     * Метод возвращает массив Enum полей
     * @param $propertyID
     * @return array
     */
    public static function getEnumFields($propertyID) {
        $arResult = [];

        $rsEnum = \Bitrix\Iblock\PropertyEnumerationTable::getList(array(
            'filter' => ['PROPERTY_ID' => $propertyID],
            'order' => ["SORT" => "ASC"]
        ));

        while ($arEnum = $rsEnum->fetch()) {
            $arResult[] = [
                "ID"     => $arEnum["ID"],
                "NAME"   => $arEnum["VALUE"],
                "XML_ID" => $arEnum["XML_ID"],
            ];
        }

        return $arResult;
    }

    public static function getIdHLByName($code)
    {
        CModule::IncludeModule('highloadblock');
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList([
            'filter' => ['=NAME' => $code],
        ])->fetch();
        if ( ! $hlblock) {
            throw new \Exception('HL'.$code.' notFound');
        }

        return $hlblock["ID"];
    }
}
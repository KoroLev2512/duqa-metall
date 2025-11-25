<?php

namespace Flamix\BitrixIntegrations\Order;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Sale;
use Flamix\BitrixIntegrations\Option;
use Flamix\BitrixIntegrations\Email;
use Flamix\BitrixIntegrations\Lead;
use Flamix\BitrixIntegrations\Order;
use CIBlockElement;
use Exception;
use CSite;

/**
 * Класс для организации обработчиков заказов
 */
class Event
{
    /**
     * Обработчик при сохранении заказа
     *
     * @param object $obEvent - событие|заказ
     * @return void
     * @example вешается на событие
     */
    public static function onSaleOrderSaved(object $obEvent)
    {
        if (!Order::isSendEnabled()) {
            return;
        }

        $isNew = $obEvent->getParameter("IS_NEW");
        if (!$isNew) {
            return;
        }

        try {
            //определяем объект заказа
            $obOrder = $obEvent;
            if (method_exists($obEvent, 'getParameter')) {
                $obOrder = $obEvent->getParameter('ENTITY');
            }

            //получаем оплату
            $paymentCollection = $obOrder->getPaymentCollection();
            $paymentsName = [];
            foreach ($paymentCollection as $payment) {
                $paymentsName[] = $payment->getPaymentSystemName();
            }

            //получаем доставку
            $shipmentCollection = $obOrder->getShipmentCollection();
            $shipmentsName = [];
            foreach($shipmentCollection as $shipment){
                $shipmentsName[] = $shipment->getDeliveryName();
            }

            $arData = [
                'FIELDS' => [
                    //базовые поля заказа
                    'ORDER_ID' => $obOrder->getId(),
                    'ORDER_ACCOUNT_NUMBER' => $obOrder->getField('ACCOUNT_NUMBER'),
                    'ORDER_PRICE' => $obOrder->getField('PRICE'),
                    'ORDER_COMMENT' => $obOrder->getField('USER_DESCRIPTION'),
                    'ORDER_PAYMENT' => implode(", ", $paymentsName),
                    'ORDER_DELIVERY' => implode(", ", $shipmentsName),
                ],
                'PRODUCTS' => [],
                'CURRENCY' => $obOrder->getCurrency(),
            ];

            //handler for fields
            foreach (GetModuleEvents('flamix.bitrixintegrations', 'onFieldsCreate', true) as $arEvent) {
                ExecuteModuleEventEx($arEvent, [$obOrder, &$arData['FIELDS']]);
            }

            //добавляем свойства заказа
            $arPropertiesValues = static::getOrderPropertiesValues($obOrder);
            foreach ($arPropertiesValues as $sCode => $sValue) {
                $arData['FIELDS']['ORDER_PROP_' . $sCode] = $sValue;
            }

            //добавляем данные по товарам
            $obBasket = $obOrder->getBasket();
            $arElements = static::getBasketElements($obBasket);
            foreach ($obBasket as $obItem) {
                $arItem = $obItem->getFields()->getValues();
                $iElementId = (int) $arItem['PRODUCT_ID'];
                $arElement = $arElements[$iElementId];

                $name = $arItem['NAME'];
                // Если оффер - ставим свойства
                if ($arElement['IBLOCK_TYPE_ID'] === 'offers') {
                    $name .= ' (';
                    foreach($obItem->getPropertyCollection()->getPropertyValues() as $option) {
                        $name .= $option['NAME'] . ': ' . $option['VALUE'] . '; ';
                    }
                    $name .= ')';
                }

                $arData['PRODUCTS'][] = [
                    'ID' => $iElementId,
                    'NAME' => $name,
                    'PRICE' => $arItem['PRICE'],
                    'CURRENCY_ID' => $arItem['CURRENCY'],
                    'CODE' => $arElement['CODE'],
                    'DESCRIPTION' => $arElement['PREVIEW_TEXT'],
                    'MEASURE' => $arItem['MEASURE_CODE'],
                    'XML_ID' => $arElement['XML_ID'],
                    'QUANTITY' => $arItem['QUANTITY'],
                    'FIND_BY' => Option::get('order_product_find_by') !== 'DISABLE' ? Option::get('order_product_find_by') : null,
                ];
            }

            //handler for products
            foreach (GetModuleEvents('flamix.bitrixintegrations', 'onProductCreate', true) as $arEvent) {
                ExecuteModuleEventEx($arEvent, [$obOrder, &$arData['PRODUCTS']]);
            }

            //handler for all data
            foreach (GetModuleEvents('flamix.bitrixintegrations', 'onDataCreate', true) as $arEvent) {
                ExecuteModuleEventEx($arEvent, [$obOrder, &$arData]);
            }

            //отправляем лид
            Lead::send($arData);

        } catch (Exception $e) {
            Loc::loadMessages(__FILE__);

            //отправляем уведомления об ошибке

            //для отправки нужен сайт, берем по умолчанию
            $sSiteId = SITE_ID;

            //если мы в админке - берем сайт из заказа
            if (CSite::InDir('/bitrix/') && isset($obOrder)) {
                $sSiteId = $obOrder->getSiteId();
            }

            //отправляем уведомление по каждому сайту
            Email::send([
                'MESSAGE' => Loc::getMessage('FX_BI_ERROR', [
                    '#MESSAGE#' => $e->getMessage()
                ])
            ], $sSiteId);

        }
    }

    /**
     *  Получение массива значений свйоств заказа
     * 
     * @param object $obOrder - объект заказа
     * @return array - массив значений
     * @throws Exception - ошибка
     * @example static::getOrderPropertiesValues($obOrder);
     */
    protected static function getOrderPropertiesValues(object $obOrder): array
    {
        $arValues = [];
        
        $obPropertyCollection = $obOrder->getPropertyCollection();
        foreach ($obPropertyCollection as $obPropertyValue) {
            $arProperty = $obPropertyValue->getProperty();

            if (empty($obPropertyValue->getViewHtml() ?? $obPropertyValue->getValue()))
                continue;

            $sPropCode = $arProperty['CODE'] ?? 'PROP_' . $arProperty['ID'];
            $value = $obPropertyValue->getViewHtml();

            if ($arProperty['IS_EMAIL'] == 'Y') {
                $value = $obPropertyValue->getValue();
            }

            $arValues[strtoupper($sPropCode)] = $value;
        }
        
        return $arValues;
        
    }
    
    /**
     * Полученеи данных товаров из инфоблока
     * 
     * @param object $obBasket - объект корзины
     * @return array - данные товаров
     * @throws Exception - ошибка
     * @example static::getBasketElements($obBasket);
     */
    protected static function getBasketElements(object $obBasket): array
    {
        if (!Loader::includeModule('iblock')) {
        	throw new Exception('No iblock module found');
        }
        
        $arIds = [];
        foreach ($obBasket as $obItem) {
            $arIds[] = $obItem->getField('PRODUCT_ID');
        }

        $arElements = [];
        $rsItems = CIBlockElement::GetList(['ID' => 'ASC'], ['ID' => $arIds]);
        while ($obItem = $rsItems->GetNextElement()) {
            $arItem = $obItem->GetFields();
            $arItem['PROPERTIES'] = $obItem->GetProperties();

            $arElements[$arItem['ID']] = $arItem;
        }
        
        return $arElements;
    }

    /**
     * Обработчик, вызывается при сохранении, если статус заказа был изменен.
     *
     * @param object $obEvent - событие|заказ
     * @return void
     * @throws \Exception
     * @example вешается на событие
     */
    public static function onSaleStatusOrderChange(object $obEvent)
    {
        if (!Order::isSendEnabled()) {
            return;
        }

        //определяем объект заказа
        $obOrder = $obEvent;
        if (method_exists($obEvent, 'getParameter')) {
            $obOrder = $obEvent->getParameter('ENTITY');
        }

        $arData = [
            'HOSTNAME' => $_SERVER["SERVER_NAME"],
            'ORDER_ID' => $obOrder->getId(),
            'STATUS' => $obOrder->getField("STATUS_ID"),
        ];

        Lead::send($arData, "status/change");
    }

    public static function OnSalePayOrder(int $id, string $val)
    {
        if ($val == 'Y') {
            try {
                $order = Sale\Order::load($id);

                $arData = [
                    'HOSTNAME' => $_SERVER["SERVER_NAME"],
                    'ORDER_ID' => $id,
                    'AMOUNT' => $order->getPrice(),
                    'CURRENCY' => $order->getCurrency()
                ];

                Lead::send($arData, "status/pay");
            } catch (Exception $e) {
                Loc::loadMessages(__FILE__);

                //отправляем уведомления об ошибке

                //для отправки нужен сайт, берем по умолчанию
                $sSiteId = SITE_ID;

                //если мы в админке - берем сайт из заказа
                if (CSite::InDir('/bitrix/') && isset($obOrder)) {
                    $sSiteId = $order->getSiteId();
                }

                //отправляем уведомление по каждому сайту
                Email::send([
                    'MESSAGE' => Loc::getMessage('FX_BI_ERROR', [
                        '#MESSAGE#' => $e->getMessage()
                    ])
                ], $sSiteId);
            }
        }
    }
}

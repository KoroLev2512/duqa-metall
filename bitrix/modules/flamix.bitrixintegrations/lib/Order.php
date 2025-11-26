<?php

namespace Flamix\BitrixIntegrations;

use Exception;
use Bitrix\Main\Loader;
use Bitrix\Sale;

/**
 * Класс для работы с заказами в рамках модуля
 */
class Order
{
    /**
     * Проверка, нужно ли отправлять заказы в Bitrix24
     *
     * @return bool - результат
     * @example \Flamix\BitrixIntegrations\Order::isSendEnabled();
     */
    public static function isSendEnabled(): bool
    {
        return Option::get('order_send_enabled') == 'Y';
    }

    /**
     * Проставляем статус по заказу
     *
     * @param int $orderId - id заказа
     * @param string $status - статус заказа
     * @return void - результат
     * @throws \Bitrix\Main\LoaderException
     * @example \Flamix\BitrixIntegrations\Order::setStatus();
     */
    public static function setStatus(int $orderId, string $status)
    {
        if (!Loader::includeModule('sale')) {
            throw new Exception('No sale module found');
        }

        $statuses = \Bitrix\Sale\OrderStatus::getAllStatusesNames();
        if (!in_array($status, array_keys($statuses))) {
            throw new Exception("Status '{$status}' not exist in Bitrix!");
        }
        
        $obOrder = Sale\Order::load($orderId);

        if (\Flamix\BitrixIntegrations\Order\Status::isPay($status)) {
            $paymentCollection = $obOrder->getPaymentCollection();
            foreach ($paymentCollection as $payment) {
                $payment->setPaid("Y");
            }
        }

        if (\Flamix\BitrixIntegrations\Order\Status::isDelivery($status)) {
            $shipmentCollection = $obOrder->getShipmentCollection();
            $shipmentItemIds = [];
            $shipmentItemsReserved = [];
            foreach ($shipmentCollection as $shipment) {
                $shipmentItemCollection = $shipment->getShipmentItemCollection();
                foreach ($shipmentItemCollection as $shipmentItem) {
                    $reservedItemQuantity = $shipmentItem->getReservedQuantity();
                    if ($reservedItemQuantity == 0) {
                        continue;
                    }
                    $shipmentItemIds[] = $shipmentItem->getProductId();
                    $shipmentItemsReserved[$shipmentItem->getProductId()] = $reservedItemQuantity;
                    $shipmentItem->setField("RESERVED_QUANTITY", 0);
                }
            }
            if ($shipmentItemIds) {
                $products = \Bitrix\Catalog\ProductTable::getList([
                    'filter' => ["ID" => $shipmentItemIds],
                    'select' => ["ID", "QUANTITY_RESERVED"],
                ]);
                while ($product = $products->fetch()) {
                    \Bitrix\Catalog\ProductTable::update(
                        $product["ID"],
                        ["QUANTITY_RESERVED" => $product["QUANTITY_RESERVED"] - $shipmentItemsReserved[$product["ID"]]]
                    );
                }
            }
        }

        if (\Flamix\BitrixIntegrations\Order\Status::isCancel($status)) {
            $obOrder->setField("CANCELED", "Y");
        }

        $obOrder->setField('STATUS_ID', $status);
        $result = $obOrder->save();
        $current_status = $obOrder->getField('STATUS_ID');
        if ($status !== $current_status) {
            throw new Exception("Can't change status to '{$statuses[$status]} ({$status})'! Please, check your shop ruller!");
        }

        die('Status changed!');
    }
}
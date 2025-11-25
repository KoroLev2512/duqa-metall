<?php

namespace Flamix\BitrixIntegrations\Order;

use Bitrix\Main\Loader;
use Flamix\BitrixIntegrations\Option;

/**
 * Класс для работы с статусами
 */
class Status
{
    /**
     * Проверяем статус и опции
     *
     * @param string $status
     * @param string $option_name
     * @return bool
     * @example \Flamix\BitrixIntegrations\Order\Status::checkStatus($status, 'order_status_payment');
     */
    public static function checkStatus(string $status, string $option_name): bool
    {
        $options = Option::get($option_name);

        if (empty($options))
            return false;

        if (is_array($options))
            return in_array($status, $options);

        return $status == $options;
    }

    /**
     * Проверяем статус = ли он статусу оплаты
     *
     * @param string $status
     * @return bool
     * @example \Flamix\BitrixIntegrations\Order\Status::isPay($status);
     */
    public static function isPay(string $status): bool
    {
        return self::checkStatus($status, 'order_status_payment');
    }

    /**
     * Проверяем статус = ли он статусу доставка
     *
     * @param string $status
     * @return bool
     * @example \Flamix\BitrixIntegrations\Order\Status::isDelivery($status);
     */
    public static function isDelivery(string $status): bool
    {
        return self::checkStatus($status, 'order_status_delivery');
    }

    /**
     * Проверяем статус = ли он статусу отмена
     *
     * @param string $status
     * @return bool
     * @example \Flamix\BitrixIntegrations\Order\Status::isCancel($status);
     */
    public static function isCancel(string $status): bool
    {
        return self::checkStatus($status, 'order_status_cancel');
    }

    /**
     * Получаем статусы
     *
     * @return array $arStatus
     * @throws \Bitrix\Main\LoaderException
     * @example \Flamix\BitrixIntegrations\Order\Status::getList();
     */
    public static function getList(): array
    {
        if (!Loader::includeModule('sale'))
            return [];

        $statusResult = \Bitrix\Sale\Internals\StatusTable::getList([
            'order' => ['SORT' => 'ASC'],
            'filter' => ['TYPE' => 'O'],
        ]);

        $arStatusIds = [];
        while ($statusTable = $statusResult->fetch()) {
            $arStatusIds[] = $statusTable["ID"];
        }

        $statusResultLang = \Bitrix\Sale\Internals\StatusLangTable::getList([
            'order' => ['STATUS.SORT' => 'ASC'],
            'filter' => ['STATUS.ID' => $arStatusIds, 'LID' => LANGUAGE_ID],
            'select' => ['STATUS_ID', 'NAME'],
        ]);

        $arStatusesName = [];
        while ($statusLang = $statusResultLang->fetch()) {
            $arStatusesName[$statusLang["STATUS_ID"]] = $statusLang["NAME"];
        }

        $arStatuses = [];
        foreach ($arStatusIds as $arStatusId) {
            $arStatuses[] = [
                "NAME" => $arStatusesName[$arStatusId],
                "STATUS_ID" => $arStatusId,
            ];
        }

        return $arStatuses;
    }
}

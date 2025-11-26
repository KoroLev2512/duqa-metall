<?php
/*
 * @updated 04.12.2020, 0:44
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @copyright Copyright (c) 2020, Компания Webco
 * @link http://webco.io
 */

/**
 * Файл устарел
 * Оставлен для совместимости
 * @deprecated
 */
if (!is_file($_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/bitrix24-extension-export-users.php')) {
    copy(
        $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/artamonov.bitrix24/install/admin/bitrix24-extension-export-users.php',
        $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/bitrix24-extension-export-users.php'
    );
}
header('Location: /bitrix/admin/bitrix24-extension-export-users.php');

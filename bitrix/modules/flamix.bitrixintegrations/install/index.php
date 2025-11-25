<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Flamix\BitrixIntegrations\Option;

Loc::loadMessages(__FILE__);

/**
 * Класс-конструктор установщика модуля
 */
class flamix_bitrixintegrations extends CModule
{
    var $MODULE_ID = 'flamix.bitrixintegrations';

    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;

    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;

    var $PARTNER_NAME;
    var $PARTNER_URI;

    /**
     * Конструктор установщика модуля
     *
     * @return void
     */
    public function __construct()
    {
        $arModuleVersion = [];
        include($this->getInstallDir() . '/version.php');

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->MODULE_NAME = Loc::getMessage('FX_BI_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('FX_BI_MODULE_DESCRIPTION');

        $this->PARTNER_NAME = Loc::getMessage('FX_BI_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('FX_BI_PARTNER_URI');
    }

    /**
     * Установка модуля
     *
     * @return void
     */
    public function DoInstall()
    {
        global $arRes;
        $arRes = [
            'status' => 'error',
            'mess' => Loc::getMessage('FX_BI_UNDEFINED_ERROR')
        ];

        try {
            //регистрация модуля в базе данных
            RegisterModule($this->MODULE_ID);

            //регистрация обработчиков событий
            $obManager = EventManager::getInstance();
            foreach ($this->getEvents() as $arEvent) {
                $obManager->registerEventHandler(
                    $arEvent['module'], $arEvent['event'],
                    $this->MODULE_ID,
                    $arEvent['class'], $arEvent['method']
                );
            }

            if (Loader::includeModule($this->MODULE_ID)) {
                $this->setDefaultSettings();
                $this->installEmails();
            }

            $arRes['status'] = 'ok';
            $arRes['mess'] = '';

        } catch (Exception $e) {
            $arRes['mess'] = $e->getMessage();
        }

        global $APPLICATION;
        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('FX_BI_MODULE_INSTALL', ['#MODULE#' => $this->MODULE_NAME]),
            $this->getInstallDir() . '/step.php'
        );
    }

    /**
     * Удаление модуля
     *
     * @return void
     */
    public function DoUninstall()
    {
        global $arRes;
        $arRes = [
            'status' => 'error',
            'mess' => Loc::getMessage('FX_BI_UNDEFINED_ERROR')
        ];

        try {
            if (Loader::includeModule($this->MODULE_ID)) {
                $this->uninstallEmails();
            }

            //удаление обработчиков событий
            $obManager = EventManager::getInstance();
            foreach ($this->getEvents() as $arEvent) {
                $obManager->unRegisterEventHandler(
                    $arEvent['module'], $arEvent['event'],
                    $this->MODULE_ID,
                    $arEvent['class'], $arEvent['method']
                );
            }

            //удаление модуля из регистра базы данных
            UnRegisterModule($this->MODULE_ID);

            $arRes['status'] = 'ok';
            $arRes['mess'] = '';

        } catch (Exception $e) {
            $arRes['mess'] = $e->getMessage();
        }

        global $APPLICATION;
        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('FX_BI_MODULE_DELETE', ['#MODULE#' => $this->MODULE_NAME]),
            $this->getInstallDir() . '/unstep.php'
        );
    }


    /**
     * Установка дефолтных настроек модуля
     *
     * @return void
     * @example $this->setDefaultSettings();
     */
    protected function setDefaultSettings()
    {
        if (!Option::get('form_send_enabled')) {
            Option::set('form_send_enabled', 'Y');
        }

        if (!Option::get('order_send_enabled')) {
            Option::set('order_send_enabled', 'Y');
        }

        if (!Option::get('order_product_find_by')) {
            Option::set('order_product_find_by', 'DISABLE');
        }
    }


    /**
     * Установка шаблонов писем
     *
     * @return void
     * @example $this->installEmails();
     */
    protected function installEmails()
    {
        $sType = Flamix\BitrixIntegrations\Email::EVENT_TYPE;

        $arSites = [];
        $rsSites = CSite::GetList($sBy = 'ID', $sOrder = 'asc');
        while ($arSite = $rsSites->Fetch()) {
            $arSites[$arSite['ID']] = $arSite['LANGUAGE_ID'];
        }

        include 'emails/notifications.php';
        /** @var $arLanguageData */

        foreach ($arSites as $sSiteId => $sLanguage) {
            $obEventType = new CEventType;
            $obEventMessage = new CEventMessage;

            $arData = $arLanguageData[$sLanguage] ?? $arLanguageData['en'];

            $obEventType->Add([
                'LID' => $sLanguage,
                'EVENT_NAME' => $sType,
                'NAME' => $arData['NAME'],
                'DESCRIPTION' => $arData['DESCRIPTION'],
            ]);
            $obEventMessage->Add([
                'ACTIVE' => 'Y',
                'EVENT_NAME' => $sType,
                'LID' => $sSiteId,
                'LANGUAGE_ID' => $sLanguage,
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => '#EMAIL#',
                'BODY_TYPE' => 'text',
                'SUBJECT' => $arData['SUBJECT'],
                'MESSAGE' => $arData['MESSAGE'],
            ]);
        }
    }

    /**
     * Удаление шаблонов писем
     *
     * @return void
     * @example $this->uninstallEmails();
     */
    protected function uninstallEmails()
    {
        $sType = Flamix\BitrixIntegrations\Email::EVENT_TYPE;

        $rsMessage = CEventMessage::GetList($sBy = 'ID', $sOrder = 'asc', [
            'TYPE_ID' => $sType
        ]);
        while ($arMessage = $rsMessage->GetNext()) {
            $em = new CEventMessage;
            $em->Delete($arMessage['ID']);
        }

        $obType = new CEventType;
        $obType->Delete($sType);
    }


    /**
     * Получение списка обработчиков событий модуля
     *
     * @return array - массиыв обработчиков
     * @example $this->getEvents();
     */
    protected function getEvents(): array
    {
        $arEvents = [
            //базовые обработчики
            [
                'module' => 'main',
                'event' => 'OnEpilog',
                'class' => '\Flamix\BitrixIntegrations\Application\Event',
                'method' => 'OnEpilog'
            ],
        ];

        //обработчики для форм
        if (Loader::includeModule('form')) {
            $arEvents[] = [
                'module' => 'form',
                'event' => 'onAfterResultAdd',
                'class' => '\Flamix\BitrixIntegrations\Form\Event',
                'method' => 'onAfterResultAdd'
            ];
        }

        //обработчики для заказов
        if (Loader::includeModule('sale')) {
            $arEvents[] = [
                'module' => 'sale',
                'event' => 'OnSaleOrderSaved',
                'class' => '\Flamix\BitrixIntegrations\Order\Event',
                'method' => 'onSaleOrderSaved'
            ];
            $arEvents[] = [
                'module' => 'sale',
                'event' => 'OnSaleStatusOrderChange',
                'class' => '\Flamix\BitrixIntegrations\Order\Event',
                'method' => 'onSaleStatusOrderChange'
            ];
            $arEvents[] = [
                'module' => 'main',
                'event' => 'OnProlog',
                'class' => '\Flamix\BitrixIntegrations\Application\Event',
                'method' => 'OnProlog'
            ];
            $arEvents[] = [
                'module' => 'sale',
                'event' => 'OnSalePayOrder',
                'class' => '\Flamix\BitrixIntegrations\Order\Event',
                'method' => 'OnSalePayOrder'
            ];
        }

        return $arEvents;
    }


    /**
     * Путь к директории установщика модуля
     *
     * @param bool $bRoot - включать в путь DOCUMENT_ROOT
     * @return string - путь
     * @example $this->getInstallDir();
     */
    protected function getInstallDir(bool $bRoot = true): string
    {
        $sDir = '';

        if ($bRoot) {
            global $DOCUMENT_ROOT;
            $sDir .= $DOCUMENT_ROOT;
        }

        $sDir .= '/bitrix/modules/' . $this->MODULE_ID . '/install';

        return $sDir;
    }
}

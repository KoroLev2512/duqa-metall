<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\File;

Loc::loadMessages(__FILE__);

class webcomp_market extends CModule
{
    var $MODULE_ID = 'webcomp.market';
    const solutionName = 'market';
    const partnerName = 'webcomp';

    public $exclusionAdminFiles;

    public function __construct()
    {

        if (is_file(__DIR__.'/version.php')) {

            $this->exclusionAdminFiles = [
                '..',
                '.',
                'menu.php',
                'operation_description.php',
                'task_description.php'
            ];

            include (__DIR__.'/version.php');
            $this->MODULE_ID = 'webcomp.market';
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
            $this->MODULE_NAME = Loc::getMessage("WEBCOMP_MARKET_MODULE_NAME");
            $this->MODULE_DESCRIPTION = Loc::getMessage("WEBCOMP_MARKET_MODULE_DESCRIPTION");

            $this->PARTNER_NAME = Loc::getMessage("WEBCOMP_MARKET_PARTNER_NAME");
            $this->PARTNER_URI = Loc::getMessage("WEBCOMP_MARKET_PARTNER_URI");

            $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = "Y";
            $this->MODULE_GROUP_RIGHTS = "Y";

        } else {
            CAdminMessage::showMessage(Loc::getMessage("WEBCOMP_MARKET_PARTNER_URI"));
        }
    }

    public function GetPath($notDocumentRoot = false) {
        if($notDocumentRoot)
            return str_ireplace(Application::getDocumentRoot(), "", dirname(__DIR__));
        else
            return dirname(__DIR__);
    }

    // метод проверяет поддерживается ли ядро D7 
    // D7 начался с версии 14.00.00
    public function isVersion() {
        return CheckVersion(ModuleManager::getVersion('main'), '20.00.00');
    }

    // Метод переноса необходимых файлов модуля из установщика в битрикс
    public function InstallFiles($arParams = [])
    {
        //сменим кодировку фалов на utf8 если надо
        $this->isUtf8ReplaceFilesEncoding();
        // Переновим все компоненты
        CopyDirFiles($this->GetPath()."/install/components",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
        CopyDirFiles($this->GetPath().'/install/css/',
            $_SERVER['DOCUMENT_ROOT'].'/bitrix/css/'.self::partnerName.'.'
            .self::solutionName, true, true);
        CopyDirFiles($this->GetPath().'/install/js/',
            $_SERVER['DOCUMENT_ROOT'].'/bitrix/js/'.self::partnerName.'.'
            .self::solutionName, true, true);
        CopyDirFiles($this->GetPath().'/install/images/',
            $_SERVER['DOCUMENT_ROOT'].'/bitrix/images/'.self::partnerName.'.'
            .self::solutionName, true, true);

        // Переносим папки из admin
        if (Directory::isDirectoryExists($path = $this->GetPath()."/admin")) {
            CopyDirFiles($this->GetPath()."/install/admin",
                $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin", true);
        }

        return true;
    }

    // Метод удаления файлов модуля из битрикс
    public function UnInstallFiles() {
        // Удаление компонентов // как перенесем все, можно будет удалить не тест
        Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/test/");
        Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"].'/bitrix/css/'.self::partnerName.'.'.self::solutionName.'/');
        Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"].'/bitrix/js/'.self::partnerName.'.'.self::solutionName.'/');
        Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"].'/bitrix/images/'.self::partnerName.'.'.self::solutionName.'/');

        // Удаление админских файлов
        if(Directory::isDirectoryExists($path = $this->GetPath() . "/admin")) {
            DeleteDirFiles($_SERVER["DOCUMENT_ROOT"].$this->GetPath() . "/install/admin/", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/");

            if($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if(in_array($item, $this->exclusionAdminFiles))
                        continue;

                    File::deleteFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/" . $this->MODULE_ID . "_" . $item);
                }

                closedir($dir);
            }
        }

        return true;
    }

    // Метод регистрации событий модуля
    public function InstallEvents() {
        $event = EventManager::getInstance();
        // Property
        $event->registerEventHandler(
            "iblock", 'OnIBlockPropertyBuildList', $this->MODULE_ID, "\Webcomp\Market\Property\FormString", "OnIBlockPropertyBuildList");
        $event->registerEventHandler(
            "iblock", 'OnIBlockPropertyBuildList', $this->MODULE_ID, "\Webcomp\Market\Property\FormPhone", "OnIBlockPropertyBuildList");
        $event->registerEventHandler(
            "iblock", 'OnIBlockPropertyBuildList', $this->MODULE_ID, "\Webcomp\Market\Property\FormEmail", "OnIBlockPropertyBuildList");
        $event->registerEventHandler(
            "iblock", 'OnIBlockPropertyBuildList', $this->MODULE_ID, "\Webcomp\Market\Property\FormText", "OnIBlockPropertyBuildList");
        $event->registerEventHandler(
            "iblock", 'OnIBlockPropertyBuildList', $this->MODULE_ID,
            "\Webcomp\Market\Property\FormBind", "OnIBlockPropertyBuildList");
        $event->registerEventHandler(
            "iblock", 'OnIBlockPropertyBuildList', $this->MODULE_ID,
            "\Webcomp\Market\Property\FormFile", "OnIBlockPropertyBuildList");
        $event->registerEventHandler(
            "iblock", 'OnIBlockPropertyBuildList', $this->MODULE_ID,
            "\Webcomp\Market\Property\FormRating", "OnIBlockPropertyBuildList");
        $event->registerEventHandler(
            "iblock", 'OnIBlockPropertyBuildList', $this->MODULE_ID,
            "\Webcomp\Market\Property\FormAddress",
            "OnIBlockPropertyBuildList");
        // Highload custom tab
        $event->registerEventHandler(
            "main", 'OnAdminTabControlBegin', $this->MODULE_ID, "CMarketEvent",
            "OnAdminHighloadBlockTabHandler");
        $event->registerEventHandler(
            "", 'WebCompMarketOrdersOnBeforeUpdate', $this->MODULE_ID,
            "CMarketEvent", "OnBeforeUpdateHLMarketOrders");
        // Registration events
        $event->registerEventHandler(
            "main", 'OnBeforeUserRegister', $this->MODULE_ID, "CMarketEvent",
            "OnBeforeUserUpdateHandler");
        $event->registerEventHandler(
            "main", 'OnBeforeUserUpdate', $this->MODULE_ID, "CMarketEvent",
            "OnBeforeUserUpdateHandler");
    }

    // Метод снятия регистрации с событий модуля
    public function UnInstallEvents() {
        $event = EventManager::getInstance();
        // Property
        $event->unRegisterEventHandler(
            "iblock", 'OnIBlockPropertyBuildList', $this->MODULE_ID, "\Webcomp\Market\Property\FormString", "OnIBlockPropertyBuildList");
        $event->unRegisterEventHandler(
            "iblock", 'OnIBlockPropertyBuildList', $this->MODULE_ID, "\Webcomp\Market\Property\FormPhone", "OnIBlockPropertyBuildList");
        $event->unRegisterEventHandler(
            "iblock", 'OnIBlockPropertyBuildList', $this->MODULE_ID, "\Webcomp\Market\Property\FormEmail", "OnIBlockPropertyBuildList");
        $event->unRegisterEventHandler(
            "iblock", 'OnIBlockPropertyBuildList', $this->MODULE_ID, "\Webcomp\Market\Property\FormText", "OnIBlockPropertyBuildList");
        $event->unRegisterEventHandler(
            "iblock", 'OnIBlockPropertyBuildList', $this->MODULE_ID, "\Webcomp\Market\Property\FormBind", "OnIBlockPropertyBuildList");
        $event->unRegisterEventHandler(
            "iblock", 'OnIBlockPropertyBuildList', $this->MODULE_ID, "\Webcomp\Market\Property\FormFile", "OnIBlockPropertyBuildList");
        $event->unRegisterEventHandler(
            "iblock", 'OnIBlockPropertyBuildList', $this->MODULE_ID, "\Webcomp\Market\Property\FormRating", "OnIBlockPropertyBuildList");
        $event->unRegisterEventHandler(
            "iblock", 'OnIBlockPropertyBuildList', $this->MODULE_ID, "\Webcomp\Market\Property\FormAddress", "OnIBlockPropertyBuildList");
        // Highload custom tab
        $event->unRegisterEventHandler(
            "main", 'OnAdminTabControlBegin', $this->MODULE_ID, "CMarketEvent", "OnAdminHighloadBlockTabHandler");
        // Registration events
        $event->unRegisterEventHandler(
            "main", 'OnBeforeUserRegister', $this->MODULE_ID, "CMarketEvent", "OnBeforeUserUpdateHandler");
        $event->unRegisterEventHandler(
            "main", 'OnBeforeUserUpdate', $this->MODULE_ID, "CMarketEvent", "OnBeforeUserUpdateHandler");
    }

    // Метод создания в базе данных таблиц и полей
    public function InstallDB()
    {
        // Регистрация модуля
        ModuleManager::registerModule($this->MODULE_ID);
        RegisterModuleDependences("main", "OnPageStart", $this->MODULE_ID);

        return true;
    }

    // Метод удаления из базе данных таблиц и зничений
    public function UnInstallDB() {
        Option::delete($this->MODULE_ID);

        return true;
    }

    public function DoInstall(){
        global $APPLICATION;

        // Для работы модуля необходимо ядро D7 и версия битрикс не ниже 20
        if( $this->isVersion() ) {

            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();

            $APPLICATION->includeAdminFile(Loc::getMessage("WEBCOMP_MARKET_INSTALL_TITLE", ["#MODULE_ID#" => $this->MODULE_ID]), $this->GetPath()."/install/step.php");
        } else {
            $APPLICATION->ThrowException(Loc::getMessage("WEBCOMP_MARKET_ERROR_MAIN_VERSION"));
            return;
        }
    }

    public function DoUninstall() {
        global $APPLICATION;

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        if($request["step"] < 2) {
            $APPLICATION->includeAdminFile(Loc::getMessage("WEBCOMP_MARKET_UNINSTALL_TITLE", ["#MODULE_ID#" => $this->MODULE_ID]), $this->GetPath()."/install/unstep1.php");
        } elseif($request["step"] == 2) {

            $this->UnInstallEvents();
            $this->UnInstallFiles();

            // Без сохраниния данных в базе
            if ($request["savedata"] !== "Y") {
                $this->UnInstallDB();
            }
            UnRegisterModuleDependences("main", "OnPageStart",
                $this->MODULE_ID);
            ModuleManager::UnRegisterModule($this->MODULE_ID);

            $APPLICATION->includeAdminFile(Loc::getMessage("WEBCOMP_MARKET_UNINSTALL_TITLE"),
                $this->GetPath()."/install/unstep2.php");

        }
    }

    // Метод вывода прав доступа к модулю
    public function GetModuleRightList()
    {
        return [
            "reference_id" => ["D", "K", "S", "W"],
            "reference"    => [
                "[D] ".Loc::getMessage("WEBCOMP_MARKET_DENIED"),
                "[K] ".Loc::getMessage("WEBCOMP_MARKET_READ_COMPONENT"),
                "[S] ".Loc::getMessage("WEBCOMP_MARKET_WRITE_SETTINGS"),
                "[W] ".Loc::getMessage("WEBCOMP_MARKET_FULL"),
            ],
        ];
    }

    //метод получения всех файлов нужного расширения для перекодировки в случае если сайт в UTF-8
    protected function getFileList(
        $folder,
        $arrExtension
        = [
            'php',
            'txt',
            'css',
            'js',
            'html',
            'htaccess',
            'pug',
            'scss',
            'ini',
            'map',
        ]
    ): array {
        $res = [];
        $iterator = new DirectoryIterator($folder);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isDot()) {
                continue;
            }
            if ($fileinfo->isDir()) {
                if ($fileinfo->getBasename() == 'lang')//исключаем папки lang
                {
                    continue;
                }
                $res = array_merge($res,
                    $this->getFileList($fileinfo->getPathname()));
                continue;
            }
            if (in_array($fileinfo->getExtension(), $arrExtension)) {
                $res[] = $fileinfo->getPathname();
            }
        }

        return $res;
    }

    //метод меняет кодировку файла на utf-8. На входе массив файлов
    protected function replaceEncoding($files)
    {
        if ( ! is_array($files)) {
            $files[] = $files;
        }
        foreach ($files as $filePath) {
            $file_string = file_get_contents($filePath);
            if ( ! mb_check_encoding($file_string, 'UTF-8')
                or ! ($file_string
                    === mb_convert_encoding(mb_convert_encoding($file_string,
                        'UTF-32',
                        'UTF-8'), 'UTF-8', 'UTF-32'))
            ) {
                $file_string = mb_convert_encoding($file_string, 'UTF-8',
                    'windows-1251');
                file_put_contents($filePath, $file_string);
            }
        }
    }

    protected function isUtf8ReplaceFilesEncoding()
    {
        if (SITE_CHARSET == "UTF-8") {
            $dir = $_SERVER['DOCUMENT_ROOT']
                ."/bitrix/modules/{$this->MODULE_ID}/";
            $files = $this->getFileList($dir);
            $this->replaceEncoding($files);
        }
    }

}
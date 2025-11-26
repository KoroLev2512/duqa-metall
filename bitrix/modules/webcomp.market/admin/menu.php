<?
use Bitrix\Main\Localization\Loc;
CJSCore::Init(array('jquery3'));

AddEventHandler('main', 'OnBuildGlobalMenu',
    'OnBuildGlobalMenuHandlerWebCompMarket');
function OnBuildGlobalMenuHandlerWebCompMarket(&$arGlobalMenu, &$arModuleMenu)
{
    if ( ! defined('WEBCOMP_MARKET_MENU_INCLUDED')) {
        define('WEBCOMP_MARKET_MENU_INCLUDED', true);

        Webcomp\Market\Constants::getAllHightLoadBlocks();

        Loc::loadMessages(__FILE__);
        $moduleID = 'webcomp.market';

        $GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/".$moduleID
            ."/main.css");

        $GLOBALS['APPLICATION']->AddHeadScript("/bitrix/js/".$moduleID
            ."/script.js");

        if ($GLOBALS['APPLICATION']->GetGroupRight($moduleID) >= 'R') {
            $arMenu = array(
                'menu_id'  => 'global_menu_webcomp_market',
                'text'     => Loc::getMessage('WEBCOMP_MARKET_GLOBAL_MENU_TEXT'),
                'title'    => Loc::getMessage('WEBCOMP_MARKET_GLOBAL_MENU_TITLE'),
                'sort'     => 1000,
                'items_id' => 'global_menu_WEBCOMP_MARKET_items',
                'icon'     => 'webcomp-market',
                'items'    => array(
                    /* array(
                        'text'      => Loc::getMessage('WEBCOMP_MARKET_MENU_CONTROL_CENTER_TEXT'),
                        'title'     => Loc::getMessage('WEBCOMP_MARKET_MENU_CONTROL_CENTER_TITLE'),
                        'sort'      => 10,
                        'url'       => '/bitrix/admin/'.$moduleID
                            .'_center.php',
                        'icon'      => 'webcomp-market__center',
                        'page_icon' => 'pi_control_center',
                        'items_id'  => 'control_center',
                    ), */
                    array(
                        'text'      => Loc::getMessage('WEBCOMP_MARKET_MENU_CONTROL_SETTINGS_TEXT'),
                        'title'     => Loc::getMessage('WEBCOMP_MARKET_MENU_CONTROL_SETTINGS_TITLE'),
                        'sort'      => 20,
                        'url'       => '/bitrix/admin/'.$moduleID
                            .'_settings.php',
                        'icon'      => 'webcomp-market__settings',
                        'page_icon' => 'pi_control_setting',
                        'items_id'  => 'control_setting',
                    ),
                    array(
                        'text'      => Loc::getMessage('WEBCOMP_MARKET_MENU_CONTROL_ORDERS_TEXT'),
                        'title'     => Loc::getMessage('WEBCOMP_MARKET_MENU_CONTROL_ORDERS_TITLE'),
                        'sort'      => 30,
                        'url'       => '/bitrix/admin/highloadblock_rows_list.php?ENTITY_ID='
                            .$GLOBALS['WEBCOMP']['HLBLOCKS']["WebCompMarketOrders"],
                        'icon'      => 'webcomp-market__orders',
                        'page_icon' => 'pi_control_orders',
                        'items_id'  => 'control_orders',
                    ),
				),
			);

			if(!isset($arGlobalMenu['global_menu_webcomp'])){
				$arGlobalMenu['global_menu_webcomp'] = [
                    'menu_id'  => 'global_menu_webcomp',
                    'text'     => Loc::getMessage('WEBCOMP_MARKET_GLOBAL_MENU_HEADING'),
                    'title'    => Loc::getMessage('WEBCOMP_MARKET_GLOBAL_MENU_TITLE'),
                    'sort'     => 1000,
                    'items_id' => 'global_menu_webcomp_items',
                ];
            }

			$arGlobalMenu['global_menu_webcomp']['items'][$moduleID] = $arMenu;
		}
	}
}

?>

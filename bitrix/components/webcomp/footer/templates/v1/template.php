<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Localization\Loc;
?>

<footer class="footer footer-v1">

    <?
    $APPLICATION->IncludeComponent(
        "webcomp:form",
        "callorder_footer",
        [
            "CACHE_FILTER"       => "N",
            "CACHE_TIME"         => "0",
            "CACHE_TYPE"         => "A",
            "ELEMENTS_COUNT"     => "20",
            "FIELD_CODE"         => "",
            "FILTER_NAME"        => "",
            "IBLOCK_ID"          => $GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_callorder'],
            "IBLOCK_TYPE"        => "forms",
            "PROPERTY_CODE"      => "",
            "SHOW_ONLY_ACTIVE"   => "Y",
            "SORT_BY1"           => "SORT",
            "SORT_BY2"           => "NAME",
            "SORT_ORDER1"        => "ASC",
            "SORT_ORDER2"        => "ASC",
            "COMPONENT_TEMPLATE" => "popup",
            "EMAIL_EVENT_ID"     => "WEBCOMP_CALLORDER",
            "BIND_ELEMENTS"      => "",
            "FORM_NAME"          => "CALLORDER_FOOTER",
        ],
        false
    );
    ?>

    <div class="footer__top">
        <div class="container">
            <div class="row footer__top-row">
                <div class="footer__col">
                    <? $APPLICATION->IncludeComponent(
                        "webcomp:menu",
                        "footer_menu",
                        [
                            "CACHE_TIME"                      => "36000000",
                            "CACHE_TYPE"                      => "A",
                            "MAX_DEPTH"                       => "1",
                            "TYPE_MENU"                       => "bottom_left",
                            "USE_CATALOG"                     => "N",
                            "COMPONENT_TEMPLATE"              => "footer_menu",
                            "START_DIRECTORY"                 => SITE_DIR,
                            "IBLOCK_TYPE"                     => "catalog",
                            "IBLOCK_ID"                       => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'],
                            "CATALOG_PATH"                    => "/catalog/",
                            "CATALOG_ONLY"                    => "N",
                            "PARAMS_CATALOG_FIELD_CODE"       => [
                                0 => "ID",
                                1 => "NAME",
                                2 => "CODE",
                                3 => "",
                            ],
                            "PARAMS_CATALOG_MAX_DEPTH"        => "1",
                            "PARAMS_CATALOG_SHOW_ONLY_ACTIVE" => "Y",
                        ],
                        false
                    ); ?>
                </div>

                <div class="footer__col">
                    <? $APPLICATION->IncludeComponent(
                        "webcomp:menu",
                        "footer_menu",
                        [
                            "CACHE_TIME"                      => "36000000",
                            "CACHE_TYPE"                      => "A",
                            "MAX_DEPTH"                       => "1",
                            "TYPE_MENU"                       => "bottom_center",
                            "USE_CATALOG"                     => "N",
                            "COMPONENT_TEMPLATE"              => "footer_menu",
                            "START_DIRECTORY"                 => SITE_DIR,
                            "IBLOCK_TYPE"                     => "catalog",
                            "IBLOCK_ID"                       => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'],
                            "CATALOG_PATH"                    => "/catalog/",
                            "CATALOG_ONLY"                    => "N",
                            "PARAMS_CATALOG_FIELD_CODE"       => [
                                0 => "ID",
                                1 => "NAME",
                                2 => "CODE",
                                3 => "",
                            ],
                            "PARAMS_CATALOG_MAX_DEPTH"        => "1",
                            "PARAMS_CATALOG_SHOW_ONLY_ACTIVE" => "Y",
                        ],
                        false
                    ); ?>
                </div>

                <div class="footer__col">
                    <? $APPLICATION->IncludeComponent(
                        "webcomp:menu",
                        "footer_menu",
                        [
                            "CACHE_TIME"                      => "36000000",
                            "CACHE_TYPE"                      => "A",
                            "MAX_DEPTH"                       => "1",
                            "TYPE_MENU"                       => "bottom_right",
                            "USE_CATALOG"                     => "N",
                            "COMPONENT_TEMPLATE"              => "footer_menu",
                            "START_DIRECTORY"                 => SITE_DIR,
                            "IBLOCK_TYPE"                     => "catalog",
                            "IBLOCK_ID"                       => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'],
                            "CATALOG_PATH"                    => "/catalog/",
                            "CATALOG_ONLY"                    => "N",
                            "PARAMS_CATALOG_FIELD_CODE"       => [
                                0 => "ID",
                                1 => "NAME",
                                2 => "CODE",
                                3 => "",
                            ],
                            "PARAMS_CATALOG_MAX_DEPTH"        => "1",
                            "PARAMS_CATALOG_SHOW_ONLY_ACTIVE" => "Y",
                        ],
                        false
                    ); ?>
                </div>

                <div class="footer__col">
                    <div class="footer__socials">
                        <? $APPLICATION->IncludeComponent("webcomp:highload.getList", "social_bottom", array(
	"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"ELEMENTS_COUNT" => "20",
		"FIELD_CODE" => array(
			0 => "UF_NAME",
			1 => "UF_ICON",
			2 => "UF_LINK",
		),
		"HLBLOCK_ID" => $GLOBALS["WEBCOMP"]["HLBLOCKS"]["WebCompMarketSocial"],
		"USE_FILTER" => "N",
		"USE_SORT" => "Y",
		"COMPONENT_TEMPLATE" => "social_bottom",
		"SORT_FILED" => "ID",
		"SORT_ORDER" => "DESC",
		"FILTER_NAME" => ""
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "N"
	)
); ?>

                    </div>
                </div>
                <div class="footer__col footer__col_right">
                    <div class="row">
                        <div class="footer__contacts">
                            <a class="footer__contacts-title" href="/company/contacts/">
                                <?= Loc::getMessage("WEBCOMP_FOOTER_CONTACTS") ?>
                            </a>

                            <? CMarketView::showPageBlock('footer_phones', 'footer') ?>

                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",
                                    "PATH" => SITE_TEMPLATE_PATH . "/include/footer/email.php"
                                )
                            ); ?>
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",
                                    "PATH" => SITE_TEMPLATE_PATH . "/include/footer/addr.php"
                                )
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer__bottom">
        <div class="container">
            <div class="footer__bottom-row">

                <div class="footer__copy">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => SITE_TEMPLATE_PATH . "/include/footer/copy.php"
                        )
                    ); ?>
                </div>

                <?= CMarket::showVendor() ?>

            </div>
        </div>
    </div>
</footer>



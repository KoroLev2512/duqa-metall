<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

use Bitrix\Main\Localization\Loc;
CMarket::Init();
global $USER;
?>
<!DOCTYPE html>
<html xml:lang="<?= LANGUAGE_ID ?>" lang="<?= LANGUAGE_ID ?>">
<head>
    <title><? $APPLICATION->ShowTitle(); ?></title>
    <? $APPLICATION->ShowHead(); ?>
    <? CMarket::showMeta() ?>
    <style><? $APPLICATION->ShowViewContent('custom_css'); ?></style>
</head>
<body class="<?= CMarket::getBodyClass() ?>">
<? $APPLICATION->ShowPanel() ?>
<div class="wrapper">
    <header class="header <?= $isMainPage ? '' : 'header_second' ?>">
        <div class="header__top">
            <div class="container">
                <div class="header__top-row">
                    <div class="header__top-left">
                        <!--button(type='button').header__city Москва-->
                    </div>
                    <div class="header__top-right">
                        <div class="header__controls">
                            <a class="header__control jsSearch" href="#">
                                <?= CMarketView::showIcon('magnifier', 'header__control-svg') ?>
                                <span class="header__control-txt"><?=Loc::getMessage('WEBCOMP_HEADER_SEARCH')?></span>
                            </a>
                            <? if ($WEBCOMP["SETTINGS"]["WEBCOMP_CHECKBOX_LK"] === "Y"): ?>
                                <a class="header__control" href="/personal/">
                                    <?= CMarketView::showIcon('cabinet', 'header__control-svg') ?>
                                    <span class="header__control-txt">
                                        <?= ($USER->GetLogin() ? $USER->GetLogin() : Loc::getMessage("WEBCOMP_USER_ENTER")) ?>
                                    </span>
                                </a>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="header__middle">
            <div class="container">
                <div class="header__middle-row row">
                    <div class="header__middle-left">
                        <div class="header__mmenu">
                            <button class="mmenu-btn jsMenuOpen" type="button">
                                <?= CMarketView::showIcon('menu', 'mmenu-btn__svg') ?>
                            </button>
                        </div>
                        <div class="header__logo">
                            <? CMarketView::showPageBlock('header_logo', 'header') ?>
                            <? CMarketView::showPageBlock('header_slogan', 'header') ?>
                        </div>
                    </div>
                    <div class="header__middle-right">
                        <div class="header__info">
                            <div class="worktime">
                                <div class="worktime__left">
                                    <?= CMarketView::showIcon('time', 'worktime__svg') ?>
                                </div>
                                <div class="worktime__right">
                                    <div class="worktime__title"><?= Loc::getMessage("WEBCOMP_WORKING_TIME") ?></div>
                                    <div class="worktime__txt">
                                        <? $APPLICATION->IncludeComponent(
                                            "bitrix:main.include",
                                            "",
                                            array(
                                                "AREA_FILE_SHOW" => "file",
                                                "AREA_FILE_SUFFIX" => "inc",
                                                "EDIT_TEMPLATE" => "",
                                                "PATH" => SITE_TEMPLATE_PATH . "/include/worktime.php"
                                            )
                                        ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="header__links">
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",
                                    "PATH" => SITE_TEMPLATE_PATH . "/include/email.php"
                                )
                            ); ?>

                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",
                                    "PATH" => SITE_TEMPLATE_PATH
                                        . "/include/address.php",
                                )
                            ); ?>
                        </div>

                        <div class="header__phones">
                            <? CMarketView::showPageBlock('header_phones', 'header') ?>

                            <button class="header__call"
                                    data-trigger="click"
                                    data-target="CALLORDER"
                                    type="button"><?= Loc::getMessage("WEBCOMP_CALLORDER_BTN") ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header__bottom">
            <div class="container">
                <div class="header__bottom-row">
                    <nav class="<?= $isMainPage ? "" : "menu_white" ?> header__menu menu">

                        <? $APPLICATION->IncludeComponent(
                            "webcomp:menu",
                            "menu_with_catalog",
                            [
                                "TYPE_MENU"                       => "top",
                                "MAX_DEPTH"                       => "3",
                                "USE_CATALOG"                     => "Y",
                                "CATALOG_PATH"                    => "/catalog/",
                                "IBLOCK_ID"                       => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'],
                                "CATALOG_ONLY"                    => "N",
                                "COMPONENT_TEMPLATE"              => "menu_with_catalog",
                                "IBLOCK_TYPE"                     => "catalog",
                                "PARAMS_CATALOG_MAX_DEPTH"        => "3",
                                "PARAMS_CATALOG_SHOW_ONLY_ACTIVE" => "Y",
                                "CACHE_TYPE"                      => "A",
                                "CACHE_TIME"                      => "36000000",
                                "PARAMS_CATALOG_FIELD_CODE"       => [
                                    0 => "ID",
                                    1 => "NAME",
                                    2 => "CODE",
                                    3 => "UF_ICON",
                                    4 => "",
                                ],
                                "START_DIRECTORY"                 => SITE_DIR,
                            ],
                            false
                        ); ?>
                    </nav>

                </div>
            </div>
        </div>
    </header>

    <? $APPLICATION->ShowViewContent('offer_banner') ?>

    <div class="<?= $isMainPage ? '' : 'page_second' ?> page">
        <? if (!$isMainPage): ?>
        <div class="catalog">
            <div class="container">
                <? $APPLICATION->IncludeComponent("bitrix:breadcrumb",
                    "custom",
                    array(
                        "PATH" => "",
                        "SITE_ID" => "s1",
                        "START_FROM" => "0",
                    ),
                    false
                ); ?>

                <h1 class="catalog__title right__title"><? $APPLICATION->ShowTitle(false) ?></h1>
                <div class="row">
                    <!--left menu start -->

                    <? if ($isShowLeftMenu && !$isDetailNews): ?>
                        <aside class="catalog__left left">
                            <? if ($isCatalog): ?>
                                <? $APPLICATION->IncludeComponent(
                                    "webcomp:menu",
                                    "left_menu",
                                    [
                                        "CACHE_TIME"                      => "36000000",
                                        "CACHE_TYPE"                      => "A",
                                        "MAX_DEPTH"                       => "2",
                                        "TYPE_MENU"                       => "top",
                                        "USE_CATALOG"                     => "Y",
                                        "COMPONENT_TEMPLATE"              => "left_menu",
                                        "START_DIRECTORY"                 => "THIS_DIR",
                                        "IBLOCK_TYPE"                     => "catalog",
                                        "IBLOCK_ID"                       => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'],
                                        "CATALOG_PATH"                    => "/catalog/",
                                        "CATALOG_ONLY"                    => "Y",
                                        "PARAMS_CATALOG_FIELD_CODE"       => [
                                            0 => "ID",
                                            1 => "NAME",
                                            2 => "CODE",
                                            3 => "UF_ICON",
                                        ],
                                        "PARAMS_CATALOG_MAX_DEPTH"        => "2",
                                        "PARAMS_CATALOG_SHOW_ONLY_ACTIVE" => "Y",
                                        "MENU_TITLE"                      => Loc::getMessage("WEBCOMP_CATALOG_MENU_TITLE"),
                                    ],
                                    false
                                ); ?>
                            <? else: ?>

                                <? $APPLICATION->IncludeComponent(
                                    "webcomp:menu",
                                    "left_menu",
                                    array(
                                        "CACHE_TIME" => "36000000",
                                        "CACHE_TYPE" => "N",
                                        "MAX_DEPTH" => "\$depthLevelLeftMenu",
                                        "TYPE_MENU" => "top",
                                        "USE_CATALOG" => "N",
                                        "COMPONENT_TEMPLATE" => "left_menu",
                                        "START_DIRECTORY" => "THIS_DIR",
                                        "MENU_TITLE" => "",
                                        "USE_EXT" => "Y",
                                    ),
                                    false
                                ); ?>
                            <? endif; ?>

                            <? $APPLICATION->ShowViewContent('catalog_filter'); ?>

                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",
                                    "PATH" => SITE_TEMPLATE_PATH
                                        . "/include/banner_left_menu.php",
                                )
                            ); ?>

                            <? $arrSection = ["IBLOCK_SECTION_ID" => [27]] ?>
                            <? $APPLICATION->IncludeComponent("webcomp:element.getList",
                                "inner_banners",
                                [
                                    "CACHE_FILTER"       => "N",
                                    // Кешировать при установленном фильтре
                                    "CACHE_TIME"         => "36000000",
                                    // Время кеширования (сек.)
                                    "CACHE_TYPE"         => "A",
                                    // Тип кеширования
                                    "ELEMENTS_COUNT"     => "20",
                                    // Максимальное количество элементов
                                    "FIELD_CODE"         => [    // Поля
                                                                 0 => "ID",
                                                                 1 => "IBLOCK_ID",
                                                                 2 => "NAME",
                                                                 3 => "PREVIEW_PICTURE",
                                                                 4 => "PREVIEW_TEXT",
                                    ],
                                    "FILTER_NAME"        => "arrSection",
                                    // Имя переменной фильтра
                                    "IBLOCK_ID"          => $GLOBALS['WEBCOMP']['IBLOCKS']['marketing']['webcomp_market_marketing_banner'],
                                    // Код информационного блока
                                    "IBLOCK_TYPE"        => "marketing",
                                    // Тип информационного блока
                                    "PROPERTY_CODE"      => [    // Свойства
                                                                 0 => "UF_PICTURE_DESC",
                                                                 1 => "UF_PICTURE_TAB",
                                                                 2 => "UF_PICTURE_MOB",
                                                                 3 => "UF_LINK_MORE",
                                                                 4 => "UF_LINK_CATALOG",
                                                                 5 => "UF_ORDER_BTN",
                                    ],
                                    "SHOW_ONLY_ACTIVE"   => "Y",
                                    // Показывать только активные элементы
                                    "SORT_BY1"           => "ACTIVE_FROM",
                                    // Поле для первой сортировки
                                    "SORT_BY2"           => "SORT",
                                    // Поле для второй сортировки
                                    "SORT_ORDER1"        => "DESC",
                                    // Направление для первой сортировки
                                    "SORT_ORDER2"        => "ASC",
                                    // Направление для второй сортировки
                                    "COMPONENT_TEMPLATE" => "slider_main_page",
                                    "USE_FILTER"         => "Y",
                                    // Использовать фильтр
                                ],
                                false
                            );
                            ?>
                        </aside>
                    <? endif; ?>
                    <!--left menu end -->
                    <? if ($isCartPage): ?>
                    <main class="w100">
                        <? elseif (!$isShowLeftMenu): ?>
                        <main class="catalog__right w100">
                            <? elseif ($isDetailNews): ?>
                            <main class="new__left">
                                <? else: ?>
                                <main class="catalog__right right">
                                    <? endif; ?>

                                    <? endif; // isMainPage?>

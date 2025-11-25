<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
use Bitrix\Main\Localization\Loc;

?>
<? if (!$isMainPage): ?>
    </main>
    <?php include(\Bitrix\Main\Application::getDocumentRoot()
        .SITE_DIR.'/include/right_column.php') ?>
    </div>
    </div>
    </div>
<?php
endif;
?>
</div>
<? CMarketView::includeBlock("footer", "v1"); ?>
</div>
<div class="debug">
    <div>
        <div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
</div>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
<div class="scroll">
    <div class="container">
        <div class="scroll__wrap">
            <button class="scroll__btn" type="button">
                <?= CMarketView::showIcon('arr-top', 'scroll__btn-svg') ?>
            </button>
        </div>
    </div>
</div>
<div class="fixmenu">
    <div class="container">
        <div class="fixmenu__row row">
            <div class="fixmenu__mmenu">
                <button class="mmenu-btn jsMenuOpen" type="button">
                    <?= CMarketView::showIcon('menu', 'mmenu-btn__svg') ?>
                </button>
            </div>
            <div class="fixmenu__logo">
                <? CMarketView::showPageBlock('header_logo', 'header') ?>
            </div>
            <div class="fixmenu__menu">
                <nav class="menu_small menu_white menu">
                    <? $APPLICATION->IncludeComponent(
                        "webcomp:menu",
                        "menu_with_catalog",
                        [
                            "TYPE_MENU"                       => "top",
                            "MAX_DEPTH"                       => "2",
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
            <div class="fixmenu__controls header__controls">
                <a class="header__control jsSearch header__control_black" href="#">
                    <?= CMarketView::showIcon('magnifier', 'header__control-svg') ?>
                </a>
                <? if ($WEBCOMP["SETTINGS"]["WEBCOMP_CHECKBOX_LK"] === "Y"): ?>
                    <a style="display:none;" class="header__control header__control_black" href="/personal/">
                        <?= CMarketView::showIcon('cabinet', 'header__control-svg') ?>
                    </a>
                <? endif; ?>
            </div>
            <div class="fixmenu__phones">
                <div class="fixmenu__phones-wrap">
                    <? CMarketView::showPageBlock('header_phones', 'header') ?>
                    <button class="header__call"
                            data-trigger="click"
                            data-target="CALLORDER"
                            type="button">
                        <?=Loc::getMessage('CALLORDER')?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="popup">
    <div class="popup__bg"></div>

    <div class="popup__btns">

        <? if ($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_CHECKBOX_E-SHOP"]==="Y"): ?>
            <? if (!$isCartPage): ?>
                <button class="popup__btn jsCartForm" type="button"
                        data-ajax="/ajax.php"
                        data-action="cart">
                    <span class="popup__btn-img">
                        <?= CMarketView::showIcon('cart', 'popup__btn-svg popup__btn-svg_cart') ?>
                        <span class="popup__btn-count cart-icon_count" data-type="cartCount"><?= $cartCount ?></span>

                    </span>
                </button>
            <? endif ?>

            <? if (!$isFavoritePage): ?>
                <button class="popup__btn jsFavoriteForm" type="button"
                        data-ajax="/ajax.php"
                        data-action="favorite">
                    <span class="popup__btn-img">
                        <?= CMarketView::showIcon('heart', 'popup__btn-svg popup__btn-svg_heart') ?>
                        <span class="popup__btn-count favorite-icon_count"
                              data-type="favoriteCount"><?= isset($_SESSION["FAVORITE"]) ? count($_SESSION["FAVORITE"]) : 0 ?></span>
                    </span>
                </button>
            <? endif ?>

            <? if (!$isComparePage): ?>
                <a class="popup__btn" href="/cart/compare/">
                    <span class="popup__btn-img">
                        <?= CMarketView::showIcon('compare', 'popup__btn-svg popup__btn-svg_compare') ?>
                        <span class="popup__btn-count compare-icon_count"
                              data-type="compareCount">
                            <?= isset($_SESSION["COMPARE"]) ? count($_SESSION["COMPARE"]) : 0 ?>
                        </span>
                    </span>
                </a>
            <? endif ?>
        <? endif; ?>

        <button class="popup__btn popup__btn_t" type="button"
                data-event="showForm"
                data-request="/ajax/form/"
                data-form_name="CALLORDER"
                data-form_id="<?= $GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_callorder'] ?>"
                data-email_event_id="WEBCOMP_CALLORDER"
                data-elements_id="">
                <span class="popup__btn-img">
                    <?= CMarketView::showIcon('tel',
                        'popup__btn-svg popup__btn-svg_tel') ?>
                </span>
        </button>

        <button class="popup__btn popup__btn_t" type="button"
                data-event="showForm"
                data-request="/ajax/form/"
                data-form_name="REVIEWS"
                data-form_id="<?= $GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_reviews'] ?>"
                data-email_event_id="WEBCOMP_REVIEWS"
                data-elements_id="">
                <span class="popup__btn-img">
                    <?= CMarketView::showIcon('review',
                        'popup__btn-svg popup__btn-svg_review') ?>
                </span>
        </button>

        <button class="popup__btn popup__btn_t" type="button"
                data-event="showForm"
                data-request="/ajax/form/"
                data-form_name="QUESTION"
                data-form_id="<?= $GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_question'] ?>"
                data-email_event_id="WEBCOMP_ASK_QUESTION"
                data-elements_id="">
                <span class="popup__btn-img">
                    <?= CMarketView::showIcon('callback',
                        'popup__btn-svg popup__btn-svg_callback') ?>
                </span>
        </button>
    </div>
    <div class="popup__tabs">
        <div class="popup__tab"></div>
    </div>
</div>
<div class="mmenu">
    <div class="mmenu__main">
        <div class="mmenu__top">
            <div class="mmenu__top-left">
                <button class="mmenu__title jsMenuClose" type="button">
                    <?= CMarketView::showIcon('arrow-s-left', 'mmenu__title-svg') ?>
                    <span class="mmenu__title-title"><?= Loc::getMessage("MENU") ?></span>
                </button>
            </div>
            <div class="mmenu__top-right">
                <? if ($WEBCOMP["SETTINGS"]["WEBCOMP_CHECKBOX_LK"] === "Y"): ?>
                    <a style="display:none;" class="mmenu__enter" href="/personal/">
                        <?= CMarketView::showIcon('cabinet', 'mmenu__enter-svg') ?>
                        <span class="mmenu__enter-txt"><?= Loc::getMessage("ENTER") ?></span>
                    </a>
                <? endif; ?>
                <button class="mmenu__search jsSearch" type="button">
                    <?= CMarketView::showIcon('magnifier', 'mmenu__search-svg') ?>
                </button>
            </div>
        </div>
        <div class="mmenu__bottom">
            <? if($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_CHECKBOX_E-SHOP"] === "Y"): ?>
                <div class="mmenu__controls mcontrols">
                    <? if (!$isCartPage): ?>
                        <a class="mcontrol" href="/cart/">
                            <?= CMarketView::showIcon('cart', 'mcontrol__svg mcontrol__svg_cart') ?>
                            <span class="mcontrol__count" data-type="cartCount"><?= $cartCount ?></span>
                        </a>
                    <? endif ?>

                    <? if (!$isFavoritePage): ?>
                        <a class="mcontrol" href="/cart/favorite/">
                            <?= CMarketView::showIcon('heart', 'mcontrol__svg mcontrol__svg_heart') ?>
                            <span class="mcontrol__count"
                                  data-type="favoriteCount">
                                <?= isset($_SESSION["FAVORITE"]) ? count($_SESSION["FAVORITE"]) : 0 ?>
                            </span>
                        </a>
                    <? endif ?>

                    <? if (!$isComparePage): ?>
                        <a class="mcontrol" href="/cart/compare/">
                            <?= CMarketView::showIcon('compare', 'mcontrol__svg mcontrol__svg_compare') ?>
                            <span class="mcontrol__count"
                                  data-type="compareCount">
                                <?= isset($_SESSION["COMPARE"]) ? count($_SESSION["COMPARE"]) : 0 ?>
                            </span>
                        </a>
                    <? endif ?>
                </div>
            <? endif ?>

            <? $APPLICATION->IncludeComponent(
                "webcomp:menu",
                "menu_with_catalog_mobile",
                [
                    "TYPE_MENU"                       => "top",
                    "MAX_DEPTH"                       => "2",
                    "USE_CATALOG"                     => "Y",
                    "CATALOG_PATH"                    => "/catalog/",
                    "IBLOCK_ID"                       => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'],
                    "CATALOG_ONLY"                    => "N",
                    "COMPONENT_TEMPLATE"              => "menu_with_catalog_mobile",
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

            <? CMarketView::showPageBlock('mobile_menu_phones', 'mobile') ?>

            <div class="mitem">
                <a class="mitem__link"
                   data-trigger="click"
                   data-target="CALLORDER">
                        <span class="mitem__link-img">
                            <?= CMarketView::showIcon('tel', 'mitem__link-svg mitem__link-svg_tel') ?>
                        </span>
                    <span class="mitem__link-txt"><?= Loc::getMessage("CALLORDER") ?></span>
                </a>
            </div>

            <div class="mitem">
                <a class="mitem__link"
                   data-trigger="click"
                   data-target="REVIEWS">
                        <span class="mitem__link-img">
                            <?= CMarketView::showIcon('review', 'mitem__link-svg mitem__link-svg_review') ?>
                        </span>
                    <span class="mitem__link-txt"><?= Loc::getMessage("REVIEWS") ?></span>
                </a>
            </div>

            <div class="mitem">
                <a class="mitem__link"
                   data-trigger="click"
                   data-target="QUESTION">
                        <span class="mitem__link-img">
                            <?= CMarketView::showIcon('callback', 'mitem__link-svg mitem__link-svg_callback') ?>
                        </span>
                    <span class="mitem__link-txt"><?= Loc::getMessage("QUESTION") ?></span>
                </a>
            </div>
        </div>

        <div class="mmenu__contacts">
            <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array(
	"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include/mobile/menu-email.php"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "N"
	)
); ?>

            <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array(
	"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include/mobile/menu-address.php"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "N"
	)
); ?>

        </div>
    </div>
</div>
</div>

<form class="hsearch" action="<?= SITE_DIR ?>search/" method="GET">
    <div class="hsearch__top">
        <div class="container">
            <div class="hsearch__field">
                <input class="hsearch__input" type="search" name="q" placeholder="<?=Loc::getMessage('WEBCOMP_FOOTER_SEARCH_PLACEHOLDER')?>">
                <input class="hsearch__input" type="hidden" name="action" value="search">
                <button class="hsearch__submit btn" type="submit"><?=Loc::getMessage('WEBCOMP_FOOTER_SEARCH')?></button>
                <button class="hsearch__close jsSearchClose" type="button">
                    <?= CMarketView::showIcon('close', 'hsearch__close-svg') ?>
                </button>
            </div>
        </div>
    </div>
    <div class="hsearch__bottom">
        <div class="container hsearch__html"></div>
    </div>
</form>

<template id="tmpl">
    <div class="loader">
        <div class="loader__wrap">
            <div class="inner one"></div>
            <div class="inner two"></div>
            <div class="inner three"></div>
        </div>
    </div>
</template>
</body>

<? $jsParams = [
    "FavoriteList" => $_SESSION["FAVORITE"],
    "CompareList" => $_SESSION["COMPARE"],
    "CartList" => $_SESSION["CART"],
    "isCartPage" => $isCartPage,
    "CatalogSettings" => [
        "decimal" => ($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_STRING_DECIMAL"]) ?: 0,
        "decimalPoint" => ($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_STRING_DECIMAL_POINT"]) ?: ".",
        "thousandthSeporator" => ($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_STRING_THOUSANDTH_SEPORATOR"]) ?: " ",
    ],
    "payItems"=> $jsPayItems,
    "SEO" => [
        "YandexID" => ($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_SEO_YANDEX_COUNT"]) ?: " ",
    ]
];
$jsSeo = [
    "Yandex" => [
        "Enabled" => ($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_SEO_YANDEX_CHECKBOX"]) ?: "N",
        "Code" => ($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_SEO_YANDEX_CODE"]) ?: "",
        "YandexID" => ($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_SEO_YANDEX_COUNT"]) ?: "",
    ]
];
$jsRecaptcha = [
    "status" => ($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_RECAPTCHA_CHECKBOX"]) ?: "N",
    "public" => ($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_RECAPTCHA_PUBLIC_CODE"]) ?: "",
];
?>


<?php $APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    array(
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "inc",
        "EDIT_TEMPLATE" => "",
        "PATH" => "/include/" . $jsSeo["Yandex"]["Code"],
    )
); ?>


<script>
    BX.ready(function () {
        window.JSMarket = new JSMarket(<?=CUtil::PhpToJSObject($jsParams)?>);
        window.JSSeo = <?=CUtil::PhpToJSObject($jsSeo);?>;
        window.JSRecaptcha = <?=CUtil::PhpToJSObject($jsRecaptcha);?>;
    });
</script>
<? if ($jsRecaptcha["status"] == "Y"): ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?= $jsRecaptcha["public"]; ?>"></script>
<? endif; ?>
<? CMarket::end(); ?>

<!-- Yandex.Metrika counter -->
<script>
try {
  (function(m,e,t,r,i,k,a){
    m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
    m[i].l=1*new Date();
    for (var j=0; j<document.scripts.length; j++) 
      if (document.scripts[j].src === r) return;
    k=e.createElement(t),a=e.getElementsByTagName(t)[0],
    k.async=1,k.src=r,a.parentNode.insertBefore(k,a);
  })(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

  ym(104589812, "init", {
      clickmap:true,
      trackLinks:true,
      accurateTrackBounce:true,
      webvisor:true,
      ecommerce:"dataLayer"
  });
} catch(e) {
  console.warn("Yandex Metrika blocked:", e);
}
</script>
<noscript>
  <div><img src="https://mc.yandex.ru/watch/104589812" style="position:absolute; left:-9999px;" alt="" /></div>
</noscript>
<!-- /Yandex.Metrika counter -->

</html>
<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

global $isSearchPage;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 *
 *  _________________________________________________________________________
 * |    Attention!
 * |    The following comments are for system use
 * |    and are required for the component to work correctly in ajax mode:
 * |    <!-- items-container -->
 * |    <!-- pagination-container -->
 * |    <!-- component-end -->
 */

$this->setFrameMode(true);

//die(print_r($arResult['ITEMS']));
if (!empty($arResult['NAV_RESULT'])) {
    $navParams = array(
        'NavPageCount' => $arResult['NAV_RESULT']->NavPageCount,
        'NavPageNomer' => $arResult['NAV_RESULT']->NavPageNomer,
        'NavNum' => $arResult['NAV_RESULT']->NavNum
    );
} else {
    $navParams = array(
        'NavPageCount' => 1,
        'NavPageNomer' => 1,
        'NavNum' => $this->randString()
    );
}

$showTopPager = false;
$showBottomPager = false;
$showLazyLoad = false;

if ($arParams['PAGE_ELEMENT_COUNT'] > 0 && $navParams['NavPageCount'] > 1) {
    $showTopPager = $arParams['DISPLAY_TOP_PAGER'];
    $showBottomPager = $arParams['DISPLAY_BOTTOM_PAGER'];
    $showLazyLoad = $arParams['LAZY_LOAD'] === 'Y' && $navParams['NavPageNomer'] != $navParams['NavPageCount'];
}

$templateLibrary = array('popup', 'ajax', 'fx');
$currencyList = '';

if (!empty($arResult['CURRENCIES'])) {
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$templateData = array(
    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList
);
unset($currencyList, $templateLibrary);

$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = array('CONFIRM' => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

$positionClassMap = array(
    'left' => 'product-item-label-left',
    'center' => 'product-item-label-center',
    'right' => 'product-item-label-right',
    'bottom' => 'product-item-label-bottom',
    'middle' => 'product-item-label-middle',
    'top' => 'product-item-label-top'
);

$discountPositionClass = '';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION'])) {
    foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos) {
        $discountPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
    }
}

$labelPositionClass = '';
if (!empty($arParams['LABEL_PROP_POSITION'])) {
    foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos) {
        $labelPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
    }
}

$arParams['~MESS_BTN_BUY'] = $arParams['~MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_BUY');
$arParams['~MESS_BTN_DETAIL'] = $arParams['~MESS_BTN_DETAIL'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_DETAIL');
$arParams['~MESS_BTN_COMPARE'] = $arParams['~MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_COMPARE');
$arParams['~MESS_BTN_SUBSCRIBE'] = $arParams['~MESS_BTN_SUBSCRIBE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_SUBSCRIBE');
$arParams['~MESS_BTN_ADD_TO_BASKET'] = $arParams['~MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET');
$arParams['~MESS_NOT_AVAILABLE'] = $arParams['~MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE');
$arParams['~MESS_SHOW_MAX_QUANTITY'] = $arParams['~MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCS_CATALOG_SHOW_MAX_QUANTITY');
$arParams['~MESS_RELATIVE_QUANTITY_MANY'] = $arParams['~MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['~MESS_RELATIVE_QUANTITY_FEW'] = $arParams['~MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_FEW');

$arParams['MESS_BTN_LAZY_LOAD'] = $arParams['MESS_BTN_LAZY_LOAD'] ?: Loc::getMessage('CT_BCS_CATALOG_MESS_BTN_LAZY_LOAD');

$generalParams = array(
    'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
    'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
    'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
    'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'],
    'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
    'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
    'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
    'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
    'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
    'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
    'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
    'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
    'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'],
    'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
    'COMPARE_PATH' => $arParams['COMPARE_PATH'],
    'COMPARE_NAME' => $arParams['COMPARE_NAME'],
    'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
    'PRODUCT_BLOCKS_ORDER' => $arParams['PRODUCT_BLOCKS_ORDER'],
    'LABEL_POSITION_CLASS' => $labelPositionClass,
    'DISCOUNT_POSITION_CLASS' => $discountPositionClass,
    'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
    'SLIDER_PROGRESS' => $arParams['SLIDER_PROGRESS'],
    '~BASKET_URL' => $arParams['~BASKET_URL'],
    '~ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
    '~BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
    '~COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
    '~COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
    'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
    'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
    'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],
    'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
    'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
    'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'],
    'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
    'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
    'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE']
);

$obName = 'ob' . preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($navParams['NavNum']));
$containerName = 'container-' . $navParams['NavNum'];

if ($showTopPager) {
    ?>
    <div data-pagination-num="<?= $navParams['NavNum'] ?>">
        <!-- pagination-container -->
        <?= $arResult['NAV_STRING'] ?>
        <!-- pagination-container -->
    </div>
    <?
}

if ($arParams['HIDE_SECTION_DESCRIPTION'] !== 'Y' && !empty($arResult['DESCRIPTION'])) {
    ?>
    <div class="catalog__top">
        <div class="content">
            <div class="bx-section-desc bx-<?= $arParams['TEMPLATE_THEME'] ?>">
                <div class="bx-section-desc-post"><?= $arResult['DESCRIPTION'] ?></div>
            </div>
        </div>
    </div>

    <?
}
?>


<?
$currentSortName = Loc::getMessage("WEBCOMP_MARKET_CATALOG_SORT_DEFAULT");

$arSort = [
    "DEFAULT" => [
        "TEXT" => Loc::getMessage("WEBCOMP_MARKET_CATALOG_SORT_DEFAULT"),
        "NAME" => "DEFAULT",
        "ORDER" => "",
    ],
    "VIEW_ASC" => [
        "TEXT" => Loc::getMessage("WEBCOMP_MARKET_CATALOG_SORT_POPULAR_ASC"),
        "NAME" => "VIEW",
        "ORDER" => "ASC",
    ],
    "VIEW_DESC" => [
        "TEXT" => Loc::getMessage("WEBCOMP_MARKET_CATALOG_SORT_POPULAR_DESC"),
        "NAME" => "VIEW",
        "ORDER" => "DESC",
    ],
    "NAME_ASC" => [
        "TEXT" => Loc::getMessage("WEBCOMP_MARKET_CATALOG_SORT_NAME_ASC"),
        "NAME" => "NAME",
        "ORDER" => "ASC",
    ],
    "NAME_DESC" => [
        "TEXT" => Loc::getMessage("WEBCOMP_MARKET_CATALOG_SORT_NAME_DESC"),
        "NAME" => "NAME",
        "ORDER" => "DESC",
    ],
    "PRICE_ASC" => [
        "TEXT" => Loc::getMessage("WEBCOMP_MARKET_CATALOG_SORT_PRICE_ASC"),
        "NAME" => "property_PRICE",
        "ORDER" => "ASC",
    ],
    "PRICE_DESC" => [
        "TEXT" => Loc::getMessage("WEBCOMP_MARKET_CATALOG_SORT_PRICE_DESC"),
        "NAME" => "property_PRICE",
        "ORDER" => "DESC",
    ],
];

if (isset($_SESSION["SORT"])) {
    foreach ($arSort as $key => $sort) {
        if ($_SESSION["SORT"]["NAME"] == $sort["NAME"]
            && $_SESSION["SORT"]["ORDER"] == $sort["ORDER"]) {
            $arSort[$key]["ACTIVE"] = "Y";

            $currentSortName = $sort["TEXT"];
        }
    }
}

$currentViewName = "block";

$arView = [
    "BLOCK" => [
        "NAME" => "block",
        "ACTIVE" => "Y",
    ],
    "LIST" => [
        "NAME" => "list",
        "ACTIVE" => "N",
    ],
];

if (isset($_SESSION["VIEW_TYPE"])) {
    foreach ($arView as $key => $view) {
        if($_SESSION["VIEW_TYPE"] == $view["NAME"]) {
            $arView[$key]["ACTIVE"] = "Y";
            $currentViewName = $view["NAME"];
        } else {
            $arView[$key]["ACTIVE"] = "N";
        }
    }
}
?>

<? if (!empty($arResult['ITEMS'])): ?>
    <div class="catalog__panel">
        <div class="catalog__filters-btn">
            <button class="filters-btn" type="button">
                <?= CMarketView::showIcon("filter", "filters-btn__svg") ?>
                <span class="filters-btn__txt"><?=Loc::getmessage("WEBCOMP_MARKET_CATALOG_FILTER_TITLE")?></span>
            </button>
        </div>

        <div class="catalog__sort">
            <div class="csort">
                <div class="csort__title"><?= $currentSortName ?></div>
                <div class="csort__drop">
                    <? if ($arSort): ?>
                        <? foreach ($arSort as $sort): ?>
                            <a class="<?= ($sort["ACTIVE"] === "Y") ? "active " : "" ?>csort__link"
                               href="?<?= ($isSearchPage) ? "q=" . $_GET["q"] . "&" : "" ?>sort=<?= $sort["NAME"] ?>&order=<?= $sort["ORDER"] ?>"><?= $sort["TEXT"] ?></a>
                        <? endforeach ?>
                    <? endif ?>
                </div>
            </div>
        </div>

        <? if($arView): ?>
            <div class="catalog__controls">
                <? foreach ($arView as $view): ?>
                    <? $viewName = $view["NAME"]?>
                    <a class="catalog__control <?=($view["ACTIVE"] === "Y") ? "active" : ""?>" href="?view=<?=$viewName?>">
                        <?= CMarketView::showIcon("cat-$viewName", "catalog__control-svg") ?>
                    </a>
                <? endforeach  ?>
            </div>
        <? endif ?>

    </div>

<div class="catalog__list <?=$currentViewName?>" data-entity="<?= $containerName ?>">

    <!-- items-container -->

    <div class="catalog__row catalog-section" data-entity="items-row">

        <? $areaIds = array(); ?>

        <? foreach ($arResult['ITEMS'] as $item): ?>

            <?
            $uniqueId = $item['ID'] . '_' . md5($this->randString() . $component->getAction());
            $areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
            $this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
            $this->AddDeleteAction($uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);

            $arProps = $item["PROPERTIES"];

            // show blocks
            $bShowStickers = $bCanBuy = false;

            $arItem = [];

            if (empty($item["DETAIL_PICTURE"]["SRC"])) {
                if (empty($item["PREVIEW_PICTURE"]["SRC"])) {
                    $arItem["PICTURE"] = "/image/empty.jpg";
                } else {
                    $arItem["PICTURE"] = $item["PREVIEW_PICTURE"]["SRC"];
                }
            } else {
                $arItem["PICTURE"] = $item["DETAIL_PICTURE"]["SRC"];
            }

            $arItem["NAME"] = $item["NAME"];

            // stickers
            if (!empty($arProps["STICKERS"]["VALUE"])) {
                $bShowStickers = true;
                $arItem["STICKER"] = $arProps["STICKERS"]["VALUE_XML_ID"];
            }

            $arItem["AVAILABLE"] = $arProps["AVAILABLE"]["VALUE"];

            if ($arProps["AVAILABLE"]["VALUE_XML_ID"] === "Y") {
                $bCanBuy = true;
            }

            $arItem["PRICE"] = CMarketCatalog::getPrice($arProps["PRICE"]["VALUE"]);
            $arItem["OLD_PRICE"] = CMarketCatalog::getPrice($arProps["OLD_PRICE"]["VALUE"]);

            $arItem["URL"] = $item["DETAIL_PAGE_URL"];
            $arItem["ID"] = $item["ID"];

            ?>

            <div class="catalog__item" data-entity="item" data-type="item">

                <div class="item <?= ($bCanBuy) ? "" : "no-available" ?>">
                    <div class="item__top">
                        <div class="item__img">
                            <a href="<?= $arItem["URL"] ?>" class="item__img-wrap">
                                <img class="item__img-img" src="<?= $arItem["PICTURE"] ?>" alt="<?= $arItem["NAME"] ?>">
                            </a>
                            <? if ($bShowStickers): ?>
                                <div class="item__sticks">
                                    <div class="sticks">
                                        <? foreach ($arItem["STICKER"] as $key => $sticker): ?>
                                            <span class="stick stick_<?= $sticker ?>"><?= Loc::getMessage("WEBCOMP_MARKET_STICKER_"
                                                    .$sticker) ?></span>
                                        <? endforeach ?>
                                    </div>
                                </div>
                            <? endif ?>
                            <div class="item__controls">
                                <button class="item__control item__control_compare"
                                        type="button"
                                        data-event="changeCompareList"
                                        data-request="<?= SITE_DIR ?>ajax/catalog/"
                                        data-id="<?= $arItem["ID"] ?>">
                                    <?= CMarketView::showIcon("compare",
                                        "item__control-svg") ?>
                                </button>
                                <button class="item__control item__control_favorite"
                                        type="button"
                                        data-event="changeFavoriteList"
                                        data-request="<?= SITE_DIR ?>ajax/catalog/"
                                        data-id="<?= $arItem["ID"] ?>">
                                    <?= CMarketView::showIcon("heart",
                                        "item__control-svg") ?>
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="item__bottom">
                        <div class="item__content">
                            <div class="item__avaible">
                                <div class="item__avaible__round"></div>
                                <div class="item__avaible__txt"><?= $arItem["AVAILABLE"] ?></div>
                            </div>
                            <div class="item__prices">
                                <? if (!empty($arItem["PRICE"])): ?>
                                    <div class="item__price price"><?= $arItem["PRICE"] ?></div>
                                <? endif ?>

                                <? if (!empty($arItem["OLD_PRICE"])): ?>
                                    <div class="item__priceold priceold"><?= $arItem["OLD_PRICE"] ?></div>
                                <? endif ?>
                            </div>
                            <a href="<?= $arItem["URL"] ?>"
                               class="item__title"><?= $arItem["NAME"] ?></a>
                        </div>
                        <div class="item__btns">
                            <? if (COption::GetOptionString("webcomp.market", "WEBCOMP_CHECKBOX_E-SHOP", "Y") === "Y" && $bCanBuy): ?>
                                <a href="#" class="item__buy add"
                                   type="button"
                                   data-event="addToCart"
                                   data-request="<?= SITE_DIR ?>ajax/catalog/"
                                   data-id="<?= $arItem["ID"] ?>">
                                    <?= CMarketView::showIcon("check", "add__svg") ?>
                                    <span class="add__txt"><?=Loc::getMessage("WEBCOMP_MARKET_ADD_TO_BASKET")?></span>
                                    <span class="add__txt2 jsCartForm"><?=Loc::getMessage("WEBCOMP_MARKET_IN_CART")?></span>
                                    <svg class="add__mobile">
                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#cart"></use>
                                    </svg>
                                </a>
                                <button type="button" class="item__fast btn3"
                                        data-event="showForm"
                                        data-request="<?= SITE_DIR ?>ajax/form/"
                                        data-form_name="ONE_CLICK_BUY"
                                        data-form_id="<?= $GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_oneclick'] ?>"
                                        data-email_event_id="WEBCOMP_ONE_CLICK_BUY"
                                        data-elements_id="<?= $arItem["ID"] ?>">
                                    <?= CMarketView::showIcon("one", "btn3__svg") ?>
                                    <span class="btn3__txt"><?=Loc::getMessage("WEBCOMP_MARKET_CLICK_ONE_BUY")?></span>
                                </button>
                            <? else: ?>
                                <a href="#"
                                   class="item__buy add"
                                   data-event="showForm"
                                   data-request="<?= SITE_DIR ?>ajax/form/"
                                   data-form_name="ONE_CLICK_BUY"
                                   data-form_id="<?= $GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_oneclick'] ?>"
                                   data-email_event_id="WEBCOMP_ONE_CLICK_BUY"
                                   data-elements_id="<?= $arItem["ID"] ?>">
                                    <span class="add__txt"><?=Loc::getMessage("WEBCOMP_MARKET_ORDER")?></span>
                                </a>
                            <? endif ?>
                        </div>
                    </div>
                    <div class="item__controls item__controls_list">
                        <div class="item__control item__control_compare"
                             data-event="changeCompareList"
                             data-request="<?= SITE_DIR ?>ajax/catalog/"
                             data-id="<?= $arItem["ID"] ?>">
                            <?= CMarketView::showIcon("compare", "item__control-svg") ?>
                        </div>
                        <div class="item__control item__control_favorite"
                             data-event="changeFavoriteList"
                             data-request="<?= SITE_DIR ?>ajax/catalog/"
                             data-id="<?= $arItem["ID"] ?>">
                            <?= CMarketView::showIcon("heart", "item__control-svg") ?>
                        </div>
                    </div>
                </div>

            </div>

        <? endforeach ?>

        <!-- items-container -->

        <?

        if ($showLazyLoad) {
            ?>
            <button class="catalog__more" type="button"
                    data-use="show-more-<?= $navParams['NavNum'] ?>"><?= $arParams['MESS_BTN_LAZY_LOAD'] ?></button>
            <?
        } ?>

    </div>

    <? endif ?>

</div>


<?
if ($showBottomPager) {
    ?>
    <div data-pagination-num="<?= $navParams['NavNum'] ?>">
        <!-- pagination-container -->
        <?= $arResult['NAV_STRING'] ?>
        <!-- pagination-container -->
    </div>
<? } ?>

<?
$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedTemplate = $signer->sign($templateName, 'catalog.section');
$signedParams = $signer->sign(base64_encode(serialize($arResult['ORIGINAL_PARAMETERS'])), 'catalog.section');
?>
<script>
    BX.message({
        BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
        BASKET_URL: '<?=$arParams['BASKET_URL']?>',
        ADD_TO_BASKET_OK: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
        TITLE_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_TITLE_ERROR')?>',
        TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCS_CATALOG_TITLE_BASKET_PROPS')?>',
        TITLE_SUCCESSFUL: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
        BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_BASKET_UNKNOWN_ERROR')?>',
        BTN_MESSAGE_SEND_PROPS: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_SEND_PROPS')?>',
        BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE')?>',
        BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
        COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_OK')?>',
        COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
        COMPARE_TITLE: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_TITLE')?>',
        PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCS_CATALOG_PRICE_TOTAL_PREFIX')?>',
        RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
        RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
        BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
        BTN_MESSAGE_LAZY_LOAD: '<?=CUtil::JSEscape($arParams['MESS_BTN_LAZY_LOAD'])?>',
        BTN_MESSAGE_LAZY_LOAD_WAITER: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_LAZY_LOAD_WAITER')?>',
        SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>'
    });
    var <?=$obName?> = new JCCatalogSectionComponent({
        siteId: '<?=CUtil::JSEscape($component->getSiteId())?>',
        componentPath: '<?=CUtil::JSEscape($componentPath)?>',
        navParams: <?=CUtil::PhpToJSObject($navParams)?>,
        deferredLoad: false, // enable it for deferred load
        initiallyShowHeader: '<?=!empty($arResult['ITEM_ROWS'])?>',
        bigData: <?=CUtil::PhpToJSObject($arResult['BIG_DATA'])?>,
        lazyLoad: !!'<?=$showLazyLoad?>',
        loadOnScroll: !!'<?=($arParams['LOAD_ON_SCROLL'] === 'Y')?>',
        template: '<?=CUtil::JSEscape($signedTemplate)?>',
        ajaxId: '<?=CUtil::JSEscape($arParams['AJAX_ID'])?>',
        parameters: '<?=CUtil::JSEscape($signedParams)?>',
        container: '<?=$containerName?>'
    });
</script>
<!-- component-end -->

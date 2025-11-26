<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);

$templateLibrary = array('popup', 'fx');
$currencyList = '';

if (!empty($arResult['CURRENCIES'])) {
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$templateData = array(
    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList,
    'ITEM' => array(
        'ID' => $arResult['ID'],
        'IBLOCK_ID' => $arResult['IBLOCK_ID'],
        'OFFERS_SELECTED' => $arResult['OFFERS_SELECTED'],
        'JS_OFFERS' => $arResult['JS_OFFERS']
    )
);
unset($currencyList, $templateLibrary);

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
    'ID' => $mainId,
    'DISCOUNT_PERCENT_ID' => $mainId . '_dsc_pict',
    'STICKER_ID' => $mainId . '_sticker',
    'BIG_SLIDER_ID' => $mainId . '_big_slider',
    'BIG_IMG_CONT_ID' => $mainId . '_bigimg_cont',
    'SLIDER_CONT_ID' => $mainId . '_slider_cont',
    'OLD_PRICE_ID' => $mainId . '_old_price',
    'PRICE_ID' => $mainId . '_price',
    'DISCOUNT_PRICE_ID' => $mainId . '_price_discount',
    'PRICE_TOTAL' => $mainId . '_price_total',
    'SLIDER_CONT_OF_ID' => $mainId . '_slider_cont_',
    'QUANTITY_ID' => $mainId . '_quantity',
    'QUANTITY_DOWN_ID' => $mainId . '_quant_down',
    'QUANTITY_UP_ID' => $mainId . '_quant_up',
    'QUANTITY_MEASURE' => $mainId . '_quant_measure',
    'QUANTITY_LIMIT' => $mainId . '_quant_limit',
    'BUY_LINK' => $mainId . '_buy_link',
    'ADD_BASKET_LINK' => $mainId . '_add_basket_link',
    'BASKET_ACTIONS_ID' => $mainId . '_basket_actions',
    'NOT_AVAILABLE_MESS' => $mainId . '_not_avail',
    'COMPARE_LINK' => $mainId . '_compare_link',
    'TREE_ID' => $mainId . '_skudiv',
    'DISPLAY_PROP_DIV' => $mainId . '_sku_prop',
    'DISPLAY_MAIN_PROP_DIV' => $mainId . '_main_sku_prop',
    'OFFER_GROUP' => $mainId . '_set_group_',
    'BASKET_PROP_DIV' => $mainId . '_basket_prop',
    'SUBSCRIBE_LINK' => $mainId . '_subscribe',
    'TABS_ID' => $mainId . '_tabs',
    'TAB_CONTAINERS_ID' => $mainId . '_tab_containers',
    'SMALL_CARD_PANEL_ID' => $mainId . '_small_card_panel',
    'TABS_PANEL_ID' => $mainId . '_tabs_panel'
);
$obName = $templateData['JS_OBJ'] = 'ob' . preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
$name = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
    : $arResult['NAME'];
$title = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
    : $arResult['NAME'];
$alt = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
    : $arResult['NAME'];

$haveOffers = !empty($arResult['OFFERS']);
if ($haveOffers) {
    $actualItem = isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']])
        ? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]
        : reset($arResult['OFFERS']);
    $showSliderControls = false;

    foreach ($arResult['OFFERS'] as $offer) {
        if ($offer['MORE_PHOTO_COUNT'] > 1) {
            $showSliderControls = true;
            break;
        }
    }
} else {
    $actualItem = $arResult;
    $showSliderControls = $arResult['MORE_PHOTO_COUNT'] > 1;
}

$skuProps = array();
$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
$measureRatio = $actualItem['ITEM_MEASURE_RATIOS'][$actualItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
$showDiscount = $price['PERCENT'] > 0;

$showDescription = !empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']);
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';
$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($arResult['PRODUCT']['SUBSCRIBE'] === 'Y' || $haveOffers);

$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCE_CATALOG_BUY');
$arParams['MESS_BTN_ADD_TO_BASKET'] = $arParams['MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCE_CATALOG_ADD');
$arParams['MESS_NOT_AVAILABLE'] = $arParams['MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE');
$arParams['MESS_BTN_COMPARE'] = $arParams['MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCE_CATALOG_COMPARE');
$arParams['MESS_PRICE_RANGES_TITLE'] = $arParams['MESS_PRICE_RANGES_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_PRICE_RANGES_TITLE');
$arParams['MESS_DESCRIPTION_TAB'] = $arParams['MESS_DESCRIPTION_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_DESCRIPTION_TAB');
$arParams['MESS_PROPERTIES_TAB'] = $arParams['MESS_PROPERTIES_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_PROPERTIES_TAB');
$arParams['MESS_COMMENTS_TAB'] = $arParams['MESS_COMMENTS_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_COMMENTS_TAB');
$arParams['MESS_SHOW_MAX_QUANTITY'] = $arParams['MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCE_CATALOG_SHOW_MAX_QUANTITY');
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW');

$positionClassMap = array(
    'left' => 'product-item-label-left',
    'center' => 'product-item-label-center',
    'right' => 'product-item-label-right',
    'bottom' => 'product-item-label-bottom',
    'middle' => 'product-item-label-middle',
    'top' => 'product-item-label-top'
);

$discountPositionClass = 'product-item-label-big';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION'])) {
    foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos) {
        $discountPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
    }
}

$labelPositionClass = 'product-item-label-big';
if (!empty($arParams['LABEL_PROP_POSITION'])) {
    foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos) {
        $labelPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
    }
}
?>

<?

$bShowStickers = false;
$bCanBuy = false;
$bShowRating = false;
$bBrand = false;
$bShowPreviewText = false;
$bShowDetailText = false;
$bShowProps = false;
$bShowDocs = false;
$bShowGallery = false;
$bShowReviews = false;
$bUsePhotoSlider = false;
$bEshop = $GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_CHECKBOX_E-SHOP"] === "Y";

$arProps = $arResult["PROPERTIES"];
$arItem = [];

if (!empty($arResult["DETAIL_PICTURE"]["SRC"]))
    $arItem["PHOTO"][] = $arResult["DETAIL_PICTURE"]["SRC"];

// Проверяем есть ли дополнительные фото
if (isset($arProps["MORE_PHOTO"]) && !empty($arProps["MORE_PHOTO"]["VALUE"])) {
    foreach ($arProps["MORE_PHOTO"]["VALUE"] as $key => $photo) {
        $arItem["PHOTO"][] = CFile::getPath($photo);

    }
}

if (count($arItem["PHOTO"]) > 1) {
    $bUsePhotoSlider = true;
}

// Стикеры
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
$arItem["ARTICLE"] = $arProps["ARTICLE"]["VALUE"];


// Рейтинг товара
if (!empty($arProps["RATING"]["VALUE"])) {
    $bShowRating = true;
    $arItem["RATING"] = $arProps["RATING"]["VALUE"];
    $arItem["COUNT_VOTE"] = (!empty($arProps["COUNT_VOTE"]["VALUE"])) ? $arProps["COUNT_VOTE"]["VALUE"] : false;
}

if (!empty($arProps["BRAND"]["VALUE"])) {
    $bBrand = true;
    $arItem["BRAND"] = [
        "NAME" => $arResult["DISPLAY_PROPERTIES"]["BRAND"]["LINK_ELEMENT_VALUE"][$arProps["BRAND"]["VALUE"]]["NAME"],
        "LINK" => $arResult["DISPLAY_PROPERTIES"]["BRAND"]["LINK_ELEMENT_VALUE"][$arProps["BRAND"]["VALUE"]]["DETAIL_PAGE_URL"],
        "PICTURE" => CFile::getPath($arResult["DISPLAY_PROPERTIES"]["BRAND"]["LINK_ELEMENT_VALUE"][$arProps["BRAND"]["VALUE"]]["PREVIEW_PICTURE"])
    ];
}

if (!empty($arResult["PREVIEW_TEXT"])) {
    $bShowPreviewText = true;
    $arItem["PREVIEW_TEXT"] = $arResult["PREVIEW_TEXT"];
}

if (!empty($arResult["DETAIL_TEXT"])) {
    $bShowDetailText = true;
    $arItem["DETAIL_TEXT"] = $arResult["DETAIL_TEXT"];
}

if (!empty($arResult['DISPLAY_PROPERTIES'])) {
    $bShowProps = true;
    $arItem["PROPERTIES"] = $arResult['DISPLAY_PROPERTIES'];
}

if (!empty($arProps["DOCUMENTS"]["VALUE"])) {
    $bShowDocs = true;
    $arItem["DOCUMENTS"] = $arProps["DOCUMENTS"]["VALUE"];
}

if (!empty($arProps["GALLERY"]["VALUE"])) {
    $bShowGallery = true;
    $arItem["GALLERY"] = $arProps["GALLERY"]["VALUE"];
}

// Выбираем привязанные к тораву отзывы
$arSelect = array("ID", "IBLOCK_ID", "NAME", "ACTIVE_FROM", "PREVIEW_TEXT", "DETAIL_TEXT", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_*"); //IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields выше
$arFilter = array("IBLOCK_ID" => $GLOBALS['WEBCOMP']['IBLOCKS']['content']['webcomp_market_content_reviews'], "ACTIVE" => "Y", "PROPERTY_ELEMENT" => $arResult["ID"]);
$res = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arProps = $ob->GetProperties();

    $arItem["REVIEWS"][$arFields["ID"]]["NAME"] = $arFields["NAME"];
    $arItem["REVIEWS"][$arFields["ID"]]["PREVIEW_TEXT"] = $arProps["MESSAGE"]["VALUE"]["TEXT"];
    $arItem["REVIEWS"][$arFields["ID"]]["DETAIL_TEXT"] = $arProps["MESSAGE"]["VALUE"]["TEXT"];
    $arItem["REVIEWS"][$arFields["ID"]]["PREVIEW_PICTURE"] = CFile::getPath($arProps["PHOTO"]["VALUE"]);
    $arItem["REVIEWS"][$arFields["ID"]]["DETAIL_PICTURE"] = CFile::getPath($arProps["PHOTO"]["VALUE"]);
    $arItem["REVIEWS"][$arFields["ID"]]["ACTIVE_FROM"] = $arFields["ACTIVE_FROM"];

    foreach ($arProps as $key => $value) {
        $arItem["REVIEWS"][$arFields["ID"]][$value["CODE"]] = $value["VALUE"];
    }

}

if(isset($arItem["REVIEWS"])) {
    $bShowReviews = true;
}

?>
    <div data-type="item" class="product__main <?= ($bCanBuy) ? "" : "no-available" ?>" id="<?= $itemIds['ID'] ?>" itemscope itemtype="http://schema.org/Product">
        <div class="row">

            <meta itemprop="name" content="<?=$arResult['NAME']?>">
            <link itemprop="url" href="<?=$arResult["DETAIL_PAGE_URL"]?>">
            <meta itemprop="category" content="<?=$arResult["SECTION"]["NAME"]?>">
            <meta itemprop="description" content="<?=strip_tags($arResult["DETAIL_TEXT"])?>">
            <meta itemprop="sku" content="<?=$arResult["ID"]?>">
            <meta itemprop="image" content="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>">

            <div class="" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                <meta itemprop="price" content="<?=$arItem["PRICE"]?>">
                <meta itemprop="priceCurrency" content="RUB">
                <link itemprop="availability" href="http://schema.org/<?=($bCanBuy) ? "InOfStock" : "OutOfStock"?>">
                <link itemprop="url" href="<?=$arResult["DETAIL_PAGE_URL"]?>">
            </div>

            <div class="product__left">
                <div class="product__sliders">
                    <div class="product__slider">
                        <div class="swiper-container <?= ($bUsePhotoSlider) ? "pslider" : "" ?>">
                            <div class="swiper-wrapper">
                                <? foreach ($arItem["PHOTO"] as $key => $photo): ?>
                                    <div class="swiper-slide pslide">
                                        <div class="pslide__img">
                                            <a data-fancybox="gallery" href="<?= $photo ?>">
                                                <img class="pslide__img-img" src="<?= $photo ?>" alt="<?= $arResult["NAME"] ?>"/>
                                            </a>
                                        </div>
                                    </div>
                                <? endforeach ?>

                            </div>
                        </div>

                        <? if ($bUsePhotoSlider): ?>
                            <div class="product__nav">
                                <button class="product__prev product__arr" type="button">
                                    <?=CMarketView::showIcon("arr-l", "product__arr-svg")?>
                                </button>
                                <button class="product__next product__arr" type="button">
                                    <?=CMarketView::showIcon("arr-r", "product__arr-svg")?>
                                </button>
                            </div>
                        <? endif ?>


                        <div class="product__controls">
                            <button class="product__control"
                                    type="button"
                                    data-event="changeCompareList"
                                    data-request="/ajax/catalog/"
                                    data-id="<?= $arResult["ID"] ?>">
                                <?=CMarketView::showIcon("compare", "product__control-svg product__control-svg_compare")?>
                            </button>
                            <button class="product__control"
                                    type="button"
                                    data-event="changeFavoriteList"
                                    data-request="/ajax/catalog/"
                                    data-id="<?= $arResult["ID"] ?>">
                                <?=CMarketView::showIcon("heart", "product__control-svg product__control-svg_heart")?>
                            </button>
                        </div>

                    </div>
                    <? if ($bUsePhotoSlider): ?>
                        <div class="product__thumbs">
                            <div class="swiper-container pthumbs">
                                <div class="swiper-wrapper">
                                    <? foreach ($arItem["PHOTO"] as $key => $photo): ?>
                                        <div class="swiper-slide pthumb">
                                            <div class="pthumb__img">
                                                <img class="pthumb__img-img" src="<?= $photo ?>"
                                                     alt="<?= $arResult["NAME"] ?>"/>
                                            </div>
                                        </div>
                                    <? endforeach ?>
                                </div>
                            </div>
                        </div>
                    <? endif ?>

                </div>
            </div>


            <div class="product__right">

                <? if ($bShowStickers): ?>
                    <div class="product__sticks">
                        <span class="sticks">

                            <? foreach ($arItem["STICKER"] as $key => $sticker): ?>
                                <span class="stick stick_<?= $sticker ?>"><?= GetMessage("STICKER_" . $sticker) ?></span>
                            <? endforeach ?>

                        </span>
                    </div>
                <? endif ?>


                <div class="product__prices">
                    <div class="product__price">
                        <? if (!empty($arItem["PRICE"])): ?>
                            <div class="product__price-val"><?= $arItem["PRICE"] ?></div>
                        <? endif ?>
                        <div class="product__price-avaible item__avaible">
                            <span class="product__avaible__round item__avaible__round"></span>
                            <span class="product__avaible__txt item__avaible__txt"><?= $arItem["AVAILABLE"] ?></span>
                        </div>
                    </div>
                    <? if (!empty($arItem["OLD_PRICE"])): ?>
                        <div class="product__oldprice"><?= $arItem["OLD_PRICE"] ?></div>
                    <? endif ?>

                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => SITE_TEMPLATE_PATH."/include/products_dop_text.php"
                        )
                    );?>
                </div>

                <div class="product__info">
                    <? if (!empty($arItem["ARTICLE"])): ?>
                        <div class="product__info-articul"><?= GetMessage("WEBCOMP_PRODUCT_ACTICLE") ?> <?= $arItem["ARTICLE"] ?></div>
                    <? endif ?>

                    <div class="product__info-top">
                        <? if ($bShowRating): ?>
                            <div class="product__rating">
	          	<span class="rating">
	          		<span class="rating__list">

                        <? for ($i = 0; $i < 5; $i++): ?>
                            <span class="rating__star <?= ($i < $arItem["RATING"]) ? "active" : "" ?>">
                                <?=CMarketView::showIcon("star", "rating__star-svg")?>
                            </span>
                        <? endfor ?>

	                </span>

	                <? if ($arItem["COUNT_VOTE"]): ?>
                        <span class="rating__count">(<?= $arItem["COUNT_VOTE"] ?>)</span>
                    <? endif ?>

	              </span>
                            </div>
                        <? endif ?>

                        <? if ($bBrand): ?>
                            <a class="product__brand" href="<?= $arItem["BRAND"]["LINK"] ?>">
                                <img class="product__brand-img" src="<?= $arItem["BRAND"]["PICTURE"] ?>"
                                     alt="<?= $arItem["BRAND"]["NAME"] ?>"/>
                            </a>
                        <? endif ?>

                    </div>

                    <? if ($bShowPreviewText): ?>
                        <div class="product__info-txt"><?= $arItem["PREVIEW_TEXT"] ?></div>
                    <? endif ?>

                </div>

                <? if ($bCanBuy && $bEshop): ?>
                    <div class="product__btns">
                        <div class="product__add">
                            <div class="product__count">
                                <div class="count count_product">
                                    <button class="citem__count-minus count__minus count__btn" type="button">-</button>
                                    <input class="citem__count-input count__input"
                                           data-type="itemQuantity"
                                           data-event="changeQuantity"
                                           type="number"
                                           min="1"
                                           value="1"
                                           step="1"
                                           max="999"
                                           data-id="<?= $arResult["ID"] ?>">
                                    <button class="citem__count-plus count__plus count__btn"
                                            type="button">+
                                    </button>
                                </div>
                            </div>
                            <a href="#" class="add product__buy add_product"
                               type="button"
                               data-event="addToCart"
                               data-request="/ajax/catalog/"
                               data-id="<?= $arResult["ID"] ?>">
                                <svg class="add__svg">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#check"></use>
                                </svg>
                                <span class="add__txt"><?=GetMessage("WEBCOMP_PRODUCT_ADD_TO_BASKET")?></span>
                                <span class="add__txt2 jsCartForm"><?=GetMessage("WEBCOMP_PRODUCT_IN_BASKET")?></span>
                                <?=CMarketView::showIcon("cart", "add__mobile")?>
                            </a>
                        </div>
                        <button class="product__one btn3" type="button"
                                data-type="changeQuantityBtn"
                                data-event="showForm"
                                data-request="/ajax/form/"
                                data-form_name="ONE_CLICK_BUY"
                                data-form_id="<?=$GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_oneclick']?>"
                                data-email_event_id="WEBCOMP_ONE_CLICK_BUY"
                                data-elements_id="<?= $arResult["ID"] ?>"
                                data-quantity="1">
                            <?=CMarketView::showIcon("one", "btn3__svg")?>
                            <span class="btn3__txt"><?=GetMessage("WEBCOMP_PRODUCT_ONE_CLICK_BUY")?></span>
                        </button>
                    </div>
                <? else: ?>
                    <a href="#"
                       class="item__buy add"
                       data-event="showForm"
                       data-request="/ajax/form/"
                       data-form_name="ONE_CLICK_BUY"
                       data-form_id="<?=$GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_oneclick']?>"
                       data-email_event_id="WEBCOMP_ONE_CLICK_BUY"
                       data-elements_id="<?= $arResult["ID"] ?>"
                       data-quantity="1"
                    >
                        <span class="add__txt"><?=GetMessage("WEBCOMP_PRODUCT_BUY")?></span>
                    </a>
                <? endif ?>
            </div>

        </div>

        <div class="product__sections">
            <? if ($bShowDetailText): ?>
                <div class="product__section">

                    <div class="product__section-title"><?= GetMessage("CT_BCE_CATALOG_DESCRIPTION_TAB") ?></div>
                    <div class="product__section-content content">
                        <?= $arItem["DETAIL_TEXT"] ?>
                    </div>

                </div>
            <? endif ?>

            <? if ($bShowProps): ?>

                <div class="product__section">
                    <div class="product__section-title"><?= GetMessage("CT_BCE_CATALOG_PROPERTIES_TAB") ?></div>
                    <div class="product__section-content content">
                        <table>
                            <tbody>
                            <? foreach ($arItem["PROPERTIES"] as $property): ?>
                                <tr>
                                    <td><?= $property['NAME'] ?></td>
                                    <td class="product__property"><?= (
                                        is_array($property['DISPLAY_VALUE'])
                                            ? implode(' / ', $property['DISPLAY_VALUE'])
                                            : $property['DISPLAY_VALUE']
                                        ) ?></td>
                                </tr>
                                <? unset($property) ?>
                            <? endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <? endif ?>

            <? if ($bShowDocs): ?>
                <div class="product__section">
                    <div class="product__section-title"><?= GetMessage("CT_BCE_CATALOG_DOCUMENTS") ?></div>
                    <div class="product__section-content">
                        <div class="product__docs">
                            <div class="pdocs">

                                <? foreach ($arItem["DOCUMENTS"] as $key => $docs): ?>

                                    <?
                                    $fileInfo = CFile::GetFileArray($docs);
                                    $type = end(explode(".", $fileInfo["ORIGINAL_NAME"]));

                                    if (in_array($type, ["jpg", "png", "jpeg", "webp"])) {
                                        $type = "img";
                                    }

                                    $type = (file_exists($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/images/icons/" . $type . ".svg") ? $type : "file");
                                    ?>

                                    <div class="pdoc">
                                        <div class="pdoc__left">
                                            <img class="pdoc__img"
                                                 src="<?= SITE_TEMPLATE_PATH ?>/images/icons/<?= $type ?>.svg"
                                                 alt="<?= $fileInfo["ORIGINAL_NAME"] ?>"/>
                                        </div>
                                        <div class="pdoc__right">
                                            <div class="pdoc__title"><?= $fileInfo["ORIGINAL_NAME"] ?></div>
                                            <a class="pdoc__link" href="<?= $fileInfo["SRC"] ?>"
                                               download="<?= $fileInfo["ORIGINAL_NAME"] ?>"><?= GetMessage("WEBCOMP_PRODUCT_DOWNLOAD") ?></a>
                                        </div>
                                    </div>

                                <? endforeach ?>


                            </div>
                        </div>
                    </div>
                </div>
            <? endif ?>

            <? if ($bShowGallery): ?>

                <div class="product__section">
                    <div class="product__section-title"><?= GetMessage("CT_BCE_CATALOG_GALLERY") ?></div>
                    <div class="product__section-content">
                        <div class="product__gallery pgallery">

                            <? if (count($arItem["GALLERY"]) > 1): ?>

                                <button class="pgallery__prev pgallery__arr" type="button">
                                    <?=CMarketView::showIcon("arr-l", "pgallery__arr-svg")?>
                                </button>

                            <? endif ?>

                            <div class="swiper-container pgallery__container">
                                <div class="swiper-wrapper">

                                    <? foreach ($arItem["GALLERY"] as $key => $photo): ?>

                                        <div class="swiper-slide pgallery__item">
                                            <div class="pgallery__img">
                                                <img class="pgallery__img-img" src="<?= CFile::getPath($photo) ?>"
                                                     alt="Галерея товара фото №<?= $key ?>"/>
                                            </div>
                                        </div>

                                    <? endforeach ?>

                                </div>
                            </div>

                            <? if (count($arItem["GALLERY"]) > 1): ?>
                                <button class="pgallery__next pgallery__arr" type="button">
                                    <?=CMarketView::showIcon("arr-r", "pgallery__arr-svg")?>
                                </button>
                            <? endif ?>

                        </div>
                        <div class="product__thumbs2">
                            <div class="swiper-container pthumbs2">
                                <div class="swiper-wrapper">
                                    <? foreach ($arItem["GALLERY"] as $key => $photo): ?>
                                        <div class="swiper-slide pthumb2">
                                            <div class="pthumb2__img">
                                                <img class="pthumb__img-img" src="<?= CFile::getPath($photo) ?>"
                                                     alt="<?= GetMessage("WEBCOMP_PRODUCT_GALLERY_PHOTO") ?> №<?= $key ?>"/>
                                            </div>
                                        </div>
                                    <? endforeach ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <? endif ?>



                <div class="product__section">
                    <div class="product__section-title"><?= Loc::getMessage("CT_BCE_CATALOG_REVIEWS") ?></div>
                    <div class="product__section-content">
                        <div class="product__revs">
                            <div class="revs">

                                <div class="revs__txt"><?= Loc::getMessage("CT_BCE_CATALOG_REVIEWS_TEXT") ?></div>

                                <button class="revs__btn btn" type="button" data-trigger="click" data-target="REVIEWS"
                                        data-elements_id="<?= $arResult["ID"] ?>">
                                    <?= Loc::getMessage("CT_BCE_CATALOG_REVIEWS_BTN") ?>
                                </button>
                                <? if ($bShowReviews): ?>
                                <div class="revs__list">
                                    <? foreach ($arItem["REVIEWS"] as $item): ?>
                                        <? $picture = !empty($item["PREVIEW_PICTURE"]) ? $item["PREVIEW_PICTURE"] : SITE_TEMPLATE_PATH."/images/reviewsDefault.png" ?>

                                        <div class="rev">
                                            <div class="rev__left">
                                                <div class="rev__img">
                                                    <div class="rev__img-wrap">
                                                        <img class="rev__img-img" src="<?=$picture ?>"
                                                             alt="<?= $item["NAME"] ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rev__right">
                                                <!-- -date-->
                                                <div class="rev__date"><?= date("d.m.Y", strtotime($item["ACTIVE_FROM"])) ?></div>
                                                <div class="rev__top">
                                                    <div class="rev__top-wrap">
                                                        <div class="rev__left rev__left_m">
                                                            <div class="rev__img">
                                                                <div class="rev__img-wrap">
                                                                    <img class="rev__img-img" src="<?=$picture ?>"
                                                                                                alt="<?= $item["NAME"] ?>"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="rev__top-block">
                                                            <div class="rev__top-left">
                                                                <div class="rev__name"><?= $item["NAME"] ?></div>
                                                                <div class="rev__prof"><?= $item["POSITION"] ?></div>
                                                            </div>
                                                            <div class="rev__top-right">
                                                                <? if (!empty($item["RATING"])): ?>
                                                                    <div class="rev__rating">
                                                                        <span class="rating">
                                                                        <span class="rating__list">

                                                                            <? for ($i = 0; $i < 5; $i++) : ?>
                                                                                <span class="rating__star <?= ($i < $item["RATING"]) ? "active" : "" ?>">
                                                                                    <?=CMarketView::showIcon("star", "rating__star-svg")?>
                                                                                </span>
                                                                            <? endfor ?>

                                                                        </span>
                                                                      </span>
                                                                    </div>
                                                                <? endif ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <? if (!empty($item["DETAIL_TEXT"])): ?>
                                                    <div class="rev__txt">
                                                        <?= $item["DETAIL_TEXT"] ?>
                                                    </div>
                                                <? endif ?>

                                                <? if (!empty($item["MORE_PHOTO"]) || !empty($item["YOUTUBE"])): ?>
                                                    <div class="rev__hidden">

                                                        <? if (!empty($item["MORE_PHOTO"])): ?>
                                                            <div class="rev__imgs">
                                                                <? foreach ($item["MORE_PHOTO"] as $photo): ?>
                                                                    <div class="rev__image">
                                                                        <a class="rev__image-fancy"
                                                                           href="<?= CFile::getPath($photo) ?>"
                                                                           data-fancybox="images1">
                                                                            <img class="rev__image-img"
                                                                                 src="<?= CFile::getPath($photo) ?>"
                                                                                 alt="<?= $item["NAME"] ?>"/>
                                                                        </a>
                                                                    </div>
                                                                <? endforeach ?>
                                                            </div>
                                                        <? endif ?>

                                                        <? if (!empty($item["YOUTUBE"])): ?>
                                                            <div class="rev__yt">
                                                                <iframe class="rev__yt-iframe"
                                                                        src="<?= $item["YOUTUBE"] ?>"></iframe>
                                                            </div>
                                                        <? endif ?>

                                                    </div>


                                                    <div class="rev__bottom">
                                                        <button class="rev__more" type="button"><span
                                                                    class="rev__more-open"><?= GetMessage("WEBCOMP_PRODUCT_OPEN") ?></span><span
                                                                    class="rev__more-close"><?= GetMessage("WEBCOMP_PRODUCT_CLOSE") ?></span></button>
                                                    </div>

                                                <? endif ?>
                                            </div>
                                        </div>
                                    <? endforeach ?>

                                </div>
                                <? endif ?>
                            </div>
                        </div>
                    </div>
                </div>


            <? if ( ! empty($arResult['PROPERTIES']['RECOMMENDED']['VALUE'])): ?>
                <?
                global $recommendProductFilter;
                $recommendProductFilter = ['ID' => $arResult['PROPERTIES']['RECOMMENDED']['VALUE']];

                $APPLICATION->IncludeComponent(
                    "webcomp:element.getList",
                    "recommend_product",
                    [
                        "CACHE_FILTER"          => "N",
                        "CACHE_TIME"            => "36000000",
                        "CACHE_TYPE"            => "A",
                        "COMPONENT_TEMPLATE"    => "recommend_product",
                        "ELEMENTS_COUNT"        => "20",
                        "FIELD_CODE"            => [
                            0 => "ID",
                            1 => "ACTIVE_FROM",
                            2 => "NAME",
                            3 => "PREVIEW_PICTURE",
                            4 => "PREVIEW_TEXT",
                            5 => "CODE",
                        ],
                        "FILTER_NAME"           => "recommendProductFilter",
                        "IBLOCK_ID"             => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'],
                        "IBLOCK_TYPE"           => "catalog",
                        "LINK_LINK"             => "/",
                        "LINK_TITLE"            => GetMessage("WEBCOMP_PRODUCT_ALL_LINK"),
                        "PAGINATION"            => "Y",
                        "PROPERTY_CODE"         => [
                            0 => "OLD_PRICE",
                            1 => "PRICE",
                            2 => "STICKERS",
                            3 => "AVAILABLE",
                            4 => "",
                            5 => "",
                        ],
                        "SHOW_ONLY_ACTIVE"      => "Y",
                        "SORT_BY1"              => "ACTIVE_FROM",
                        "SORT_BY2"              => "SORT",
                        "SORT_ORDER1"           => "DESC",
                        "SORT_ORDER2"           => "ASC",
                        "TITLE"                 => GetMessage("WEBCOMP_PRODUCT_RECOMMENDED"),
                        "DONT_INCLUDE_TEMPLATE" => "N",
                        "USE_FILTER"            => "Y",
                    ],
                    false
                ); ?>
            <? endif; ?>

        </div>

    </div>

    <script>
        BX.message({
            ECONOMY_INFO_MESSAGE: '<?=GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO2')?>',
            TITLE_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR')?>',
            TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS')?>',
            BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR')?>',
            BTN_SEND_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS')?>',
            BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
            BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE')?>',
            BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
            TITLE_SUCCESSFUL: '<?=GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK')?>',
            COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK')?>',
            COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
            COMPARE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE')?>',
            BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
            PRODUCT_GIFT_LABEL: '<?=GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL')?>',
            PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
            RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
            RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
            SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>'
        });

        var <?=$obName?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
    </script>
<?
unset($actualItem, $itemIds, $jsParams);

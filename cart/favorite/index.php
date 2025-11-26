<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Избранное");?>
<div id="favoriteRender">
<?if(isset($_SESSION["FAVORITE"]) && !empty($_SESSION["FAVORITE"])): ?>
<?
global $APPLICATION, $arrFilter;
$arrFilter = ["ID" => array_keys($_SESSION["FAVORITE"])];
    $APPLICATION->IncludeComponent(
        "webcomp:element.getList",
        "products_in_favorite_page",
        [
            "CACHE_FILTER"       => "N",
            "CACHE_TIME"         => "0",
            "CACHE_TYPE"         => "N",
            "COMPONENT_TEMPLATE" => "products_in_favorite_page",
            "ELEMENTS_COUNT"     => "100",
            "FIELD_CODE"         => [
                0 => "ID",
                1 => "NAME",
                2 => "PREVIEW_PICTURE",
                3 => "PREVIEW_TEXT",
                4 => "CODE",
            ],
            "FILTER_NAME"        => "arrFilter",
            "IBLOCK_ID"          => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'],
            "IBLOCK_TYPE"        => "content",
            "PROPERTY_CODE"      => [
                0 => "OLD_PRICE",
                1 => "PRICE",
                2 => "AVAILABLE",
                3 => "",
            ],
            "SHOW_ONLY_ACTIVE"   => "Y",
            "SORT_BY1"           => "ACTIVE_FROM",
            "SORT_BY2"           => "SORT",
            "SORT_ORDER1"        => "DESC",
            "SORT_ORDER2"        => "ASC",
            "TITLE"              => "Корзина",
            "USE_FILTER"         => "Y",
            "LINK_TITLE"         => "Все услуги",
            "LINK_LINK"          => "/",
        ],
        false
    ); ?>

<? else: ?>
    <div class="cart-empty">
        <div class="cart-empty__title">Список пуст</div>
        <div class="cart-empty__text">Исправить это просто: выберите в каталоге
            интересующий товар и нажмите кнопку добавить в избранное.
        </div>
        <a class="cart-empty__btn btn" href="/catalog/">
            <span>В каталог</span>
        </a>
    </div>
<? endif?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
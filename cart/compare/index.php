<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Сравнение"); ?>
<div id="compareRender">
<? if (isset($_SESSION["COMPARE"]) && !empty($_SESSION["COMPARE"])): ?>
    <?
    global $APPLICATION, $arrFilter;
    $arrFilter = ["ID" => array_keys($_SESSION["COMPARE"])]; ?>
    <? $APPLICATION->IncludeComponent(
        "webcomp:element.getList",
        "products_in_compare_page",
        [
            "CACHE_FILTER"          => "N",
            "CACHE_TIME"            => "0",
            "CACHE_TYPE"            => "N",
            "COMPONENT_TEMPLATE"    => "products_in_compare_page",
            "ELEMENTS_COUNT"        => "100",
            "FIELD_CODE"            => [
                0 => "ID",
                1 => "NAME",
                2 => "PREVIEW_PICTURE",
                3 => "PREVIEW_TEXT",
                4 => "CODE",
            ],
            "FILTER_NAME"           => "arrFilter",
            "IBLOCK_ID"             => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'],
            "IBLOCK_TYPE"           => "content",
            "PROPERTY_CODE"         => [
                0  => "OLD_PRICE",
                1  => "PRICE",
                2  => "AVAILABLE",
                3  => "BRAND",
                4  => "WEIGHT",
                5  => "EQUIPMENT",
                6  => "VENDOR",
                7  => "COLOR",
                8  => "DIAGONAL",
                9  => "CAPACITY_ELECTRO",
                10 => "MEMORY",
                11 => "SYSTEM",
                12 => "CAMERA",
                13 => "CPU",
                14 => "SIZES",
                15 => "SPEED_TEHNO",
                16 => "POWER_TEHNO",
                17 => "BATTERY_TYPE",
                18 => "POWER_TYPE_TEHNO",
                19 => "INSTALL",
                20 => "CORD_LENGTH",
                21 => "CAPACITY_INSTR",
                22 => "SPEED_INSTR",
                23 => "NUMBER_ROTATION",
                24 => "POWER_INSTR",
                25 => "POWER_TYPE_INSTR",
                26 => "SIZE_CLOTH",
                27 => "MATERIAL_CLOTH",
                28 => "GENDER",
                29 => "SEASON",
                30 => "COMPOSITION",
                31 => "DIAMETER_OF_WHEELS",
                32 => "SPEED_SPORT",
                33 => "MATERIAL_SPORT",
                34 => "APPOINTMENT",
                35 => "SIZE_SPORT",
                36 => "LIST_COUNT",
                37 => "COLOR_COUNT",
                38 => "LINEST",
                39 => "FORMAT",
                40 => "ZOO_TYPE",
                41 => "TASTE",
                42 => "ZOO_AGE",
                43 => "MATERIAL_ZOO",
                44 => "STORAGE",
                45 => "BOX",
                46 => "",
            ],
            "SHOW_ONLY_ACTIVE"      => "Y",
            "SORT_BY1"              => "ACTIVE_FROM",
            "SORT_BY2"              => "SORT",
            "SORT_ORDER1"           => "DESC",
            "SORT_ORDER2"           => "ASC",
            "TITLE"                 => "Корзина",
            "USE_FILTER"            => "Y",
            "PROPERTIES_COMPARE"    => "Наши услуги",
            "DONT_INCLUDE_TEMPLATE" => "N",
        ],
        false
    ); ?>
<? else: ?>
    <div class="cart-empty">
        <div class="cart-empty__title">Список сравнения пуст</div>
        <div class="cart-empty__text">Исправить это просто: выберите в каталоге
            интересующий товар и нажмите кнопку "В
            сравнение"
            <span class="popup__btn-img">
              <svg class="popup__btn-svg popup__btn-svg_compare bread__link-svg">
                <use xlink:href="/images/icons/sprite.svg#compare"></use>
              </svg>
            </span>
        </div>
        <a class="cart-empty__btn btn" href="/catalog/">
            <span>В каталог</span>
        </a>
    </div>
<? endif ?>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
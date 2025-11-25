<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

// вкл/выкл панели по query-параметру (?debug=irecom или ?debug=Y)
$__dbgOn = isset($_GET['debug']) && ( $_GET['debug']==='irecom' || $_GET['debug']==='Y' || $_GET['debug']==='1' );

// маленький helper для вывода
if (!function_exists('_irecom_dump')) {
    function _irecom_dump($title, $var) {
        echo '<details style="margin:10px 0;padding:6px;border:1px dashed #ccc;border-radius:8px;background:#fafafa">';
        echo '<summary style="cursor:pointer;font-weight:600">🛠 '.htmlspecialchars($title).'</summary>';
        echo '<pre style="white-space:pre-wrap;max-height:360px;overflow:auto;margin:8px 0 0;padding:8px;background:#111;color:#0f0;border-radius:6px">'
            .htmlspecialchars(is_string($var) ? $var : print_r($var, true), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
            .'</pre></details>';
    }
}

if ($__dbgOn) {
    _irecom_dump('Template file', __FILE__);
    _irecom_dump('short_open_tag', ini_get('short_open_tag'));
    _irecom_dump('arParams', $arParams ?? []);
    _irecom_dump('arResult (RAW, до любых обработок)', $arResult ?? []);

    // Показать глобальный фильтр, если задан параметром компонента
    if (!empty($arParams['FILTER_NAME']) && isset($GLOBALS[$arParams['FILTER_NAME']])) {
        _irecom_dump('Global filter: $GLOBALS['.$arParams['FILTER_NAME'].']', $GLOBALS[$arParams['FILTER_NAME']]);
    }

    // Быстрая проверка источника: есть ли активные элементы в IBLOCK_ID
    if (Loader::includeModule('iblock')) {
        $iblockId = (int)($arParams['IBLOCK_ID'] ?? 0);
        if ($iblockId > 0) {
            $cnt = CIBlockElement::GetList([], ['IBLOCK_ID'=>$iblockId, 'ACTIVE'=>'Y'], [], false, ['ID'])->SelectedRowsCount();
            _irecom_dump('Probe: ACTIVE элементов в IBLOCK_ID='.$iblockId, $cnt);
        } else {
            _irecom_dump('Probe warning', 'arParams[IBLOCK_ID] пуст — источник может быть не задан');
        }
    } else {
        _irecom_dump('Module error', 'Модуль iblock не подключился');
    }

    // Проверка структуры результата (вкладки vs обычный ITEMS)
    $structure = [
        'has_ITEMS_key'   => isset($arResult['ITEMS']),
        'looks_like_tabs' => isset($arResult[0]['ITEMS']) || isset($arResult[0]['NAME']),
        'top_keys'        => is_array($arResult) ? array_slice(array_keys($arResult), 0, 10) : [],
        'count'           => is_array($arResult) ? count($arResult) : 0,
    ];
    _irecom_dump('Structure check', $structure);
	_irecom_dump('WEBCOMP IBLOCK_ID (resolved)', (int)($GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'] ?? 0));

    // Подсказка по кастомным классам
    $missing = [];
    if (!class_exists('CMarketCatalog')) $missing[] = 'CMarketCatalog';
    if (!class_exists('CMarketView'))   $missing[] = 'CMarketView';
    if ($missing) _irecom_dump('Предупреждение: не найдены классы', $missing);
}

// ---- (необязательно) универсальная обёртка под вкладки, чтобы блок рисовался даже при обычном $arResult["ITEMS"] ----
$__tabs = [];
if (isset($arResult[0]['ITEMS']) || isset($arResult[1]['ITEMS'])) {
    $__tabs = $arResult; // уже вкладки
} elseif (!empty($arResult['ITEMS'])) {
    $__tabs[] = [
        'NAME'  => $arParams['TITLE'] ?? 'Рекомендуем',
        'ITEMS' => $arResult['ITEMS'],
    ];
}
$__data = $__tabs ?: (is_array($arResult) ? $arResult : []);
?>

<? if (!empty($__data)): ?>
    <div class="irecom">
        <div class="container">
            <div class="irecom__top">
                <h2 class="irecom__title title__title">
                    <?= $arParams["TITLE"] ?>

                    <? if($arParams["LINK"]): ?>
                        <a class="view-all-link" href="<?= $arParams["LINK"] ?>">
                            <span class="view-all-link__icon">›</span>
                        </a>
                    <? endif ?>
                </h2>

                <div class="tabset__buttons">
                    <div class="tabset__select">
                        <select class="tabset__select-select">
                            <? foreach ($__data as $key => $tab): ?>
                                <option value="irecom<?= $key ?>"><?= $tab["NAME"] ?></option>
                            <? endforeach ?>
                        </select>
                    </div>

                    <? foreach ($__data as $key => $tab): ?>
                        <input class="irecom__input tabset__input"
                               type="radio" name="irecom"
                               id="irecom<?= $key ?>" <?= (!$counter++) ? "checked" : "" ?>/>
                        <label class="irecom__label tabset__label"
                               for="irecom<?= $key ?>"><?= $tab["NAME"] ?></label>
                    <? endforeach ?>
                </div>
            </div>

            <div class="irecom__bottom">
                <div class="irecom__tabset tabset">
                    <div class="irecom__tabs tabset__tabs">

                        <? foreach ($__data as $key => $tab): ?>

                            <div class="irecom__tab tabset__tab" data-type="list" data-target="irecom<?= $key ?>">
                                <div class="irecom__slider" data-speed="500" data-pagination="true"
                                     data-index="<?= $key ?>">
                                    <button class="irecom__prev-<?= $key ?> arrow irecom__prev" type="button">
                                        <?= CMarketView::showIcon("prev", "arrow__svg") ?>
                                    </button>
                                    <div class="irecom__container-<?= $key ?> swiper-container irecom__container">
                                        <div class="swiper-wrapper">

                                            <? foreach ($tab["ITEMS"] as $keyS => $item): ?>

                                                <?

                                                $result = [
                                                    "ID" => $item["ID"],
                                                    "NAME" => $item["NAME"],
                                                    "URL" => $item["DETAIL_PAGE_URL"],
                                                    "PICTURE" => (!empty(CFile::getPath($item["PREVIEW_PICTURE"]))) ? CFile::getPath($item["PREVIEW_PICTURE"]) : "/image/empty.jpg",
                                                    "AVAILABLE" => getMessage("WEBCOMP_AVAILABLE_TEXT"),
                                                    "PRICE" => CMarketCatalog::getPrice($item["PROPERTIES"]["PRICE"]["VALUE"]),
                                                    "~PRICE" => $item["PROPERTIES"]["PRICE"]["VALUE"],
                                                    "OLD_PRICE" => CMarketCatalog::getPrice($item["PROPERTIES"]["OLD_PRICE"]["VALUE"]),
                                                    "~OLD_PRICE" => $item["PROPERTIES"]["OLD_PRICE"]["VALUE"],
                                                    "STICKER" => []
                                                ];

                                                //STIKERS
                                                $stickers = [];
                                                $property_enums = CIBlockPropertyEnum::GetList(array("DEF" => "DESC", "SORT" => "ASC"), array("IBLOCK_ID" => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'], "CODE" => "STICKERS"));
                                                while ($enum_fields = $property_enums->GetNext()):
                                                    $stickers[$enum_fields["ID"]] = $enum_fields["XML_ID"];
                                                endwhile;
                                                if (!empty($item["PROPERTIES"]["STICKERS"]["VALUES"])) {
                                                    foreach ($item["PROPERTIES"]["STICKERS"]["VALUES"] as $sticker) {
                                                        $result["STICKER"][$sticker["VALUE"]] = $stickers[$sticker["VALUE"]];
                                                    }
                                                }
                                                $bShowStickers = !empty($result["STICKER"]);

                                                //AVAIBLE
                                                $avaibleArr=[];
                                                $property_enums = CIBlockPropertyEnum::GetList(array("DEF" => "DESC", "SORT" => "ASC"), array("IBLOCK_ID" => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'], "CODE" => "AVAILABLE"));
                                                while ($enum_fields = $property_enums->GetNext()):
                                                    $avaibleArr[] = $enum_fields;
                                                endwhile;
                                                $avaible = $avaibleArr[array_search($item["PROPERTIES"]["AVAILABLE"]["VALUE"],array_column($avaibleArr,"ID"))];

                                                //CAN BUY
                                                $bCanBuy = false;
                                                $result["AVAILABLE"] = $avaible["VALUE"];
                                                if ($avaible["XML_ID"] === "Y") {
                                                    $bCanBuy = true;
                                                }
                                                ?>

                                                <div class="swiper-slide irecom__item" data-type="item">
                                                    <?= CMarketCatalog::renderItem($result, $bCanBuy, $bShowStickers) ?>
                                                </div>
                                            <? endforeach ?>

                                        </div>
                                    </div>

                                    <button class="irecom__next-<?= $key ?> arrow irecom__next" type="button">
                                        <?= CMarketView::showIcon("next", "arrow__svg") ?>
                                    </button>
                                </div>

                                <div class="irecom__pag-<?= $key ?> irecom__pag">
                                    <div class="pag"></div>
                                </div>
                            </div>

                        <? endforeach ?>

                    </div>

                </div>
            </div>
        </div>
    </div>


<? else: ?>
   <div class="irecom"><div class="container">
     <h2 class="irecom__title title__title"><?= $arParams['TITLE'] ?></h2>
     <div class="irecom__empty">Пока ничего нет для «Стоит приглядеться».</div>
   </div></div>
<? endif ?>



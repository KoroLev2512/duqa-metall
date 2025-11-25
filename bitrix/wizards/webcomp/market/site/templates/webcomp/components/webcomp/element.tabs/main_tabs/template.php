<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

$counter = 0;
?>

<? if (!empty($arResult)): ?>
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
                            <? foreach ($arResult as $key => $tab): ?>
                                <option value="irecom<?= $key ?>"><?= $tab["NAME"] ?></option>
                            <? endforeach ?>
                        </select>
                    </div>

                    <? foreach ($arResult as $key => $tab): ?>
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

                        <? foreach ($arResult as $key => $tab): ?>

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


<? endif ?>




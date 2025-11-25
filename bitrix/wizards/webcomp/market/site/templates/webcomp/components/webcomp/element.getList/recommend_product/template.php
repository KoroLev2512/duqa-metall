<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

//Diag\Debug::dump($arResult);
?>
<div class="new__recom">
    <div class="precom">
        <div class="precom__top">
            <div class="precom__title"><?= $arParams['TITLE'] ?></div>
            <div class="precom__nav">
                <button class="precom__prev product__arr" type="button">
                    <svg class="product__arr-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#arr-l"></use>
                    </svg>
                </button>
                <button class="precom__next product__arr" type="button">
                    <svg class="product__arr-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#arr-r"></use>
                    </svg>
                </button>
            </div>
        </div>
        <div class="precom__bottom">
            <div class="precom__slider" data-speed="500" data-pagination="true">
                <div class="swiper-container precom__container">
                    <div class="swiper-wrapper">
                        <? foreach ($arResult["ITEMS"] as $key => $arItem): ?>
                            <?
                            $result = [
                                "ID" => $arItem["ID"],
                                "NAME" => $arItem["NAME"],
                                "URL" => $arItem["DETAIL_PAGE_URL"],
                                "PICTURE" => (!empty(CFile::getPath($arItem["PREVIEW_PICTURE"]))) ? CFile::getPath($arItem["PREVIEW_PICTURE"]) : "/image/empty.jpg",
                                "AVAILABLE" => "В наличии",
                                "PRICE" => CMarketCatalog::getPrice($arItem["PROPERTIES"]["PRICE"]["VALUE"]),
                                "~PRICE" => $arItem["PROPERTIES"]["PRICE"]["VALUE"],
                                "OLD_PRICE" => CMarketCatalog::getPrice($arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"]),
                                "~OLD_PRICE" => $arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"],
                                "STICKER" => []
                            ];

                            //STIKERS
                            $stickers = [];
                            $property_enums = CIBlockPropertyEnum::GetList(array("DEF" => "DESC", "SORT" => "ASC"), array("IBLOCK_ID" => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'], "CODE" => "STICKERS"));
                            while ($enum_fields = $property_enums->GetNext()):
                                $stickers[$enum_fields["ID"]] = $enum_fields["XML_ID"];
                            endwhile;
                            if (!empty($arItem["PROPERTIES"]["STICKERS"]["VALUES"])) {
                                foreach ($arItem["PROPERTIES"]["STICKERS"]["VALUES"] as $sticker) {
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
                            $avaible = $avaibleArr[array_search($arItem["PROPERTIES"]["AVAILABLE"]["VALUE"],array_column($avaibleArr,"ID"))];

                            //CAN BUY
                            $bCanBuy = false;
                            $result["AVAILABLE"] = $avaible["VALUE"];
                            if ($avaible["XML_ID"] === "Y") {
                                $bCanBuy = true;
                            }


                            $this->AddEditAction($arItem['ID'],
                                $arItem['EDIT_LINK'],
                                CIBlock::GetArrayByID($arItem["IBLOCK_ID"],
                                    "ELEMENT_EDIT"));
                            $this->AddDeleteAction($arItem['ID'],
                                $arItem['DELETE_LINK'],
                                CIBlock::GetArrayByID($arItem["IBLOCK_ID"],
                                    "ELEMENT_DELETE"),
                                ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
                            ?>
                            <div id="<?= $this->GetEditAreaId($arItem['ID']); ?>"
                                 class="swiper-slide precom__item">
                                <?= CMarketCatalog::renderItem($result, $bCanBuy, $bShowStickers) ?>
                            </div>
                        <? endforeach ?>
                    </div>
                </div>
                <div class="precom__pag">
                    <div class="pag"></div>
                </div>
            </div>
        </div>
    </div>
</div>

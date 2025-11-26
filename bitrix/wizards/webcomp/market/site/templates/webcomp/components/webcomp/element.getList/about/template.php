<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

//Diag\Debug::dump($arResult);

?>

<? foreach ($arResult['ITEMS'] as $arItem): ?>
    <?
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
        CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
        CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
        ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);

    $arAdvsId = [];

    if ( ! empty($arItem["PROPERTIES"]["UF_ADVS"]["VALUES"])) {
        foreach ($arItem["PROPERTIES"]["UF_ADVS"]["VALUES"] as $item) {
            $arAdvsId[] = $item["VALUE"];
        }
    }

    global $arrFilter;
    $arrFilter = ["ID" => $arAdvsId];

    if ( ! empty($arAdvsId)) {
        $advs = $APPLICATION->IncludeComponent(
            "webcomp:element.getList",
            ".default",
            [
                "CACHE_FILTER"          => "N",
                "CACHE_TIME"            => "0",
                "CACHE_TYPE"            => "A",
                "COMPONENT_TEMPLATE"    => ".default",
                "ELEMENTS_COUNT"        => "100",
                "FIELD_CODE"            => [
                    0 => "ID",
                    1 => "NAME",
                    2 => "PREVIEW_PICTURE",
                    3 => "PREVIEW_TEXT",
                    4 => "CODE",
                ],
                "FILTER_NAME"           => "arrFilter",
                "IBLOCK_ID"             => $GLOBALS['WEBCOMP']['IBLOCKS']['content']['webcomp_market_content_iadvantage'],
                "IBLOCK_TYPE"           => "content",
                "PROPERTY_CODE"         => [
                    0 => "ICON",
                ],
                "SHOW_ONLY_ACTIVE"      => "Y",
                "SORT_BY1"              => "ACTIVE_FROM",
                "SORT_BY2"              => "SORT",
                "SORT_ORDER1"           => "DESC",
                "SORT_ORDER2"           => "ASC",
                "TITLE"                 => "",
                "USE_FILTER"            => "Y",
                "DONT_INCLUDE_TEMPLATE" => "Y",
            ],
            false
        )["ITEMS"];
    }

    ?>
    <div id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
        <div class="row about__row">
            <div class="about__left">
                <img class="about__img"
                     src="<?= $arItem['DETAIL_PICTURE_VALUE']['SRC']; ?>"
                     alt="<?= $arItem['NAME']; ?>"/>
            </div>
            <div class="about__right">
                <div class="content">
                    <?= $arItem['PREVIEW_TEXT']; ?>
                </div>
            </div>
        </div>

        <? if ( ! empty($advs)): ?>
            <div class="about__advantages">
                <div class="aadvantages">
                    <div class="row">
                        <? foreach ($advs as $adv): ?>
                            <div class="aadvantage">
                                <div class="aadvantage__left">
                                    <!--                                <img class="aadvantage__img"-->
                                    <!--                                     src="-->
                                    <? //=CFile::getPath($adv["PREVIEW_PICTURE"])?><!--"-->
                                    <!--                                     alt="-->
                                    <? //=$adv["NAME"]?><!--"/>-->
                                    <?= CMarketView::showSvg($adv["PROPERTIES"]["ICON"]["VALUE"]["SRC"],
                                        'aadvantage__img') ?>
                                </div>
                                <div class="aadvantage__right">
                                    <div class="aadvantage__title"><?= $adv["NAME"] ?></div>
                                    <div class="aadvantage__txt"><?= $adv["PREVIEW_TEXT"] ?></div>
                                </div>
                            </div>
                        <? endforeach; ?>
                    </div>
                </div>
            </div>
        <? endif ?>
        <div class="about__content">
            <div class="content">
                <?= $arItem['DETAIL_TEXT']; ?>
            </div>
        </div>
    </div>
<? endforeach; ?>


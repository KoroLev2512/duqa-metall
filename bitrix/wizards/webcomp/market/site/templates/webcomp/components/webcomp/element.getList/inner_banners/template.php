<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<? if(!empty($arResult["ITEMS"])): ?>
    <? foreach ($arResult["ITEMS"] as $key => $arItem): ?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>

        <div id="<?= $this->GetEditAreaId($arItem['ID']); ?>" class="catalog__inner-banner">
            <? if (!empty($arItem["PROPERTIES"]["UF_LINK_MORE"]["VALUE"])): ?>
                <a class="catalog__banner" href="<?= $arItem["PROPERTIES"]["UF_LINK_MORE"]["VALUE"] ?>">
            <? else: ?>
                <span class="catalog__banner">
            <? endif ?>

                <span class="catalog__banner-image">
                    <picture>
                        <? if(!empty($arItem["PROPERTIES"]["UF_PICTURE_DESC"]["VALUE"]['SRC'])): ?>
                            <source srcset="<?= $arItem["PROPERTIES"]["UF_PICTURE_DESC"]["VALUE"]['SRC'] ?>"
                                    type="image/jpeg" media="(min-width:991.9px)">
                        <? endif ?>

                        <? if(!empty($arItem["PROPERTIES"]["UF_PICTURE_TAB"]["VALUE"]['SRC'])): ?>
                            <source srcset="<?= $arItem["PROPERTIES"]["UF_PICTURE_TAB"]["VALUE"]['SRC'] ?>"
                                    type="image/jpeg" media="(min-width:768.9px)">
                        <? endif ?>

                        <? if(!empty($arItem["PROPERTIES"]["UF_PICTURE_MOB"]["VALUE"]['SRC'])): ?>
                            <source srcset="<?= $arItem["PROPERTIES"]["UF_PICTURE_MOB"]["VALUE"]['SRC'] ?>"
                                    type="image/jpeg" media="(min-width:320px)">
                        <? endif ?>

                        <img class="catalog__banner-img"
                             src="<?= $arItem["PROPERTIES"]["UF_PICTURE_DESC"]["VALUE"]['SRC'] ?>"
                             alt="<?= $arItem["NAME"] ?>">
                    </picture>
                </span>

                <span class="catalog__banner-title"><?= $arItem["NAME"] ?></span>

                <? if (!empty($arItem["PROPERTIES"]["UF_LINK_MORE"]["VALUE"])): ?>
                    </a>
                <? else: ?>
                    </span>
                <? endif ?>
        </div>

    <? endforeach ?>
<? endif ?>
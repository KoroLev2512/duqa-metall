<? if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
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

use Webcomp\Market\Tools;

$this->setFrameMode(true);
?>

<div class="catalog__list news__list">
    <div class="row">

        <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
            <?= $arResult["NAV_STRING"] ?><br/>
        <? endif; ?>

        <? foreach ($arResult['ITEMS'] as $key => $arItem): ?>

            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
                ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
            ?>
            <div class="news__item"
                 id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <a class="nitem" href="<?= $arItem['DETAIL_PAGE_URL']; ?>">
                <span class="nitem__top">
                    <span class="nitem__img">
                        <img class="nitem__img-img"
                             src="<?= $arItem['PREVIEW_PICTURE']['SRC']; ?>"
                             alt="<?= $arItem['NAME']; ?>"/>
                    </span>
                </span>
                    <span class="nitem__bottom">
                    <span class="nitem__date"><?= date("d.m.Y",
                            strtotime($arItem['ACTIVE_FROM'])) ?></span>
                    <span class="nitem__title"><?= $arItem['NAME']; ?></span>
                    <span class="nitem__txt">
                        <?= Tools::cutString($arItem['PREVIEW_TEXT']) ?>
                    </span>
                    <span class="nitem__link link">
                        <span class="link__txt nitem__link-txt">Подробнее</span>
                            <svg class="link__svg nitem__link-svg">
                              <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#arrow-s"></use>
                            </svg>
                    </span>
                </span>
                </a>
            </div>
        <? endforeach; ?>

    </div>

    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
        <?= $arResult["NAV_STRING"] ?>
    <? endif; ?>


</div>

<div class="catalog__bottom">
    <div class="content">
        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            [
                "AREA_FILE_SHOW"   => "file",
                "AREA_FILE_SUFFIX" => "inc",
                "EDIT_TEMPLATE"    => "",
                "PATH"             => "bottom_content.php",
            ]
        ); ?>

    </div>
</div>

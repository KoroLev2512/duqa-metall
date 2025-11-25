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
$this->setFrameMode(true);
?>

<div class="catalog__top">
    <div class="content">

        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            [
                "AREA_FILE_SHOW"   => "file",
                "AREA_FILE_SUFFIX" => "inc",
                "EDIT_TEMPLATE"    => "",
                "PATH"             => "top_content.php",
            ]
        ); ?>
    </div>
</div>

<div class="catalog__list services__list">
    <div class="row">

        <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
            <?= $arResult["NAV_STRING"] ?><br/>
        <? endif; ?>

        <? foreach ($arResult['ITEMS'] as $key => $arItem): ?>
            <?
            // TODO: Добавить ссылку для добавления элемента ADD_LINK
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
                ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
            ?>
            <div class="services__item"
                 id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <a class="iservice" href="<?= $arItem['DETAIL_PAGE_URL']; ?>">
                <span class="iservice__left">
                    <span class="iservice__title"><?= $arItem['NAME']; ?></span>
                  <svg class="iservice__svg">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#arrow"></use>
                  </svg>
                </span>
                    <span class="iservice__right">
                    <img class="iservice__img"
                         src="<?= $arItem['PREVIEW_PICTURE']['SRC']; ?>"
                         alt="<?= $arItem['NAME']; ?>">
                </span>
                </a>
            </div>
        <? endforeach ?>

        <? foreach ($arItem["DISPLAY_PROPERTIES"] as $pid => $arProperty): ?>
            <small>
                <?= $arProperty["NAME"] ?>:&nbsp;
                <? if (is_array($arProperty["DISPLAY_VALUE"])): ?>
                    <?= implode("&nbsp;/&nbsp;",
                        $arProperty["DISPLAY_VALUE"]); ?>
                <? else: ?>
                    <?= $arProperty["DISPLAY_VALUE"]; ?>
                <? endif ?>
            </small><br/>
        <? endforeach; ?>

        <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
            <br/><?= $arResult["NAV_STRING"] ?>
        <? endif; ?>

    </div>
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

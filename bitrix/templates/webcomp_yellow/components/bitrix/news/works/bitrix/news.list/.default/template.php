<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

<div class="catalog__list actions__list">
    <div class="row">

  	<?if($arParams["DISPLAY_TOP_PAGER"]):?>
			<?=$arResult["NAV_STRING"]?><br />
		<?endif;?>

    <? foreach ($arResult['ITEMS'] as $key => $arItem): ?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="actions__item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
            <a class="iaction" href="<?= $arItem['DETAIL_PAGE_URL']; ?>">
                <span class="iaction__img">
                    <img class="iaction__img-img" src="<?= $arItem['PREVIEW_PICTURE']['SRC']; ?>" alt="<?= $arItem['NAME']; ?>"/>
                </span>
                <span class="iaction__bottom">
                    <span class="iaction__title"><?= $arItem['NAME']; ?></span>

                    <? if (!empty($arItem["PROPERTIES"]["PRICE"]["VALUE"])): ?>
                        <span class="iaction__price ipop__prices">
                            <span class="<?=(!empty($arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"])) ? "price_green" : ""?> ipop__price price"><?=$arItem["PROPERTIES"]["PRICE"]["VALUE"]?>
                            </span>

                            <? if (!empty($arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"])): ?>
                                <span class="ipop__priceold priceold"><?=$arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"]?></span> 
                            <? endif ?>
                        </span>
                    <? endif ?>
                </span>
            </a>
        </div>
    <? endforeach; ?>

	<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<br /><?=$arResult["NAV_STRING"]?>
	<?endif;?>

  </div>
</div>

<div class="catalog__bottom">
  <div class="content">
  	<?$APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        Array(
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "PATH" => "bottom_content.php"
        )
    );?>
    
  </div>
</div>

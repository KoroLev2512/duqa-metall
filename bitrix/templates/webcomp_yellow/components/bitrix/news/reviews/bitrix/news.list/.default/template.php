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

<div class="catalog__list news__list">
    <div class="revs">

        <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
            <?= $arResult["NAV_STRING"] ?><br/>
        <? endif; ?>

        <div class="revs__txt">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                [
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "top_content.php",
                ]
            ); ?>
        </div>

        <button class="revs__btn btn"
                type="button"
                data-trigger="click"
                data-target="REVIEWS">
            <?= GetMessage("BUTTON_ADD_REVIEWS") ?>
        </button>

        <div class="revs__list">

            <? foreach ($arResult['ITEMS'] as $key => $arItem): ?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                    CIBlock::GetArrayByID($arItem["IBLOCK_ID"],
                        "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                    CIBlock::GetArrayByID($arItem["IBLOCK_ID"],
                        "ELEMENT_DELETE"),
                    ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);

                $props = $arItem["PROPERTIES"];
                $photo = ( ! empty($props["PHOTO"]["VALUE"]))
                    ? CFIle::getPath($props["PHOTO"]["VALUE"])
                    : SITE_TEMPLATE_PATH."/images/reviewsDefault.png"
                ?>

                <div class="rev"
                     id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                    <div class="rev__left">
                        <div class="rev__img">
                            <div class="rev__img-wrap">
                                <img class="rev__img-img"
                                     src="<?= $photo ?>"
                                     alt="<?= $arItem['NAME']; ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="rev__right">
                        <!-- -date-->
                        <div class="rev__date"><?= date("d.m.Y",
                                strtotime($arItem['ACTIVE_FROM'])) ?></div>
                        <div class="rev__top">
                            <div class="rev__top-wrap">
                                <div class="rev__left rev__left_m">
                                    <div class="rev__img">
                                        <div class="rev__img-wrap">
                                            <img class="rev__img-img"
                                                 src="<?= $photo ?>"
                                                 alt="<?= $arItem['NAME']; ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="rev__top-block">
                                    <div class="rev__top-left">
                                        <? if ( ! empty($arItem['NAME'])): ?>
                                            <div class="rev__name"><?= $arItem['NAME'] ?></div>
                                        <? endif ?>

                                        <? if ( ! empty($props["POSITION"]["VALUE"])): ?>
                                            <div class="rev__prof"><?= $props["POSITION"]["VALUE"] ?></div>
                                        <? endif ?>
                                    </div>

                                    <div class="rev__top-right">
                                        <? if ( ! empty($props["RATING"]["VALUE"])): ?>
                                            <div class="rev__rating">
                                            <span class="rating">
                                                <span class="rating__list">
                                                    <? for (
                                                        $i = 0; $i < 5; $i++
                                                    ): ?>

                                                        <span class="rating__star <?= ($i
                                                            < $props["RATING"]["VALUE"])
                                                            ? "active" : "" ?>">
                                                            <?= CMarketView::showIcon("star",
                                                                "rating__star-svg") ?>
                                                        </span>

                                                    <? endfor ?>
                     
                                                </span>
                                            </span>
                                            </div>
                                        <? endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rev__txt"><?= $arItem['DETAIL_TEXT']; ?></div>

                        <div class="rev__hidden">
                            <? if ( ! empty($props["MORE_PHOTO"]["VALUE"])): ?>
                                <div class="rev__imgs">
                                    <? foreach (
                                        $props["MORE_PHOTO"]["VALUE"] as $key =>
                                        $photo
                                    ): ?>

                                        <?
                                        $photoSRC = CFile::getPath($photo);
                                        ?>

                                        <div class="rev__image">
                                            <a class="rev__image-fancy"
                                               href="<?= $photoSRC ?>"
                                               data-fancybox="images1">
                                                <img class="rev__image-img"
                                                     src="<?= $photoSRC ?>"
                                                     alt="Фото <?= $arItem["NAME"] ?> № <?= $key
                                                     + 1 ?>"/>
                                            </a>
                                        </div>
                                    <? endforeach ?>

                                </div>
                            <? endif ?>

                            <? if ( ! empty($props["YOUTUBE"]["VALUE"])): ?>
                                <div class="rev__yt">
                                    <iframe class="rev__yt-iframe"
                                            src="<?= $props["YOUTUBE"]["VALUE"] ?>"></iframe>
                                </div>
                            <? endif ?>
                        </div>

                        <? if ( ! empty($props["YOUTUBE"]["VALUE"])
                            || ! empty($props["MORE_PHOTO"]["VALUE"])
                        ): ?>
                            <div class="rev__bottom">
                                <button class="rev__more" type="button">
                                    <span class="rev__more-open"><?= GetMessage("BUTTON_SHOW_TEXT") ?></span>
                                    <span class="rev__more-close"><?= GetMessage("BUTTON_HIDE_TEXT") ?></span>
                                </button>
                            </div>
                        <? endif ?>

                    </div>
                </div>

            <? endforeach; ?>
        </div>


    </div>

    <!--<button class="news__more" type="button">Показать еще</button>-->

    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
        <?= $arResult["NAV_STRING"] ?>
    <? endif; ?>


</div>

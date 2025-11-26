<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

$sections2LvlCountMax = 3;
?>
<div class="mmenu__list">
    <?
    foreach ($arResult['ITEMS'] as $itemLvl1):
        $isCatalog = false;
        $isParentLvl1 = false;

        if ($arParams['CATALOG_PATH'] == $itemLvl1['LINK']) {
            $isCatalog = true;
        }
        if ($itemLvl1['IS_PARENT']) {
            $isParentLvl1 = true;
        }
        ?>
        <div class="mitem<?= $isCatalog ? " mitem__catalog" : "" ?>">
            <a class="mitem__link <?= $isParentLvl1 ? "mitem__link_arr" : "" ?>"
               href="<?= $itemLvl1['LINK'] ?>"
               rel=""><?= $itemLvl1['NAME'] ?></a>
            <? if ($isParentLvl1):?>
                <div class="mmenu__wrapper">
                    <div class="mmenu__top">
                        <div class="mmenu__top-left">
                            <button class="mmenu__title jsWrapClose"
                                    type="button">
                                <svg class="mmenu__title-svg">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#arrow-s-left"></use>
                                </svg>
                                <span class="mmenu__title-title"><?= $itemLvl1['NAME'] ?></span>
                            </button>
                        </div>
                        <div class="mmenu__top-right">
                            <button class="mmenu__close jsMenuClose"
                                    type="button">
                                <svg class="mmenu__close-svg">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#close"></use>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="mmenu__bottom">
                        <div class="mmenu__list">
                            <div class="mitem"><a
                                        class="mitem__link mitem__link_b"
                                        href="<?= $itemLvl1['LINK'] ?>">Перейти
                                    в раздел "<?= $itemLvl1['NAME'] ?>"</a>
                            </div>
                            <? foreach ($itemLvl1['CHILD'] as $itemLvl2):
                                $isParentLvl2 = false;
                                if ($itemLvl2['IS_PARENT']) {
                                    $isParentLvl2 = true;
                                }
                                if ($isCatalog) {
                                    if ( ! empty($itemLvl2['SECTION_PAGE_URL'])) {
                                        $itemLvl2['LINK']
                                            = $itemLvl2['SECTION_PAGE_URL'];
                                    }
                                } ?>
                                <div class="mitem"><a
                                            class="mitem__link <?= $isParentLvl2
                                                ? "mitem__link_arr" : "" ?>"
                                            href="<?= $itemLvl2['LINK'] ?>"><?= $itemLvl2['NAME'] ?></a>
                                    <? if ($isParentLvl2):?>
                                        <div class="mmenu__wrapper">
                                            <div class="mmenu__top">
                                                <div class="mmenu__top-left">
                                                    <button class="mmenu__title jsWrapClose"
                                                            type="button">
                                                        <svg class="mmenu__title-svg">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#arrow-s-left"></use>
                                                        </svg>
                                                        <span class="mmenu__title-title"><?= $itemLvl2['NAME'] ?></span>
                                                    </button>
                                                </div>
                                                <div class="mmenu__top-right">
                                                    <button class="mmenu__close jsMenuClose"
                                                            type="button">
                                                        <svg class="mmenu__close-svg">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#close"></use>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mmenu__bottom">
                                                <div class="mmenu__list">
                                                    <div class="mitem"><a
                                                                class="mitem__link mitem__link_b"
                                                                href="<?= $itemLvl2['LINK'] ?>">Перейти
                                                            в раздел
                                                            "<?= $itemLvl2['NAME'] ?>
                                                            "</a></div>
                                                    <? foreach (
                                                        $itemLvl2['CHILD'] as
                                                        $itemLvl3
                                                    ):
                                                        $isParentLvl3 = false;
                                                        if ($itemLvl3['IS_PARENT']) {
                                                            $isParentLvl3
                                                                = true;
                                                        }

                                                        if ($isCatalog) {
                                                            if ( ! empty($itemLvl3['SECTION_PAGE_URL'])) {
                                                                $itemLvl3['LINK']
                                                                    = $itemLvl3['SECTION_PAGE_URL'];
                                                            }
                                                        }
                                                        ?>
                                                        <div class="mitem"><a
                                                                    class="mitem__link"
                                                                    href="<?= $itemLvl3['LINK'] ?>"><?= $itemLvl3['NAME'] ?></a>
                                                        </div>
                                                    <? endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <? endif; ?>
                                </div>
                            <? endforeach; ?>

                        </div>
                    </div>
                </div>
            <? endif; ?>
        </div>
    <? endforeach; ?>
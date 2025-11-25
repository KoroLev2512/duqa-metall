<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<?php

use Bitrix\Main\Context;

$url = Context::getCurrent()->getRequest()->getRequestUri();
if(strpos($url, "?")) {
    $urlWithoutQueryString = substr(Context::getCurrent()->getRequest()
        ->getRequestUri(), 0,
        strpos(Context::getCurrent()->getRequest()->getRequestUri(), "?"));
} else {
    $urlWithoutQueryString = $url;
}

if ( ! empty($urlWithoutQueryString)
    && strpos('/personal/', $urlWithoutQueryString) !== false
) {
    $exitLink = "/?logout=yes&".bitrix_sessid_get();

    $arResult['ITEMS'][] = [
        "NAME"        => getMessage("WEBCOMP_EXIT_BTN_TEXT"),
        "LINK"        => $exitLink,
        "SELECTED"    => 1,
        "DEPTH_LEVEL" => 1,
        "LOGOUT"      => "Y",
    ];
}

if ( ! empty($arResult['ITEMS'])):?>
    <div class="catalog__menu">
        <div class="amenu">
            <? if(!empty($arParams["MENU_TITLE"])): ?>
                <div class="amenu__title"><?=$arParams["MENU_TITLE"]?></div>
            <? endif ?>
            <div class="amenu__list">
                <? foreach ($arResult['ITEMS'] as $item):
                    $isParent = false;
                    if ( ! empty($item['CHILD'])) {
                        $isParent = true;
                    }
                    if ( ! empty($item['SECTION_PAGE_URL'])) {
                        $item['LINK'] = $item['SECTION_PAGE_URL'];
                    }
                    ?>
                    <div class="aitem amenu__item <?= $item['SELECTED']
                        ? "amenu__selected" : "" ?>">

                        <a class="aitem__link <?= $item['LOGOUT']=="Y" ? "aitem__exit" : "" ?>" href="<?= $item['LINK'] ?>">
                            <?if($item['LOGOUT']=="Y"):?>
                            <svg class="aitem__exit-svg">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/images/icons/sprite.svg#arr-s-l"></use>
                            </svg>
                                <span class="aitem__exit-txt"><?=$item['NAME']?></span>
                            <?else:?>

                                <? if(!empty($item['UF_ICON'])): ?>
                                     <img class="aitem__icon" src="<?=CFile::getPath($item['UF_ICON'])?>" alt="<?=$item['NAME']?>">
                                <? endif ?>

                                <?= $item['NAME'] ?>
                            <?endif;?>
                            <? if($isParent): ?>
                                <span class="aitem__link_dropmenu"></span>
                            <? endif ?>
                        </a>

                        <? if ( ! empty($item['CHILD'])):?>
                            <div class="asubmenu">
                                <? foreach ($item['CHILD'] as $itemChild):?>
                                    <?
                                    if ( ! empty($itemChild['SECTION_PAGE_URL'])) {
                                        $itemChild['LINK']
                                            = $itemChild['SECTION_PAGE_URL'];
                                    }
                                    ?>
                                    <a class="asubmenu__item <?= $itemChild['SELECTED']
                                        ? "asubmenu__selected" : "" ?>"
                                       href="<?= $itemChild['LINK'] ?>"><?= $itemChild['NAME'] ?></a>
                                <?endforeach; ?>
                            </div>
                        <?endif; ?>
                    </div>
                <?endforeach; ?>
            </div>
        </div>
    </div>
<?php
endif; ?>

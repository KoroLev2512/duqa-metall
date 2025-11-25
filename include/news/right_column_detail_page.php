<?
use Webcomp\Market\Tools;

$news = $APPLICATION->IncludeComponent(
    "webcomp:element.getList",
    ".default",
    [
        "CACHE_FILTER"          => "N",
        "CACHE_TIME"            => "0",
        "CACHE_TYPE"            => "A",
        "COMPONENT_TEMPLATE"    => "",
        "ELEMENTS_COUNT"        => "100",
        "FIELD_CODE"            => [
            0 => "ID",
            1 => "ACTIVE_FROM",
            2 => "NAME",
            3 => "PREVIEW_PICTURE",
            4 => "PREVIEW_TEXT",
            5 => "CODE",
        ],
        "FILTER_NAME"           => "",
        "IBLOCK_ID"             => $GLOBALS['WEBCOMP']['IBLOCKS']['content']['webcomp_market_content_news'],
        "IBLOCK_TYPE"           => "catalog",
        "LINK_LINK"             => "/",
        "LINK_TITLE"            => "Все новости",
        "PAGINATION"            => "Y",
        "PROPERTY_CODE"         => [
            0 => "",
            1 => "",
        ],
        "SHOW_ONLY_ACTIVE"      => "Y",
        "SORT_BY1"              => "ACTIVE_FROM",
        "SORT_BY2"              => "SORT",
        "SORT_ORDER1"           => "DESC",
        "SORT_ORDER2"           => "ASC",
        "TITLE"                 => "Последние новости",
        "DONT_INCLUDE_TEMPLATE" => "Y",
        "USE_FILTER"            => "N",
    ],
    false
)["ITEMS"]; ?>

<? if(!empty($news)): ?>
<aside class="new__right">
    <div class="new__list">
        <? shuffle($news)?>
        <? foreach($news as $key => $item): ?>
            <? if($key > 2) continue;?>

            <div class="new__item">
                <a class="nitem" href="<?=$item["DETAIL_PAGE_URL"]?>">
                <span class="nitem__top">
                    <span class="nitem__img">
                        <img class="nitem__img-img" src="<?=$item["PREVIEW_PICTURE_VALUE"]["SRC"]?>" alt="<?=$item["NAME"]?>">
                    </span>
                </span>

                    <span class="nitem__bottom">
                    <span class="nitem__date"><?=$item["ACTIVE_FROM"]->format("d-m-Y")?></span>
                    <span class="nitem__title"><?=$item["NAME"]?></span>
                    <span class="nitem__txt"><?= Tools::cutString($item['PREVIEW_TEXT']) ?></span>
                    <span class="nitem__link link">
                        <span class="link__txt nitem__link-txt">Подробнее</span>
                              <svg class="link__svg nitem__link-svg">
                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#arrow-s"></use>
                              </svg>
                    </span>
                </span>
                </a>
            </div>
        <? endforeach ?>
    </div>
</aside>

<? endif ?>

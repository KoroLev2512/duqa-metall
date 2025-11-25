<? if(!empty($arParams["ITEMS"])): ?>
<div class="header__phone phone">
    <a class="phone__phone" href="tel:<?= current($arParams["ITEMS"])['~VALUE'] ?>">
        <svg class="phone__phone-svg">
            <use xlink:href="/images/icons/sprite.svg#tel"></use>
        </svg>
        <span class="phone__link_header <?=($arParams["SHOW_DROP_BLOCK"]) ? "phone__drop-arrow" : ""?>"><?= current($arParams["ITEMS"])['VALUE'] ?></span>
    </a>
    <? if ($arParams["SHOW_DROP_BLOCK"]) : ?>
        <div class="phone__drop">
            <? foreach ($arParams["ITEMS"] as $k => $phone) :?>

                <? if ($k != 0): ?>
                    <a class="phone__drop-item" href="tel:<?=$phone['~VALUE'] ?>">
                        <span class="phone__drop-link"><?= $phone['VALUE'] ?></span>
                        <span class="phone__drop-title"><?= $phone['DESCRIPTION'] ?></span>
                    </a>
                <? endif ?>

           <? endforeach ?>
        </div>
    <? endif ?>
</div>
<? endif ?>

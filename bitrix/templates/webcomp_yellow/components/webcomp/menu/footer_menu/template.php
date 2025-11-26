<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

?>
<?php
//\Bitrix\Main\Diag\Debug::dump($arResult['ITEMS']);
?>
<? if ( ! empty($arResult['ITEMS'])):?>
 
    <div class="footer__list">
        <? foreach ($arResult['ITEMS'] as $key => $item):
            
            $classItem = ($key) ? "footer__item" : "footer__title";

            $isParent = false;
            if ( ! empty($item['CHILD'])) {
                $isParent = true;
            }
            if ( ! empty($item['SECTION_PAGE_URL'])) {
                $item['LINK'] = $item['SECTION_PAGE_URL'];
            }
            ?>

            <a class="<?=$classItem?> <?= $item['SELECTED']
                ? "footer__item_selected" : "" ?>" href="<?= $item['LINK'] ?>"><?= $item['NAME'] ?></a>

        <?endforeach; ?>
    </div>
        
<? endif ?>
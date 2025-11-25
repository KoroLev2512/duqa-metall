<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$socialButtons = array_chunk($arResult['ITEMS'], 4);
if ( ! empty($socialButtons)):
    ?>
    <div class="socials">
        <div class="socials__title"><?= GetMessage("SOCIAL_TITLE") ?></div>
        <div class="socials__list">
            <? foreach ($socialButtons as $buttonsArr):?>
                <div class="socials__row">
                    <? foreach ($buttonsArr as $item) : ?>
                        <a class="socials__item" target="_blank"
                           href="<?= $item["UF_LINK"]['VALUE'] ?>">
                            <img class="socials__item-svg"
                                 src="<?= current($item["UF_ICON"]['VALUE'])['SRC'] ?>"
                                 alt="<?= $item["UF_NAME"]['VALUE'] ?>">
                        </a>
                    <?endforeach; ?>
                </div>
            <?endforeach; ?>
        </div>
    </div>
<?php
endif;
?>


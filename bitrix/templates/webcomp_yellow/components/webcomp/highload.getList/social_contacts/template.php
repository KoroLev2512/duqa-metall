<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$socialButtons = array_chunk($arResult['ITEMS'], 6);
if ( ! empty($socialButtons)):
    ?>
    <div class="contacts__socials">
        <div class="contact__top contacts__socials-top">
            <i class="svg__icon">
                <svg class="contacts__socials-svg" width="19" height="19" viewBox="0 0 19 19" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.6408 11.7117C14.6606 10.7315 13.4938 10.0058 12.2208 9.56653C13.5842 8.62748 14.48 7.05589 14.48 5.27881C14.48 2.40981 12.1459 0.0756836 9.27686 0.0756836C6.40786 0.0756836 4.07373 2.40981 4.07373 5.27881C4.07373 7.05589 4.96948 8.62748 6.33294 9.56653C5.05993 10.0058 3.89317 10.7315 2.91291 11.7117C1.21303 13.4116 0.276855 15.6717 0.276855 18.0757H1.68311C1.68311 13.8885 5.08964 10.4819 9.27686 10.4819C13.4641 10.4819 16.8706 13.8885 16.8706 18.0757H18.2769C18.2769 15.6717 17.3407 13.4116 15.6408 11.7117ZM9.27686 9.07568C7.18327 9.07568 5.47998 7.37243 5.47998 5.27881C5.47998 3.18518 7.18327 1.48193 9.27686 1.48193C11.3704 1.48193 13.0737 3.18518 13.0737 5.27881C13.0737 7.37243 11.3704 9.07568 9.27686 9.07568Z"></path>
                </svg>
            </i>                            <div class="contact__name contacts__socials-name"><?= GetMessage("SOCIAL_TITLE") ?></div>
        </div>

        <div class="socials__list">
            <? foreach ($socialButtons as $buttonsArr):?>
                <div class="socials__row">
                    <? foreach ($buttonsArr as $item) : ?>
                        <a class="socials__item" target="_blank"
                           href="<?= $item["UF_LINK"]['VALUE'] ?>">
                            <img class=""
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


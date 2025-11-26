<? global $isMainPage ?>

<? if ($isMainPage): ?>
    <? if (!empty($arParams["LIGHT"])): ?>
        <span class="header__logo-img header__logo-img--light logo">
        <img src="<?= $arParams["LIGHT"]["SRC"] ?>"
             alt="<?= $arParams["LIGHT"]["SITE_NAME"] ?>"
             title="<?= $arParams["LIGHT"]["SITE_NAME"] ?>">
    </span>
    <? endif; ?>
    <? if (!empty($arParams["DARK"])): ?>
        <span class="header__logo-img header__logo-img--dark logo">
        <img src="<?= $arParams["DARK"]["SRC"] ?>"
             alt="<?= $arParams["DARK"]["SITE_NAME"] ?>"
             title="<?= $arParams["DARK"]["SITE_NAME"] ?>">
    </span>
    <? endif; ?>
<? else: ?>
    <a class="header__logo-img logo" href="/">
        <img src="<?= $arParams["DARK"]["SRC"] ?>"
             alt="<?= $arParams["DARK"]["SITE_NAME"] ?>"
             title="<?= $arParams["DARK"]["SITE_NAME"] ?>">
    </a>
<? endif ?>

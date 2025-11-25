<? if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<? if ($arResult["FORM_TYPE"] == "login"): ?>
    <?php
    $APPLICATION->AddViewContent('custom_css', '.catalog__left.left{display:none}'); // скрываем левое меню для страницы login
    ?>
    <?php
    $APPLICATION->SetTitle("Авторизация");
    ?>
    <form class="data__fields" name="system_auth_form<?= $arResult["RND"] ?>"
          method="post" target="_top" action="<?= $arResult["AUTH_URL"] ?>">
        <? if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR']) : ?>
            <div class="data__field">
                <div class="auth_errors">
                    <?= $arResult['ERROR_MESSAGE']['MESSAGE'] ?>
                </div>
            </div>
        <? endif; ?>
        <div class="data__field">
            <div class="data__label">Email<span class="data__label_r">*</span>
            </div>
            <div class="data__row">
                <div class="data__left">
                    <input class="data__input input" type="email_T"
                           name="USER_LOGIN" placeholder="Email"
                           value="<?= @$_POST['USER_LOGIN'] ?>">
                </div>
            </div>
        </div>
        <div class="data__field">
            <div class="data__label">Пароль<span class="data__label_r">*</span>
            </div>
            <div class="data__row">
                <div class="data__left">
                    <input class="data__input input" type="password"
                           name="USER_PASSWORD" placeholder="Пароль"
                           value="">
                </div>
            </div>
        </div>
        <div class="data__field">
            <div class="data__row">

                <div class="data__left">
                    <input type="checkbox" id="USER_REMEMBER_frm"
                           name="USER_REMEMBER" value="Y"/>
                    <label for="USER_REMEMBER_frm" title="Запомнить меня">Запомнить
                        меня</label>
                </div>
            </div>
            <div class="data__row">
                <div class="data__left data__left_btn">
                    <input class="data__btn btn" type="submit" name="Login"
                           value="Войти"/>
                    <a style="display: none;" class="data__btn btn"
                       href="<?= $arResult["AUTH_REGISTER_URL"] ?>"
                       rel="nofollow">Регистрация</a>
                </div>
            </div>
            <div class="data__row">
                <div class="data__left">
                    <a href="<?= $arResult["AUTH_FORGOT_PASSWORD_URL"] ?>"
                       rel="nofollow">Забыли пароль?</a>
                </div>
            </div>
            </div>
        </div>
        <? if ($arResult["BACKURL"] <> ''): ?>
            <input type="hidden" name="backurl"
                   value="<?= $arResult["BACKURL"] ?>"/>
        <? endif ?>
        <? foreach ($arResult["POST"] as $key => $value): ?>
            <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
        <? endforeach ?>
        <input type="hidden" name="AUTH_FORM" value="Y"/>
        <input type="hidden" name="TYPE" value="AUTH"/>
    </form>
<? else: ?>
    <? $APPLICATION->IncludeComponent(
        "webcomp:menu",
        "personal_page",
        [
            "CACHE_TIME"         => "36000000",
            "CACHE_TYPE"         => "N",
            "MAX_DEPTH"          => "1",
            "TYPE_MENU"          => "left",
            "USE_CATALOG"        => "N",
            "COMPONENT_TEMPLATE" => "left_menu",
            "START_DIRECTORY"    => "THIS_DIR",
        ],
        false
    ); ?>
    <?php
    /*$arResult*/
    ?>
<? endif ?>

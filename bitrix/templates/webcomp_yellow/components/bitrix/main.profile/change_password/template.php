<?
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
use \Bitrix\Main\Localization\Loc;
?>
<?ShowError($arResult["strProfileError"]);?>
<?
if ($arResult['DATA_SAVED'] == 'Y')
	ShowNote(Loc::getMessage('PROFILE_DATA_SAVED'));
?>

<?php
$personalFields = [
    "NEW_PASSWORD"         => [
        'LANG_MESSAGE_VARIABLE' => 'NEW_PASSWORD',
        'PLACEHOLDER'           => '',
        'LABEL'                 => 'Введите новый пароль',
        'REQUIRED'              => true,
    ],
    "NEW_PASSWORD_CONFIRM" => [
        'LANG_MESSAGE_VARIABLE' => 'NEW_PASSWORD_CONFIRM',
        'PLACEHOLDER'           => '',
        'LABEL'                 => 'Повторите ввод пароля',
        'REQUIRED'              => true,
    ],

]
?>

<form method="post" name="form1" class="data__fields" action="<?=$arResult["FORM_TARGET"]?>"
      enctype="multipart/form-data">
    <div class="data__field back_btn_mobile">
        <a class="data__btn btn" href="/personal/">Назад</a>
    </div>
<?=$arResult["BX_SESSION_CHECK"]?>
<input type="hidden" name="lang" value="<?=LANG?>" />
<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
<? foreach ($personalFields as $key=>$personalField) : ?>
    <div class="data__field">
        <div
            class="data__label"><?= Loc::getMessage($personalField['LANG_MESSAGE_VARIABLE']) ?><? if ($personalField['REQUIRED']): ?>
                <span class="data__label_r">*</span><? endif; ?></div>
        <div class="data__row">
            <div class="data__left">
                <input class="data__input input" <?= $personalField['REQUIRED']
                    ? "required" : "" ?> type="password" name="<?= $key ?>"
                       value="<?= $arResult["arUser"][$key] ?>"
                       placeholder="<?= $personalField['PLACEHOLDER'] ?>">
            </div>
            <div class="data__right">
                <div class="data__txt"><?= $personalField['LABEL'] ?></div>
            </div>
        </div>
    </div>
<?endforeach;?>
    <div class="data__field">
        <input class="data__btn btn" type="submit" name="save" value="<?=Loc::getMessage("MAIN_SAVE")?>">
    </div>
</form>
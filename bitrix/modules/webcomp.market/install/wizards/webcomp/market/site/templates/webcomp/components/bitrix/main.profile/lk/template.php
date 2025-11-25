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
    "NAME"=>[
        'LANG_MESSAGE_VARIABLE'=>'NAME',
        'PLACEHOLDER'=>'Иван Петров',
        'LABEL'=>'Заполните чтобы мы знали как к вам обращаться',
        'REQUIRED'=>true
    ],
    "EMAIL"=>[
        'LANG_MESSAGE_VARIABLE'=>'EMAIL',
        'PLACEHOLDER'=>'mail@domain.ru',
        'LABEL'=>'Для отправки уведомлений о статусе заказа. Используйте как логин для входа в личный кабинет',
        'REQUIRED'=>true
    ],
    "PERSONAL_PHONE"=>[
        'LANG_MESSAGE_VARIABLE'=>'USER_PHONE',
        'PLACEHOLDER'=>'+7 (999) 777-77-77',
        'LABEL'=>'Необходим для уточнения деталей заказа',
        'REQUIRED'=>false
    ],

]
?>

<form method="post" name="form1" class="data__fields" action="<?=$arResult["FORM_TARGET"]?>"
      enctype="multipart/form-data">
    <div class="data__field back_btn_mobile">
        <a class="data__btn btn" href="#WIZARD_SITE_DIR#personal/">Назад</a>
    </div>
<?=$arResult["BX_SESSION_CHECK"]?>
<input type="hidden" name="lang" value="<?=LANG?>" />
<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
<? foreach ($personalFields as $key=>$personalField) :?>
    <div class="data__field">
        <div class="data__label"><?=Loc::getMessage($personalField['LANG_MESSAGE_VARIABLE'])?><?if($personalField['REQUIRED']):?><span class="data__label_r">*</span><?endif;?></div>
        <div class="data__row">
            <div class="data__left">
                <input class="data__input input" <?=$personalField['REQUIRED'] ? "required" : ""?> type="text" name="<?=$key?>" value="<?=$arResult["arUser"][$key]?>" placeholder="<?=$personalField['PLACEHOLDER']?>">
            </div>
            <div class="data__right">
                <div class="data__txt"><?=$personalField['LABEL']?></div>
            </div>
        </div>
    </div>
<?endforeach;?>
    <div class="data__field">
        <input class="data__btn btn" type="submit" name="save" value="<?=Loc::getMessage("MAIN_SAVE")?>">
    </div>
</form>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
session_start();
header('Content-Type: application/json');

use Webcomp\Market\Settings;

if(!isset($GLOBALS["WEBCOMP"]["SETTINGS"])) {
    $GLOBALS["WEBCOMP"]["SETTINGS"] = Settings::GetGlobalSettings();
}

$data = $_POST;
$event = $data['EVENT'];

// if sending file
if(isset($_FILES) && !empty($_FILES)) {
    $data = array_merge($data, $_FILES);
}

switch ($event) {
    case 'showForm':
        $arResult = showForm($data);
        echo json_encode(array(
            'status' => $arResult["STATUS"],
            'html' => $arResult["HTML"],
        ));
        exit();
        break;
    case 'sendForm':
        $arResult = sendForm($data);
        echo json_encode(array(
            'status' => $arResult["STATUS"],
            'html' => $arResult["HTML"],
        ));
        exit();
        break;
}

function showForm($data) {
    # $data["EVENT"] => event name (required)
    # $data["IBLOCK_ID"] => iblock id for render fields form (required)
    # $data["ELEMENTS"]  => elements ids in bind properties
    # $data["FORM_NAME"] => form name
    # $data["EMAIL_EVENT_ID"] => email template id

    if(empty($data["IBLOCK_ID"]))
        return ["STATUS" => false];

    ob_start();
    ?>

    <?
    global $APPLICATION;
    $APPLICATION->IncludeComponent(
        "webcomp:form",
        "popup",
        [
            "CACHE_FILTER" => "N",
            "CACHE_TIME" => "0",
            "CACHE_TYPE" => "A",
            "ELEMENTS_COUNT" => "20",
            "FIELD_CODE" => "",
            "FILTER_NAME" => "",
            "IBLOCK_ID" => (int) $data["IBLOCK_ID"],
            "IBLOCK_TYPE" => "forms",
            "PROPERTY_CODE" => "",
            "SHOW_ONLY_ACTIVE" => "Y",
            "SORT_BY1" => "SORT",
            "SORT_BY2" => "NAME",
            "SORT_ORDER1" => "ASC",
            "SORT_ORDER2" => "ASC",
            "COMPONENT_TEMPLATE" => "popup",
            "EMAIL_EVENT_ID" => $data["EMAIL_EVENT_ID"],
            "BIND_ELEMENTS" => $data["ELEMENTS"],
            "FORM_NAME" => $data["FORM_NAME"]
        ],
        false
    );
    ?>

    <?
    $html = ob_get_contents();
    ob_end_clean();

    return [
        "STATUS" => true,
        "HTML" => $html
    ];
}

function sendForm($data) {

    ob_start();
    ?>

    <? if($data["FORM_NAME"] === "CALLORDER_FOOTER"): ?>
         <div class="ffeed__success_txt">
             Спасибо! Ваша заявка принята в работу.
            <br>
            Ожидайте звонка.
        </div>
    <? elseif($data["FORM_NAME"] === "FEEDBACK"):?>
        <div class="ccall__success_txt">
            Спасибо! Ваша заявка принята в работу.
            <br>
            Ожидайте звонка.
        </div>
    <? elseif($data["FORM_NAME"] === "REVIEWS"):?>
        <div class="popup__top">
            <button class="popup__close jsFormClose" type="button">
                <svg class="popup__close-svg">
                    <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#close"></use>
                </svg>
            </button>
        </div>
        <div class="popup__success">
            <div class="psuccess">
                <div class="psuccess__img">
                    <svg class="psuccess__svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#chech-round"></use>
                    </svg>
                </div>
                <div class="psuccess__title">
                    Спасибо!
                </div>
                <div class="psuccess__txt">
                    Ваш отзыв принят.
                </div>
                <button type="button" class="psuccess__close jsFormClose btn">
                    Закрыть
                </button>
            </div>
        </div>
    <? else: ?>
        <div class="popup__top">
            <button class="popup__close jsFormClose" type="button">
                <svg class="popup__close-svg">
                    <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#close"></use>
                </svg>
            </button>
        </div>
        <div class="popup__success">
            <div class="psuccess">
                <div class="psuccess__img">
                    <svg class="psuccess__svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#chech-round"></use>
                    </svg>
                </div>
                <div class="psuccess__title">
                    Спасибо!
                </div>
                <div class="psuccess__txt">
                    Ваша заявка принята в работу.
                    <br>
                    Ожидайте звонка.
                </div>
                <button type="button" class="psuccess__close jsFormClose btn">
                    Закрыть
                </button>
            </div>
        </div>
    <? endif ?>

    <?
    $html = ob_get_contents();
    ob_end_clean();

    return [
        "STATUS" => CMarketForm::Send($data),
        "HTML" => $html
    ];
}
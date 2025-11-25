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

switch ($event) {
    case 'clearCart':
        $arResult = clearCart($data);
        echo json_encode(array(
            'status' => $arResult["STATUS"],
            'html' => $arResult["HTML"],
            'data' => $arResult["DATA"],
        ));
        exit();
        break;
    case 'showForm':
        $arResult = showForm($data);
        echo json_encode(array(
            'status' => $arResult["STATUS"],
            'html' => $arResult["HTML"],
            'data' => $arResult["DATA"],
        ));
        exit();
    case 'sendOrderForm':
        $arResult = sendOrderForm($data);
        echo json_encode(array(
            'status' => $arResult["STATUS"],
            'html' => $arResult["HTML"],
            'order' => $arResult["ORDER"]
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
        "fast_order",
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

function clearCart($data) {
    # $data["EVENT"] => event name (required)

    // delete all products in cart
    unset($_SESSION["CART"]);

    return [
        "STATUS" => true,
        "HTML" => 0,
        "DATA" => [],
    ];
}

function sendOrderForm($data) {

    $orderID = CMarketForm::Send($data);
    unset($_SESSION["CART"]);

    if($orderID) {
        $_SESSION["ORDER"] = [
            "STATUS" => true,
            "ORDER_ID" => $orderID,
        ];

        return [
            "STATUS" => true,
            "HTML"   => "#WIZARD_SITE_DIR#cart/ok/",
            "ORDER"  => $orderID,
        ];
    } else {
        return [
            "STATUS" => false,
            "HTML" => 0
        ];
    }

}



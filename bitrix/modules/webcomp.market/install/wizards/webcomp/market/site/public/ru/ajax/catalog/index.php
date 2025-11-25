<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Webcomp\Market\Settings;

session_start();
header('Content-Type: application/json');

if(!isset($GLOBALS["WEBCOMP"]["SETTINGS"])) {
    $GLOBALS["WEBCOMP"]["SETTINGS"] = Settings::GetGlobalSettings();
}

$data = $_POST;
$event = $data['EVENT'];

switch ($event) {
    case 'changeFavoriteList':
        $arResult = changeFavoriteList($data);
        echo json_encode(array(
            'status' => $arResult["STATUS"],
            'html' => $arResult["HTML"],
            'data' => $arResult["DATA"]
        ));
        break;
    case 'clearFavoriteList':
        $arResult = clearFavoriteList($data);
        echo json_encode(array(
            'status' => $arResult["STATUS"],
            'html' => $arResult["HTML"],
            'data' => $arResult["DATA"]
        ));
        break;
    case 'changeCompareList':
        $arResult = changeCompareList($data);
        echo json_encode(array(
            'status' => $arResult["STATUS"],
            'html' => $arResult["HTML"],
            'data' => $arResult["DATA"]
        ));
        break;
    case 'clearCompareList':
        $arResult = clearCompareList($data);
        echo json_encode(array(
            'status' => $arResult["STATUS"],
            'html' => $arResult["HTML"],
            'data' => $arResult["DATA"]
        ));
        break;
    case 'changeQuantityInCart':
        $arResult = changeQuantityInCart($data);
        echo json_encode(array(
            'status' => $arResult["STATUS"],
            'html' => $arResult["HTML"],
            'price' => $arResult["PRICE"],
        ));
        break;
    case 'deleteProductInCart':
        $arResult = deleteProductInCart($data);
        echo json_encode(array(
            'status' => $arResult["STATUS"],
            'html' => $arResult["HTML"],
            'data' => $arResult["DATA"],
        ));
        break;
    case 'addToCart':
        $arResult = addToCart($data);
        echo json_encode(array(
            'status' => $arResult["STATUS"],
            'html' => $arResult["HTML"],
            'data' => $arResult["DATA"],
        ));
        break;
}

exit();

function changeFavoriteList($data) {
    # $data["EVENT"] => event name (required)
    # $data["ID"] => element id

    if(empty($data["ID"])) return false;

    // add or delete item in favoriteList
    if(isset($_SESSION["FAVORITE"][$data["ID"]])) {
        unset($_SESSION["FAVORITE"][$data["ID"]]);  // delete
    } else {
        $_SESSION["FAVORITE"][$data["ID"]] = 1; // add
    }

    return [
        "STATUS" => true,
        "HTML" => count($_SESSION["FAVORITE"]),
        "DATA" => $_SESSION["FAVORITE"]
    ];
}

function clearFavoriteList($data) {
    # $data["EVENT"] => event name (required)
    unset($_SESSION["FAVORITE"]);

    return [
        "STATUS" => true,
        "HTML" => 0,
        "DATA" => $_SESSION["FAVORITE"]
    ];
}

function changeCompareList($data) {
    # $data["EVENT"] => event name (required)
    # $data["ID"] => element id

    if(empty($data["ID"])) return false;

    // add or delete item in compareList
    if(isset($_SESSION["COMPARE"][$data["ID"]])) {
        unset($_SESSION["COMPARE"][$data["ID"]]);  // delete
    } else {
        $_SESSION["COMPARE"][$data["ID"]] = 1; // add
    }

    return [
        "STATUS" => true,
        "HTML" => count($_SESSION["COMPARE"]),
        "DATA" => $_SESSION["COMPARE"]
    ];
}

function clearCompareList($data) {
    # $data["EVENT"] => event name (required)
    unset($_SESSION["COMPARE"]);

    return [
        "STATUS" => true,
        "HTML" => 0,
        "DATA" => $_SESSION["COMPARE"]
    ];
}

function changeQuantityInCart($data) {
    # $data["EVENT"] => event name (required)
    # $data["ID"] => element id
    # $data["COUNT"] => element count
    # $data["PRICE"] => element price

    if(empty($data["ID"])) return false;

    $_SESSION["CART"][$data["ID"]] = $data["COUNT"];
    $cnt = 0;

    foreach($_SESSION["CART"] as $item) {
       $cnt += $item;
    }

    return [
        "STATUS" => true,
        "HTML" => $cnt,
        "PRICE" => $data["COUNT"] * $data["PRICE"]
    ];

}

function deleteProductInCart($data) {
    # $data["EVENT"] => event name (required)
    # $data["ID"] => element id

    if(empty($data["ID"])) return false;

    unset($_SESSION["CART"][$data["ID"]]);
    $cnt = 0;

    foreach($_SESSION["CART"] as $item) {
        $cnt += $item;
    }

    return [
        "STATUS" => true,
        "HTML" => $cnt,
        "DATA" => $_SESSION["CART"]
    ];

}

function addToCart($data) {
    # $data["EVENT"] => event name (required)
    # $data["ID"] => element id
    # $data["QUANTITY"] => element quantity

    if(isset($_SESSION["CART"][$data["ID"]]) ) {
        $_SESSION["CART"][$data["ID"]] = $_SESSION["CART"][$data["ID"]] + $data["QUANTITY"];
    } else {
        $_SESSION["CART"][$data["ID"]] = $data["QUANTITY"];
    }

    $cnt = 0;
    foreach($_SESSION["CART"] as $item) {
        $cnt += $item;
    }

    return [
        "STATUS" => true,
        "HTML" => $cnt,
        "DATA" => $_SESSION["CART"]
    ];
}
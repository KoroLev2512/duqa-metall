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

function cart_count_sum(): float {
    $cnt = 0.0;
    if (!empty($_SESSION["CART"]) && is_array($_SESSION["CART"])) {
        foreach ($_SESSION["CART"] as $qty) $cnt += (float)$qty;
    }
    return $cnt;
}

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
    if (empty($data["ID"])) return false;
    $id    = (int)$data["ID"];
    $qty   = (float)($data["COUNT"]  ?? 1);
    $price = (float)($data["PRICE"]  ?? ($_SESSION["CART_DETAILS"][$id]['PRICE'] ?? 0));
    $name  = (string)($data["NAME"]  ?? ($_SESSION["CART_DETAILS"][$id]['NAME']  ?? ''));

    if (!isset($_SESSION["CART"][$id]) && !isset($_SESSION["CART_DETAILS"][$id])) return false;

    // legacy map
    if (!isset($_SESSION["CART"])) $_SESSION["CART"] = [];
    $_SESSION["CART"][$id] = $qty;

    // details map
    if (!isset($_SESSION["CART_DETAILS"])) $_SESSION["CART_DETAILS"] = [];
    if (!isset($_SESSION["CART_DETAILS"][$id])) {
        $_SESSION["CART_DETAILS"][$id] = ['ID'=>$id,'NAME'=>$name,'PRICE'=>$price,'QUANTITY'=>0.0];
    }
    $_SESSION["CART_DETAILS"][$id]['QUANTITY'] = $qty;
    if ($name  !== '') $_SESSION["CART_DETAILS"][$id]['NAME']  = $name;
    $_SESSION["CART_DETAILS"][$id]['PRICE'] = $price;

    return [
        "STATUS" => true,
        "HTML"   => cart_count_sum(),
        "PRICE"  => $qty * $price,
    ];
}

function deleteProductInCart($data) {
    if (empty($data["ID"])) return false;
    $id = (int)$data["ID"];

    if (isset($_SESSION["CART"][$id])) unset($_SESSION["CART"][$id]);
    if (isset($_SESSION["CART_DETAILS"][$id])) unset($_SESSION["CART_DETAILS"][$id]);

    return [
        "STATUS" => true,
        "HTML"   => cart_count_sum(),
        "DATA"   => $_SESSION["CART"],  // как и раньше
    ];
}

function addToCart($data) {
    if (empty($data["ID"])) return false;
    $id    = (int)$data["ID"];
    $qty   = (float)($data["QUANTITY"] ?? 1);
    $name  = (string)($data["NAME"]  ?? '');
    $price = (float) ($data["PRICE"] ?? 0);

    // 1) legacy: ID => qty
    if (!isset($_SESSION["CART"]) || !is_array($_SESSION["CART"])) $_SESSION["CART"] = [];
    $_SESSION["CART"][$id] = (float)($_SESSION["CART"][$id] ?? 0) + $qty;

    // 2) подробности: ID => struct
    if (!isset($_SESSION["CART_DETAILS"]) || !is_array($_SESSION["CART_DETAILS"])) $_SESSION["CART_DETAILS"] = [];
    if (!isset($_SESSION["CART_DETAILS"][$id])) {
        $_SESSION["CART_DETAILS"][$id] = ['ID'=>$id,'NAME'=>$name,'PRICE'=>$price,'QUANTITY'=>0.0];
    }
    if ($name  !== '') $_SESSION["CART_DETAILS"][$id]['NAME']  = $name;
    if ($price >= 0)   $_SESSION["CART_DETAILS"][$id]['PRICE'] = $price;
    $_SESSION["CART_DETAILS"][$id]['QUANTITY'] += $qty;

    return [
        "STATUS" => true,
        "HTML"   => cart_count_sum(),          // как и раньше: общее количество
        "DATA"   => $_SESSION["CART"],         // оставляем интерфейс прежним
    ];
}

/**
 * Преобразует нормализованную корзину -> product rows Bitrix24.
 * Если в Б24 нет каталога с этими ID, используем "свободные позиции" (PRODUCT_NAME).
 */
function build_b24_rows(array $cart): array {
    $rows = [];
    foreach ($cart as $item) {
        $name  = (string)($item['NAME'] ?? $item['TITLE'] ?? 'Позиция');
        $qty   = (float) ($item['QUANTITY'] ?? $item['QTY'] ?? 1);
        $price = (float) ($item['PRICE'] ?? $item['AMOUNT'] ?? 0);

        // Без жесткой привязки к каталогу Б24 (надежнее, т.к. ID сайта ≠ ID каталога Б24)
        $rows[] = [
            'PRODUCT_NAME' => $name,
            'PRICE'        => $price,
            'QUANTITY'     => $qty,
            'CURRENCY_ID'  => 'RUB',
        ];

        // Если у вас настроена синхронизация и ID сайта == PRODUCT_ID в каталоге Б24,
        // можно вместо PRODUCT_NAME передавать PRODUCT_ID:
        // 'PRODUCT_ID' => (int)($item['ID'] ?? 0),
    }
    return $rows;
}

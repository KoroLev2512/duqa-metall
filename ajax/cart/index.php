<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
session_start();
header('Content-Type: application/json');

use Webcomp\Market\Settings;

// === Константы: вебхук и логи ===
const B24_BASE_WEBHOOK = 'https://prombooks.bitrix24.ru/rest/379/pnfw7pma014pup3w/';
const LOG_LEAD = '/upload/bitrix24_crm_lead_log.log';
const LOG_DBG  = '/upload/debug.log';

if (!isset($GLOBALS["WEBCOMP"]["SETTINGS"])) {
    $GLOBALS["WEBCOMP"]["SETTINGS"] = Settings::GetGlobalSettings();
}

$data  = $_POST;
$event = $data['EVENT'] ?? '';

// Поддержка JSON-тел: если пришёл application/json, $_POST будет пустым
if (empty($_POST)) {
    $raw = file_get_contents('php://input');
    if ($raw) {
        $json = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
            $_POST  = $json;
            $data   = $json;
            $event  = $data['EVENT'] ?? $event;
        }
    }
}

// --- Хелперы (инлайн) ---
function log_append(string $file, string $line): void {
    @file_put_contents($_SERVER['DOCUMENT_ROOT'].$file, date('c').' | '.$line.PHP_EOL, FILE_APPEND);
}

function getVal(array $keys, array $src): string {
    foreach ($keys as $k) {
        if (isset($src[$k]) && trim((string)$src[$k]) !== '') return trim((string)$src[$k]);
    }
    return '';
}

function normalize_phone(?string $raw): string {
    if ($raw === null) return '';
    $s = preg_replace('/\s+/', '', $raw);
    return preg_replace('/(?!^\+)\D+/', '', $s ?? '') ?? '';
}

function build_comments(array $data, string $messageKey = 'COMMENT'): string {
    $message = getVal([$messageKey, 'MESSAGE', 'TEXT', 'COMMENTS'], $data);
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $uri     = $_SERVER['REQUEST_URI'] ?? '';
    $ip      = $_SERVER['REMOTE_ADDR'] ?? '';
    $lines   = [];
    if ($message !== '') $lines[] = 'Сообщение: '.$message;
    if ($referer !== '') $lines[] = 'Страница: '.$referer;
    if ($uri     !== '') $lines[] = 'URI: '.$uri;
    if ($ip      !== '') $lines[] = 'IP: '.$ip;
    foreach (['utm_source','utm_medium','utm_campaign','utm_content','utm_term'] as $utm) {
        $val = $_COOKIE[$utm] ?? $_GET[$utm] ?? '';
        if ($val !== '') $lines[] = strtoupper($utm).': '.$val;
    }
    return implode("\n", $lines);
}

function b24_call(string $method, array $payload): array {
    $url = rtrim(B24_BASE_WEBHOOK, '/').'/'.ltrim($method, '/').'.json';
    $qs  = http_build_query($payload, '', '&');
    $ch  = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $qs,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT        => 12,
        CURLOPT_HEADER         => false,
    ]);
    $response = curl_exec($ch);
    $err      = curl_error($ch);
    $info     = curl_getinfo($ch);
    curl_close($ch);
    $decoded = @json_decode((string)$response, true);

    log_append(LOG_LEAD,
        '[b24_call] method='.$method
        .' | http_code='.($info['http_code'] ?? '')
        .' | total_time='.($info['total_time'] ?? '')
        .' | url='.$url
        .' | payload_len='.strlen($qs)
        .' | resp_len='.strlen((string)$response)
        .' | curl_err='.$err
    );

    return [
        'ok'         => is_array($decoded) && array_key_exists('result', $decoded),
        'response'   => $response,
        'json'       => $decoded,
        'err'        => $err,
        'url'        => $url,
        'payload_qs' => $qs,
        'info'       => $info,
    ];
}

// ==== НАДЁЖНОЕ получение имени и цены по ID товара (IBLOCK/CATALOG/SKU) ====
// ==== Получение NAME и PRICE: сначала каталог, затем свойства инфоблока ====
function fetch_product_info(int $id): array {
    // 0) кэш
    if (!empty($_SESSION['CART_NAMES'][$id]) && isset($_SESSION['CART_PRICES'][$id])) {
        return [
            'ID'    => $id,
            'NAME'  => (string)$_SESSION['CART_NAMES'][$id],
            'PRICE' => (float) $_SESSION['CART_PRICES'][$id],
        ];
    }

    $name  = 'Товар #'.$id;
    $price = 0.0;
    $iblockId = 0;

    // 1) Модули
    if (class_exists('\Bitrix\Main\Loader')) {
        \Bitrix\Main\Loader::includeModule('iblock');
        \Bitrix\Main\Loader::includeModule('catalog');
    } else {
        CModule::IncludeModule('iblock');
        CModule::IncludeModule('catalog');
    }

    // 2) NAME + IBLOCK_ID
    if (class_exists('\CIBlockElement')) {
        if ($res = \CIBlockElement::GetByID($id)) {
            if ($ob = $res->GetNext()) {
                $name     = html_entity_decode((string)$ob['NAME'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $iblockId = (int)$ob['IBLOCK_ID'];
            }
        }
    }

    // 3) Попытки взять цену из каталога (если вдруг есть)
    if (class_exists('\Bitrix\Catalog\GroupTable') && class_exists('\Bitrix\Catalog\PriceTable')) {
        try {
            $base = \Bitrix\Catalog\GroupTable::getBaseGroup();
            if (is_array($base) && isset($base['ID'])) {
                $row = \Bitrix\Catalog\PriceTable::getList([
                    'filter' => ['=PRODUCT_ID'=>$id, '=CATALOG_GROUP_ID'=>$base['ID']],
                    'select' => ['PRICE'],
                    'limit'  => 1,
                ])->fetch();
                if ($row && isset($row['PRICE'])) $price = (float)$row['PRICE'];
            }
        } catch (\Throwable $e) {}
    }
    if ($price <= 0 && class_exists('\Bitrix\Catalog\PriceTable')) {
        try {
            $any = \Bitrix\Catalog\PriceTable::getList([
                'filter' => ['=PRODUCT_ID'=>$id],
                'select' => ['PRICE'],
                'order'  => ['PRICE'=>'asc'],
                'limit'  => 1,
            ])->fetch();
            if ($any && isset($any['PRICE'])) $price = (float)$any['PRICE'];
        } catch (\Throwable $e) {}
    }
    if ($price <= 0 && class_exists('\CPrice')) {
        if ($ar = \CPrice::GetList(['PRICE'=>'ASC'], ['PRODUCT_ID'=>$id])->Fetch()) {
            $price = (float)$ar['PRICE'];
        }
    }

    // 4) Если в каталоге пусто — берём из СВОЙСТВ инфоблока
    if ($price <= 0 && $iblockId > 0 && class_exists('\CIBlockElement')) {
        // 4.1 Опытные коды свойств цены — перебор
        $codes = ['PRICE','TSENA','CENA','MINIMUM_PRICE','PRICE_RUB','PRICE_RUR'];
        foreach ($codes as $code) {
            $pr = \CIBlockElement::GetProperty($iblockId, $id, [], ['CODE'=>$code])->Fetch();
            if ($pr && isset($pr['VALUE']) && $pr['VALUE'] !== '') {
                $val = (string)$pr['VALUE'];
                $val = str_replace([' ', ','], ['', '.'], $val);
                $val = preg_replace('/[^\d.]/', '', $val);
                if ($val !== '') { $price = (float)$val; break; }
            }
        }
        // 4.2 Если код неизвестен — ищем по имени свойства, содержащее «цен»
        if ($price <= 0) {
            $props = \CIBlockElement::GetProperty($iblockId, $id, [], []);
            while ($p = $props->Fetch()) {
                $pname = mb_strtolower((string)($p['NAME'] ?? ''), 'UTF-8');
                if ($pname !== '' && mb_strpos($pname, 'цен', 0, 'UTF-8') !== false) {
                    $val = (string)($p['VALUE'] ?? '');
                    if ($val !== '') {
                        $val = str_replace([' ', ','], ['', '.'], $val);
                        $val = preg_replace('/[^\d.]/', '', $val);
                        if ($val !== '') { $price = (float)$val; break; }
                    }
                }
            }
        }
    }

    // 5) Кэш и диагностика
    $_SESSION['CART_NAMES'][$id]  = $name;
    $_SESSION['CART_PRICES'][$id] = $price;

    if ($price <= 0) {
        log_append(LOG_DBG, "fetch_product_info id=$id | name='$name' | price=$price | iblock=$iblockId (цена только в свойстве? проверь код свойства)");
    }

    return ['ID'=>$id, 'NAME'=>$name, 'PRICE'=>$price];
}

// Собрать позиции корзины из разных источников в единый формат
function collect_cart_items(array $data): array {
    $items = [];

    // 1) POST: CART/ITEMS (array или JSON)
    foreach (['CART','cart','ITEMS','items'] as $key) {
        if (!isset($data[$key])) continue;
        $src = is_string($data[$key]) ? json_decode($data[$key], true) : $data[$key];
        if (!is_array($src) || empty($src)) continue;

        foreach ($src as $row) {
            $id    = (int)   ($row['ID'] ?? $row['id'] ?? 0);
            $name  = (string)($row['NAME'] ?? $row['TITLE'] ?? $row['name'] ?? '');
            $qty   = (float) ($row['QUANTITY'] ?? $row['QTY'] ?? $row['COUNT'] ?? $row['count'] ?? 1);
            $price = (float) ($row['PRICE'] ?? $row['AMOUNT'] ?? $row['price'] ?? 0);
            if ($id <= 0) continue;

            // дополняем пустое
            if ($name === '' || $price <= 0) {
                $info = fetch_product_info($id);
                if ($name === '')  $name  = $info['NAME'];
                if ($price <= 0)   $price = $info['PRICE'];
            }
            $items[] = ['ID'=>$id,'NAME'=>$name,'PRICE'=>$price,'QUANTITY'=>$qty];
        }
        if (!empty($items)) return $items;
    }

    // 2) SESSION: CART_DETAILS
    if (!empty($_SESSION['CART_DETAILS']) && is_array($_SESSION['CART_DETAILS'])) {
        foreach ($_SESSION['CART_DETAILS'] as $id => $row) {
            $id    = (int)($row['ID'] ?? $id);
            $qty   = (float)($row['QUANTITY'] ?? $row['QTY'] ?? 1);
            $name  = (string)($row['NAME'] ?? '');
            $price = (float) ($row['PRICE'] ?? 0);

            if ($name === '' || $price <= 0) {
                $info = fetch_product_info($id);
                if ($name === '')  $name  = $info['NAME'];
                if ($price <= 0)   $price = $info['PRICE'];
            }
            $items[] = ['ID'=>$id,'NAME'=>$name,'PRICE'=>$price,'QUANTITY'=>$qty];
        }
        if (!empty($items)) return $items;
    }

    // 3) SESSION: legacy CART (ID=>qty)
    if (!empty($_SESSION['CART']) && is_array($_SESSION['CART'])) {
        foreach ($_SESSION['CART'] as $id => $qty) {
            $id    = (int)$id;
            $qty   = (float)$qty;
            $name  = (string)($_SESSION['CART_NAMES'][$id]  ?? '');
            $price = (float) ($_SESSION['CART_PRICES'][$id] ?? 0);

            if ($name === '' || $price <= 0) {
                $info = fetch_product_info($id);
                if ($name === '')  $name  = $info['NAME'];
                if ($price <= 0)   $price = $info['PRICE'];
            }
            $items[] = ['ID'=>$id,'NAME'=>$name,'PRICE'=>$price,'QUANTITY'=>$qty];
        }
    }

    return $items;
}

// --- Роутер ---
switch ($event) {
    case 'clearCart':
        $arResult = clearCart($data);
        echo json_encode(['status'=>$arResult["STATUS"], 'html'=>$arResult["HTML"], 'data'=>$arResult["DATA"]]);
        exit;

    case 'showForm':
        $arResult = showForm($data);
        echo json_encode(['status'=>$arResult["STATUS"], 'html'=>$arResult["HTML"], 'data'=>$arResult["DATA"] ?? []]);
        exit;

    case 'sendForm':
    	$arResult = sendForm($data);
    	echo json_encode([
        	'status' => $arResult["STATUS"],
        	'html'   => $arResult["HTML"] ?? '',
        	'reason' => $arResult["reason"] ?? null,   // удобно на время отладки
        	'b24'    => $arResult["b24"] ?? null       // тело ответа вебхука (временно)
    	]);
    	exit;

    case 'sendOrderForm':
        $arResult = sendOrderForm($data);
        echo json_encode(['status'=>$arResult["STATUS"], 'html'=>$arResult["HTML"], 'order'=>$arResult["ORDER"] ?? null]);
        exit;
}

// --- Реализация ---
function showForm($data) {
    if (empty($data["IBLOCK_ID"])) return ["STATUS"=>false];
    ob_start();
    global $APPLICATION;
    $APPLICATION->IncludeComponent(
        "webcomp:form",
        "fast_order",
        [
            "CACHE_FILTER"=>"N",
            "CACHE_TIME"=>"0",
            "CACHE_TYPE"=>"A",
            "ELEMENTS_COUNT"=>"20",
            "FIELD_CODE"=>"",
            "FILTER_NAME"=>"",
            "IBLOCK_ID"=>(int)$data["IBLOCK_ID"],
            "IBLOCK_TYPE"=>"forms",
            "PROPERTY_CODE"=>"",
            "SHOW_ONLY_ACTIVE"=>"Y",
            "SORT_BY1"=>"SORT",
            "SORT_BY2"=>"NAME",
            "SORT_ORDER1"=>"ASC",
            "SORT_ORDER2"=>"ASC",
            "COMPONENT_TEMPLATE"=>"popup",
            "EMAIL_EVENT_ID"=>$data["EMAIL_EVENT_ID"],
            "BIND_ELEMENTS"=>$data["ELEMENTS"],
            "FORM_NAME"=>$data["FORM_NAME"]
        ],
        false
    );
    $html = ob_get_clean();
    return ["STATUS"=>true, "HTML"=>$html];
}

function clearCart($data) {
    unset($_SESSION["CART"], $_SESSION["CART_DETAILS"], $_SESSION["CART_NAMES"], $_SESSION["CART_PRICES"]);
    return ["STATUS"=>true, "HTML"=>0, "DATA"=>[]];
}

/**
 * Обработчик простой формы из попапа (аналог sendForm из другого файла).
 * 1) Шлём сайт-уведомление через CMarketForm::Send
 * 2) Создаём лид в Bitrix24
 * 3) Возвращаем JSON как ждёт фронт: { status: bool, html: string }
 */
function handleSimpleLead(array $data): array {
    // 1) Штатное уведомление сайта (почта/инфоблок и т.п.)
    $status = CMarketForm::Send($data);

    // 2) Сбор контактов
    $name     = getVal(['NAME','USER_NAME','FIO','CLIENT_NAME'], $data);
    $email    = getVal(['EMAIL','MAIL','USER_EMAIL'], $data);
    $phoneRaw = getVal(['PHONE','TEL','PHONE_NUMBER','USER_PHONE'], $data);
    $phone    = normalize_phone($phoneRaw);

    // 3) Заголовок лида
    $formCode = isset($data['FORM_NAME']) ? trim((string)$data['FORM_NAME']) : '';
    $titleMap = [
        'CALLORDER_FOOTER' => 'Заказать звонок',
        'QUESTION'         => 'Возникли вопросы',
        'CALLORDER'        => 'Заказать звонок',
        'FEEDBACK'         => 'Обратная связь',
        'REVIEWS'          => 'Отзыв с сайта',
        'ONE_CLICK_BUY'    => 'Заказ в 1 клик',
        'POPUP_LEAD'       => 'Заявка из попапа',
    ];
    $title    = $titleMap[$formCode] ?? ($formCode !== '' ? $formCode : 'Заявка с сайта');

    // 4) Комментарии (сообщение+реферер+URI+IP+UTM)
    $comments = build_comments($data, 'MESSAGE');

    // 5) Поля лида
    $fields = ['TITLE'=>$title, 'SOURCE_ID'=>'WEB'];
    if ($name  !== '') $fields['NAME']  = $name;
    if ($email !== '') $fields['EMAIL'] = [['VALUE'=>$email, 'VALUE_TYPE'=>'WORK']];
    if ($phone !== '') $fields['PHONE'] = [['VALUE'=>$phone, 'VALUE_TYPE'=>'WORK']];
    if ($comments !== '') $fields['COMMENTS'] = $comments;

    // 6) Отправка в Bitrix24 (если есть как минимум телефон или email)
    try {
        if (!empty($fields['TITLE']) && (isset($fields['PHONE']) || isset($fields['EMAIL']))) {
            $res = b24_call('crm.lead.add', ['FIELDS'=>$fields]);
            log_append(
                LOG_LEAD,
                'lead.add(simple/popup)'
                .' | http_code='.($res['info']['http_code'] ?? '')
                .' | t='.($res['info']['total_time'] ?? '')
                .' | url='.$res['url']
                .' | payload_len='.strlen($res['payload_qs'])
                .' | resp_len='.strlen((string)($res['response'] ?? ''))
                .' | curl_err='.($res['err'] ?? '')
                .' | resp_body='.($res['response'] ?? '')
            );
        } else {
            log_append(LOG_LEAD, 'lead.skip(simple/popup): insufficient contact data | fields='.print_r($fields, true));
        }
    } catch (\Throwable $e) {
        log_append(LOG_LEAD, 'lead.exception(simple/popup): '.$e->getMessage());
    }

    // 7) Короткий HTML-ответ (фронту важен только статус, но пусть будет)
    $html = '<div class="popup__success">Спасибо! Ваша заявка принята.</div>';

    return ["STATUS"=>$status ? true : true, "HTML"=>$html];
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

function sendForm($data) {
    log_append(LOG_DBG, 'sendForm(hit) POST='.print_r($_POST, true));

    // Заголовок лида
    $formCode = isset($data['FORM_NAME']) ? trim((string)$data['FORM_NAME']) : '';
    $titleMap = [
        'CALLORDER_FOOTER' => 'Заказать звонок',
        'QUESTION'         => 'Возникли вопросы',
        'CALLORDER'        => 'Заказать звонок',
        'FEEDBACK'         => 'Обратная связь',
        'REVIEWS'          => 'Отзыв с сайта',
        'ONE_CLICK_BUY'    => 'Заказ в 1 клик',
        'POPUP_LEAD'       => 'Заявка с попапа',
    ];
    $title = $titleMap[$formCode] ?? ($formCode !== '' ? $formCode : 'Заявка с сайта');

    $name     = getVal(['NAME','USER_NAME','FIO','CLIENT_NAME'], $data);
    $phoneRaw = getVal(['PHONE','TEL','PHONE_NUMBER','USER_PHONE'], $data);
    $phone    = normalize_phone($phoneRaw);
    $comments = build_comments($data, 'MESSAGE'); // referer/URI/IP/UTM

    if ($phone === '') {
        log_append(LOG_LEAD, 'lead.skip(simple-popup): empty phone | data='.print_r($data,true));
        return ["STATUS"=>false, "HTML"=>"", "reason"=>"empty_phone"];
    }

    // user id из вебхука: /rest/379/xxxx/  -> 379
    $assignedId = 379;

    $fields = [
        'TITLE'         => ($title !== '' ? $title : 'Заявка с сайта'),
        'SOURCE_ID'     => 'WEB',
        'OPENED'        => 'Y',
        'ASSIGNED_BY_ID'=> $assignedId,     // <- важное поле (ответственный)
        'STATUS_ID'     => 'NEW',           // безопасное значение статуса
        'PHONE'         => [['VALUE'=>$phone, 'VALUE_TYPE'=>'WORK']],
    ];
    if ($name  !== '')    $fields['NAME']     = $name;
    if ($comments !== '') $fields['COMMENTS'] = $comments;

    try {
        $res = b24_call('crm.lead.add', ['FIELDS'=>$fields]);

        // Логи для диагностики
        log_append(
            LOG_LEAD,
            'lead.add(simple-popup) AFTER_SEND'
            .' | http_code='.($res['info']['http_code'] ?? '')
            .' | total_time='.($res['info']['total_time'] ?? '')
            .' | url='.$res['url']
            .' | curl_err='.$res['err']
        );
        log_append(LOG_LEAD, 'lead.add(simple-popup) RESP_BODY='.$res['response']);

        // Успех
        if (!empty($res['json']['result'])) {
            return ["STATUS"=>true, "HTML"=>"OK"];
        }

        // Явная ошибка Б24
        $errCode = $res['json']['error'] ?? '';
        $errDesc = $res['json']['error_description'] ?? '';
        // Типичные случаи: выключены лиды, не хватает UF_CRM_* поля, нет прав у вебхука
        if ($errCode !== '' || $errDesc !== '') {
            return [
                "STATUS"=>false,
                "HTML"=>"",
                "reason"=>"b24_failed",
                "b24_error"=>$errCode,
                "b24_error_description"=>$errDesc
            ];
        }

        // Не узнали — вернем сырой ответ
        return ["STATUS"=>false, "HTML"=>"", "reason"=>"b24_failed", "b24"=>$res['response']];

    } catch (\Throwable $e) {
        log_append(LOG_LEAD, 'lead.exception(simple-popup): '.$e->getMessage());
        return ["STATUS"=>false, "HTML"=>"", "reason"=>"exception"];
    }
}

function sendOrderForm($data) {
    // Маркеры в логах
    log_append(LOG_DBG, 'CART router hit | URI='.($_SERVER['REQUEST_URI']??'').' | IP='.($_SERVER['REMOTE_ADDR']??''));
    log_append(LOG_DBG, 'CART POST='.print_r($_POST,true).' | SESSION_CART='.print_r($_SESSION['CART'] ?? null,true));

    // 1) Сохраняем заказ стандартно
    $orderID = CMarketForm::Send($data);

    // 2) Контакты (+расширенный маппинг)
    $nameKeys  = ['NAME','USER_NAME','FIO','CLIENT_NAME','FIRST_NAME','FULL_NAME','CONTACT_NAME'];
    $mailKeys  = ['EMAIL','MAIL','USER_EMAIL','CONTACT_EMAIL','ORDER_EMAIL'];
    $phoneKeys = ['PHONE','TEL','PHONE_NUMBER','USER_PHONE','CONTACT_PHONE','ORDER_PHONE'];

    $name     = getVal($nameKeys,  $data);
    $email    = getVal($mailKeys,  $data);
    $phoneRaw = getVal($phoneKeys, $data);

    if ($name==='' && isset($_SESSION['ORDER_NAME']))      $name     = trim((string)$_SESSION['ORDER_NAME']);
    if ($email==='' && isset($_SESSION['ORDER_EMAIL']))     $email    = trim((string)$_SESSION['ORDER_EMAIL']);
    if ($phoneRaw==='' && isset($_SESSION['ORDER_PHONE']))  $phoneRaw = trim((string)$_SESSION['ORDER_PHONE']);

    $phone = normalize_phone($phoneRaw);

    // 3) Комментарий-основа
    $commentsBase = build_comments($data, 'COMMENT');

	// 4) Корзина: из POST -> CART_DETAILS -> legacy
	$cart = collect_cart_items($data);
	log_append(LOG_DBG, 'CART normalized: '.print_r($cart, true));

    // 5) Сформировать блок товаров
    $lines = [];
    $total = 0.0;
    if (!empty($cart)) {
        $lines[] = "Товары:";
        foreach ($cart as $item) {
            $title = trim((string)($item['NAME'] ?? $item['TITLE'] ?? 'Позиция'));
            $qty   = (float)($item['QUANTITY'] ?? $item['QTY'] ?? 1);
            $price = (float)($item['PRICE'] ?? $item['AMOUNT'] ?? 0);
            $sum   = $qty * $price; $total += $sum;
            $lines[] = sprintf("• %s — %s шт × %0.2f = %0.2f",
                $title,
                rtrim(rtrim(number_format($qty, 2, '.', ''), '0'), '.'),
                $price, $sum
            );
        }
        $lines[] = sprintf("Итого: %0.2f", $total);
    } else {
        $lines[] = "Товары: (корзина пустая или не передана)";
    }

    // 6) Контакты в COMMENTS
    $contactLines = [];
    if ($name   !== '') $contactLines[] = "Имя: ".$name;
    if ($phone  !== '') $contactLines[] = "Телефон: ".$phone;
    if ($email  !== '') $contactLines[] = "Email: ".$email;

    $blockContacts = !empty($contactLines) ? ("Контакты:\n".implode("\n", $contactLines)) : "Контакты: не указаны";
    $blockCart     = implode("\n", $lines);
    $fullComments  = trim($commentsBase !== '' ? ($commentsBase."\n\n".$blockContacts."\n\n".$blockCart) : ($blockContacts."\n\n".$blockCart));

    // (опционально) ограничение длины
    // $fullComments = mb_substr($fullComments, 0, 60000, 'UTF-8');

    // 7) Собираем лид
    $title = $name ? ('Заказ с сайта: '.$name) : 'Заказ с сайта';
    $fields = [
        'TITLE'     => $title,
        'SOURCE_ID' => 'WEB',
        'COMMENTS'  => $fullComments,
        'UF_CRM_1712345678' => 'Сайт duqametal: попап',
		'UF_CRM_1712349999' => 123,
		'ASSIGNED_BY_ID' => 1 
    ];
    if ($name  !== '') $fields['NAME']  = $name;
    if ($email !== '') $fields['EMAIL'] = [['VALUE'=>$email, 'VALUE_TYPE'=>'WORK']];
    if ($phone !== '') $fields['PHONE'] = [['VALUE'=>$phone, 'VALUE_TYPE'=>'WORK']];

    $payloadPreview = http_build_query(['FIELDS'=>$fields], '', '&');
    log_append(LOG_LEAD,
        'lead.add(order/cart) BEFORE_SEND'
        .' | comments_len='.strlen($fullComments)
        .' | payload_len='.strlen($payloadPreview)
        .' | name_present='.($name!==''?'1':'0')
        .' | email_present='.($email!==''?'1':'0')
        .' | phone_present='.($phone!==''?'1':'0')
    );

    // 8) Отправка в Bitrix24
    try {
        $res = b24_call('crm.lead.add', ['FIELDS'=>$fields]);
        log_append(LOG_LEAD,
            'lead.add(order/cart) AFTER_SEND'
            .' | http_code='.($res['info']['http_code'] ?? '')
            .' | total_time='.($res['info']['total_time'] ?? '')
            .' | url='.$res['url']
            .' | curl_err='.$res['err']
        );
        log_append(LOG_LEAD, 'lead.add(order/cart) RESP_BODY='.$res['response']);
        log_append(LOG_LEAD, 'lead.add(order/cart) PAYLOAD_QS='.$res['payload_qs']);
        // === Привязка товарных строк к лиду ===
		$leadId = (int)($res['json']['result'] ?? 0);
		if ($leadId > 0 && !empty($cart)) {
		    $rows = build_b24_rows($cart);
		    $resRows = b24_call('crm.lead.productrows.set', [
		        'id'   => $leadId,
		        'rows' => $rows,
		    ]);

    		log_append(
    		    LOG_LEAD,
    		    'lead.productrows.set(order/cart)'
    		    .' | lead_id='.$leadId
    		    .' | rows_count='.count($rows)
    		    .' | http_code='.($resRows['info']['http_code'] ?? '')
    		    .' | curl_err='.$resRows['err']
    		);
    		log_append(LOG_LEAD, 'lead.productrows.set(order/cart) RESP_BODY='.$resRows['response']);
		}
    } catch (\Throwable $e) {
        log_append(LOG_LEAD, 'lead.exception(order/cart): '.$e->getMessage());
    }

    // 9) Очистка корзины после отправки (как было)
    unset($_SESSION["CART"], $_SESSION["CART_DETAILS"], $_SESSION["CART_NAMES"], $_SESSION["CART_PRICES"]);

    // 10) Ответ фронту (как было)
    if ($orderID) {
        $_SESSION["ORDER"] = ["STATUS"=>true, "ORDER_ID"=>$orderID];
        return ["STATUS"=>true, "HTML"=>"/cart/ok/", "ORDER"=>$orderID];
    } else {
        // Даже если CMarketForm::Send вернул false — лид в B24 уже создан выше
        return ["STATUS"=>false, "HTML"=>0];
    }
}

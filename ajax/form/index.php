<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
// if (isset($_GET['dump_fields'])) {
//     require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
//     header('Content-Type: text/plain; charset=utf-8');

//     // Временная функция под другим именем, чтобы не конфликтовать с b24_call()
//     function b24_tmp_call(string $method, array $payload = []): array {
//         $url = rtrim('https://prombooks.bitrix24.ru/rest/379/pnfw7pma014pup3w/', '/').'/'.ltrim($method, '/').'.json';
//         $ch = curl_init();
//         curl_setopt_array($ch, [
//             CURLOPT_URL => $url,
//             CURLOPT_POST => true,
//             CURLOPT_POSTFIELDS => http_build_query($payload, '', '&'),
//             CURLOPT_RETURNTRANSFER => true,
//         ]);
//         $response = curl_exec($ch);
//         curl_close($ch);
//         return json_decode($response, true);
//     }

//     $res = b24_tmp_call('crm.lead.fields');
//     foreach ($res['result'] as $code => $field) {
//         echo $code.' — '.$field['formLabel']."\n";
//     }
//     exit;
// }
// ===== UTM capture (first hit -> cookies + session) =====
$utmKeys = ['utm_source','utm_medium','utm_campaign','utm_content','utm_term'];

// корректный домен для cookie: пустой для IP, иначе ".example.ru"
$host = $_SERVER['HTTP_HOST'] ?? '';
$hostNoPort = preg_replace('/:\d+$/', '', $host);
$cookieDomain = filter_var($hostNoPort, FILTER_VALIDATE_IP) ? '' : ('.' . $hostNoPort);

$expires = time() + 60*60*24*90; // 90 дней

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

foreach ($utmKeys as $k) {
    if (isset($_GET[$k]) && $_GET[$k] !== '') {
        $v = trim((string)$_GET[$k]);
        // первоисточник не затираем
        if (empty($_COOKIE[$k])) {
            setcookie($k, $v, $expires, '/', $cookieDomain, (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'), true);
            $_COOKIE[$k] = $v; // доступно в текущем запросе
        }
        if (empty($_SESSION[$k])) {
            $_SESSION[$k] = $v;
        }
    }
}

// Утилита для чтения UTM (GET -> COOKIE -> SESSION)
function utm_get(string $k): string {
    return $_GET[$k] ?? $_COOKIE[$k] ?? $_SESSION[$k] ?? '';
}
header('Content-Type: application/json');

use Webcomp\Market\Settings;

const B24_BASE_WEBHOOK = 'https://prombooks.bitrix24.ru/rest/379/pnfw7pma014pup3w/';
const LOG_LEAD = '/upload/bitrix24_crm_lead_log.log';
const LOG_DEAL = '/upload/bitrix24_crm_deal_log.log';
const LOG_DBG  = '/upload/debug.log';

// Если отдельные поля лида для UTM есть — укажи коды здесь.
const B24_UTM_FIELD_MAP = [
  'utm_source'   => 'UTM_SOURCE',
  'utm_medium'   => 'UTM_MEDIUM',
  'utm_campaign' => 'UTM_CAMPAIGN',
  'utm_content'  => 'UTM_CONTENT',
  'utm_term'     => 'UTM_TERM',
];

if (!isset($GLOBALS["WEBCOMP"]["SETTINGS"])) {
    $GLOBALS["WEBCOMP"]["SETTINGS"] = Settings::GetGlobalSettings();
}

$data  = $_POST;
$event = $data['EVENT'] ?? '';

if (!empty($_FILES)) {
    $data = array_merge($data, $_FILES);
}

// Поддержка JSON-тел: если пришёл application/json, $_POST будет пустым
if (empty($_POST)) {
    $raw = file_get_contents('php://input');
    if ($raw) {
        $json = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
            $_POST = $json;
            $data  = $json;
            $event = $data['EVENT'] ?? $event;
        }
    }
}

/* ==================== Вспомогательное ==================== */
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

function build_comments(array $data, string $messageKey = 'MESSAGE'): string {
    $message = getVal([$messageKey, 'TEXT', 'COMMENT', 'COMMENTS'], $data);
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $uri     = $_SERVER['REQUEST_URI'] ?? '';
    $ip      = $_SERVER['REMOTE_ADDR'] ?? '';
    $lines   = [];
    if ($message !== '') $lines[] = 'Сообщение: '.$message;
    if ($referer !== '') $lines[] = 'Страница: '.$referer;
    if ($uri     !== '') $lines[] = 'URI: '.$uri;
    if ($ip      !== '') $lines[] = 'IP: '.$ip;
    foreach (['utm_source','utm_medium','utm_campaign','utm_content','utm_term'] as $utm) {
        $val = utm_get($utm);
        if ($val !== '') $lines[] = strtoupper($utm).': '.$val;
    }
    return implode("\n", $lines);
}
/**
 * Отправка в Bitrix24 с подробной диагностикой.
 * Возвращает: ok/json/response/err/url/payload/payload_qs/info (curl_getinfo)
 */
function b24_call(string $method, array $payload): array {
    $url        = rtrim(B24_BASE_WEBHOOK, '/').'/'.ltrim($method, '/').'.json';
    $payload_qs = http_build_query($payload, '', '&');

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload_qs,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT        => 12,
        CURLOPT_HEADER         => false, // тело без заголовков
    ]);

    $response = curl_exec($ch);
    $curlErr  = curl_error($ch);
    $info     = curl_getinfo($ch); // http_code, total_time, size_upload, size_download, etc.
    curl_close($ch);

    $decoded = @json_decode((string)$response, true);

    // Единый лог каждого вызова (можно оставить, чтобы видеть любые методы)
    log_append(
        LOG_LEAD,
        '[b24_call] method='.$method
        .' | http_code='.(string)($info['http_code'] ?? '')
        .' | total_time='.(string)($info['total_time'] ?? '')
        .' | url='.$url
        .' | payload_len='.strlen($payload_qs)
        .' | resp_len='.strlen((string)$response)
        .' | curl_err='.$curlErr
    );

    return [
        'ok'         => is_array($decoded) && array_key_exists('result', $decoded),
        'response'   => $response,
        'json'       => $decoded,
        'err'        => $curlErr,
        'url'        => $url,
        'payload'    => $payload,
        'payload_qs' => $payload_qs,
        'info'       => $info,
    ];
}

// ==== Получение NAME и PRICE: сначала каталог, затем свойства инфоблока ====
function fetch_product_info(int $id): array {
    if (!empty($_SESSION['CART_NAMES'][$id]) && isset($_SESSION['CART_PRICES'][$id])) {
        return ['ID'=>$id, 'NAME'=>(string)$_SESSION['CART_NAMES'][$id], 'PRICE'=>(float)$_SESSION['CART_PRICES'][$id]];
    }

    $name = 'Товар #'.$id; $price = 0.0; $iblockId = 0;

    if (class_exists('\Bitrix\Main\Loader')) {
        \Bitrix\Main\Loader::includeModule('iblock');
        \Bitrix\Main\Loader::includeModule('catalog');
    } else {
        CModule::IncludeModule('iblock');
        CModule::IncludeModule('catalog');
    }

    if (class_exists('\CIBlockElement')) {
        if ($res = \CIBlockElement::GetByID($id)) {
            if ($ob = $res->GetNext()) {
                $name     = html_entity_decode((string)$ob['NAME'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $iblockId = (int)$ob['IBLOCK_ID'];
            }
        }
    }

    if (class_exists('\Bitrix\Catalog\GroupTable') && class_exists('\Bitrix\Catalog\PriceTable')) {
        try {
            $base = \Bitrix\Catalog\GroupTable::getBaseGroup();
            if (is_array($base) && isset($base['ID'])) {
                $row = \Bitrix\Catalog\PriceTable::getList([
                    'filter'=>['=PRODUCT_ID'=>$id,'=CATALOG_GROUP_ID'=>$base['ID']],
                    'select'=>['PRICE'], 'limit'=>1,
                ])->fetch();
                if ($row && isset($row['PRICE'])) $price = (float)$row['PRICE'];
            }
        } catch (\Throwable $e) {}
    }
    if ($price <= 0 && class_exists('\Bitrix\Catalog\PriceTable')) {
        try {
            $any = \Bitrix\Catalog\PriceTable::getList([
                'filter'=>['=PRODUCT_ID'=>$id],
                'select'=>['PRICE'], 'order'=>['PRICE'=>'asc'], 'limit'=>1,
            ])->fetch();
            if ($any && isset($any['PRICE'])) $price = (float)$any['PRICE'];
        } catch (\Throwable $e) {}
    }
    if ($price <= 0 && class_exists('\CPrice')) {
        if ($ar = \CPrice::GetList(['PRICE'=>'ASC'], ['PRODUCT_ID'=>$id])->Fetch()) {
            $price = (float)$ar['PRICE'];
        }
    }

    // свойства инфоблока: "Цена, ₽" и т.п.
    if ($price <= 0 && $iblockId > 0 && class_exists('\CIBlockElement')) {
        $codes = ['PRICE','TSENA','CENA','MINIMUM_PRICE','PRICE_RUB','PRICE_RUR'];
        foreach ($codes as $code) {
            $pr = \CIBlockElement::GetProperty($iblockId, $id, [], ['CODE'=>$code])->Fetch();
            if ($pr && ($pr['VALUE'] ?? '') !== '') {
                $val = (string)$pr['VALUE'];
                $val = str_replace([' ', ','], ['', '.'], $val);
                $val = preg_replace('/[^\d.]/', '', $val);
                if ($val !== '') { $price = (float)$val; break; }
            }
        }
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

    $_SESSION['CART_NAMES'][$id]  = $name;
    $_SESSION['CART_PRICES'][$id] = $price;

    if ($price <= 0) {
        log_append(LOG_DBG, "fetch_product_info(form) id=$id | name='$name' | price=$price | iblock=$iblockId");
    }

    return ['ID'=>$id, 'NAME'=>$name, 'PRICE'=>$price];
}

// ==== Собрать позиции корзины из POST / SESSION ====
function collect_cart_items(array $data): array {
    $items = [];

    // Приоритет: CART/ITEMS из POST (array или JSON-строка)
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

            if ($name === '' || $price <= 0) {
                $info = fetch_product_info($id);
                if ($name === '')  $name  = $info['NAME'];
                if ($price <= 0)   $price = $info['PRICE'];
            }
            $items[] = ['ID'=>$id,'NAME'=>$name,'PRICE'=>$price,'QUANTITY'=>$qty];
        }
        if (!empty($items)) return $items;
    }

    // Детальная сессия
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

    // Legacy: ID=>qty
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

/* ==================== Рендер форм ==================== */
function showForm($data) {
    if (empty($data["IBLOCK_ID"])) return ["STATUS" => false];
    ob_start();
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
    $html = ob_get_contents();
    ob_end_clean();
    return ["STATUS" => true, "HTML" => $html];
}

function renderSuccessHtmlGeneric($data): string {
    ob_start(); ?>
    <?php if (($data["FORM_NAME"] ?? '') === "CALLORDER_FOOTER"): ?>
        <div class="ffeed__success_txt">Спасибо! Ваша заявка принята в работу.<br>Ожидайте звонка.</div>
    <?php elseif (($data["FORM_NAME"] ?? '') === "FEEDBACK"): ?>
        <div class="ccall__success_txt">Спасибо! Ваша заявка принята в работу.<br>Ожидайте звонка.</div>
    <?php elseif (($data["FORM_NAME"] ?? '') === "REVIEWS"): ?>
        <div class="popup__top">
            <button class="popup__close jsFormClose" type="button">
                <svg class="popup__close-svg"><use xlink:href="/images/icons/sprite.svg#close"></use></svg>
            </button>
        </div>
        <div class="popup__success">
            <div class="psuccess">
                <div class="psuccess__img"><svg class="psuccess__svg"><use xlink:href="/images/icons/sprite.svg#chech-round"></use></svg></div>
                <div class="psuccess__title">Спасибо!</div>
                <div class="psuccess__txt">Ваш отзыв принят.</div>
                <button type="button" class="psuccess__close jsFormClose btn">Закрыть</button>
            </div>
        </div>
    <?php else: ?>
        <div class="popup__top">
            <button class="popup__close jsFormClose" type="button">
                <svg class="popup__close-svg"><use xlink:href="/images/icons/sprite.svg#close"></use></svg>
            </button>
        </div>
        <div class="popup__success">
            <div class="psuccess">
                <div class="psuccess__img"><svg class="psuccess__svg"><use xlink:href="/images/icons/sprite.svg#chech-round"></use></svg></div>
                <div class="psuccess__title">Спасибо!</div>
                <div class="psuccess__txt">Ваша заявка принята в работу.<br>Ожидайте звонка.</div>
                <button type="button" class="psuccess__close jsFormClose btn">Закрыть</button>
            </div>
        </div>
    <?php endif; ?>
    <?php return ob_get_clean();
}

function renderSuccessHtmlOrder(): string {
    ob_start(); ?>
    <div class="popup__success">
        <div class="psuccess">
            <div class="psuccess__img"><svg class="psuccess__svg"><use xlink:href="/images/icons/sprite.svg#chech-round"></use></svg></div>
            <div class="psuccess__title">Спасибо!</div>
            <div class="psuccess__txt">Ваш заказ принят. Мы свяжемся для подтверждения.</div>
            <button type="button" class="psuccess__close jsFormClose btn">Закрыть</button>
        </div>
    </div>
    <?php return ob_get_clean();
}

/* ==================== Простые формы → crm.lead.add ==================== */
function sendForm($data) {
    $html   = renderSuccessHtmlGeneric($data);
    $status = CMarketForm::Send($data);

    $name     = getVal(['NAME', 'USER_NAME', 'FIO', 'CLIENT_NAME'], $data);
    $email    = getVal(['EMAIL', 'MAIL', 'USER_EMAIL'], $data);
    $phoneRaw = getVal(['PHONE','TEL','PHONE_NUMBER','USER_PHONE'], $data);
    $phone    = normalize_phone($phoneRaw);

    $formCode = isset($data['FORM_NAME']) ? trim((string)$data['FORM_NAME']) : '';
    $titleMap = [
        'CALLORDER_FOOTER' => 'Заказать звонок',
        'QUESTION'         => 'Возникли вопросы',
        'CALLORDER'        => 'Заказать звонок',
        'FEEDBACK'         => 'Обратная связь',
        'REVIEWS'          => 'Отзыв с сайта',
        'ONE_CLICK_BUY'    => 'Заказ в 1 клик',
    ];
    $title    = $titleMap[$formCode] ?? ($formCode !== '' ? $formCode : 'Заявка с сайта');
    $comments = build_comments($data, 'MESSAGE');

    $fields = ['TITLE'=>$title, 'SOURCE_ID'=>'WEB'];
    if ($name  !== '') $fields['NAME']  = $name;
    if ($email !== '') $fields['EMAIL'] = [['VALUE'=>$email, 'VALUE_TYPE'=>'WORK']];
    if ($phone !== '') $fields['PHONE'] = [['VALUE'=>$phone, 'VALUE_TYPE'=>'WORK']];
    if ($comments !== '') $fields['COMMENTS'] = $comments;
    
    // Дополнительно сохраняем все UTM в поле UF_CRM_COOKIES
	$rawCookies = [];
	foreach (['utm_source','utm_medium','utm_campaign','utm_content','utm_term'] as $k) {
	    $v = utm_get($k);
	    if ($v !== '') $rawCookies[] = $k.'='.$v;
	}
	if (!empty($rawCookies)) {
	    $fields['UF_CRM_COOKIES'] = implode('; ', $rawCookies);
	}

    try {
        if (!empty($fields['TITLE']) && (isset($fields['PHONE']) || isset($fields['EMAIL']))) {
        	// UTM → отдельные поля лида (если маппинг настроен)
			foreach (B24_UTM_FIELD_MAP as $k => $b24Field) {
			    $val = utm_get($k);
			    if ($b24Field && $val !== '') { $fields[$b24Field] = $val; }
			}
            $res = b24_call('crm.lead.add', ['FIELDS'=>$fields]);
            log_append(
                LOG_LEAD,
                'lead.add(simple)'
                .' | http_code='.($res['info']['http_code'] ?? '')
                .' | t='.($res['info']['total_time'] ?? '')
                .' | url='.$res['url']
                .' | payload_len='.strlen($res['payload_qs'])
                .' | resp_len='.strlen((string)$res['response'])
                .' | curl_err='.$res['err']
                .' | resp_body='.$res['response']
            );
        } else {
            log_append(LOG_LEAD, 'lead.skip(simple): insufficient contact data | fields='.print_r($fields, true));
        }
    } catch (\Throwable $e) {
        log_append(LOG_LEAD, 'lead.exception(simple): '.$e->getMessage());
    }

    return ["STATUS"=>$status, "HTML"=>$html];
}

/* ========== Корзина → crm.lead.add (позиции в COMMENTS) + детальный лог ========== */
function sendOrderForm($data) {
    // Маркер: дошли до обработчика
    log_append(LOG_DBG, 'ROUTER sendOrderForm hit | URI='.($_SERVER['REQUEST_URI'] ?? '').' | IP='.($_SERVER['REMOTE_ADDR'] ?? ''));
    log_append(LOG_DBG, 'sendOrderForm POST='.print_r($_POST,true).' | SESSION_CART='.print_r($_SESSION['CART'] ?? null,true));

    // 1) Сайт: штатная отправка
    $status = CMarketForm::Send($data);

    // 2) Контакты (расширенный маппинг + из сессии)
    $nameKeys  = ['NAME','USER_NAME','FIO','CLIENT_NAME','FIRST_NAME','FULL_NAME','CONTACT_NAME'];
    $mailKeys  = ['EMAIL','MAIL','USER_EMAIL','CONTACT_EMAIL','ORDER_EMAIL'];
    $phoneKeys = ['PHONE','TEL','PHONE_NUMBER','USER_PHONE','CONTACT_PHONE','ORDER_PHONE'];

    $name     = getVal($nameKeys,  $data);
    $email    = getVal($mailKeys,  $data);
    $phoneRaw = getVal($phoneKeys, $data);

    if ($name === '' && isset($_SESSION['ORDER_NAME']))     $name     = trim((string)$_SESSION['ORDER_NAME']);
    if ($email === '' && isset($_SESSION['ORDER_EMAIL']))   $email    = trim((string)$_SESSION['ORDER_EMAIL']);
    if ($phoneRaw === '' && isset($_SESSION['ORDER_PHONE'])) $phoneRaw = trim((string)$_SESSION['ORDER_PHONE']);

    $phone = normalize_phone($phoneRaw);

    // 3) Комментарии (база)
    $comments = build_comments($data, 'COMMENT');

    // 4) Корзина: из POST -> CART_DETAILS -> legacy
	$cart = collect_cart_items($data);
	log_append(LOG_DBG, 'ORDER items normalized (form): '.print_r($cart, true));

    // 5) Текст позиций (и итог)
    $lines = [];
    $total = 0.0;
    if (!empty($cart)) {
        $lines[] = "Товары:";
        foreach ($cart as $item) {
            $title = trim((string)($item['NAME'] ?? $item['TITLE'] ?? 'Позиция'));
            $qty   = (float)($item['QUANTITY'] ?? $item['QTY'] ?? 1);
            $price = (float)($item['PRICE'] ?? $item['AMOUNT'] ?? 0);
            $sum   = $qty * $price;
            $total += $sum;
            $lines[] = sprintf(
                "• %s — %s шт × %0.2f = %0.2f",
                $title,
                rtrim(rtrim(number_format($qty, 2, '.', ''), '0'), '.'),
                $price,
                $sum
            );
        }
        $lines[] = sprintf("Итого: %0.2f", $total);
    } else {
        $lines[] = "Товары: (корзина пустая или не передана)";
    }

    // 6) Контакты в COMMENTS для надёжности
    $contactLines = [];
    if ($name   !== '') $contactLines[] = "Имя: ".$name;
    if ($phone  !== '') $contactLines[] = "Телефон: ".$phone;
    if ($email  !== '') $contactLines[] = "Email: ".$email;

    $blockContacts = !empty($contactLines) ? ("Контакты:\n".implode("\n", $contactLines)) : "Контакты: не указаны";
    $blockCart     = implode("\n", $lines);
    $fullComments  = trim($comments !== '' ? ($comments."\n\n".$blockContacts."\n\n".$blockCart) : ($blockContacts."\n\n".$blockCart));

    // (опционально) ограничение длины COMMENTS, чтобы не упереться в лимиты
    // $fullComments = mb_substr($fullComments, 0, 60000, 'UTF-8');

    // 7) Поля лида
    $title = $name ? ('Заказ с сайта: '.$name) : 'Заказ с сайта';
    $fields = [
        'TITLE'     => $title,
        'SOURCE_ID' => 'WEB',
        'COMMENTS'  => $fullComments,
    ];
    if ($name  !== '') $fields['NAME']  = $name;
    if ($email !== '') $fields['EMAIL'] = [['VALUE'=>$email, 'VALUE_TYPE'=>'WORK']];
    if ($phone !== '') $fields['PHONE'] = [['VALUE'=>$phone, 'VALUE_TYPE'=>'WORK']];
    
    // Дополнительно сохраняем все UTM в поле UF_CRM_COOKIES
	$rawCookies = [];
	foreach (['utm_source','utm_medium','utm_campaign','utm_content','utm_term'] as $k) {
	    $v = utm_get($k);
	    if ($v !== '') $rawCookies[] = $k.'='.$v;
	}
	if (!empty($rawCookies)) {
	    $fields['UF_CRM_COOKIES'] = implode('; ', $rawCookies);
	}

    // Диагностика «перед отправкой»
    $payloadPreview = http_build_query(['FIELDS'=>$fields], '', '&');
    log_append(
        LOG_LEAD,
        'lead.add(order) BEFORE_SEND'
        .' | comments_len='.strlen($fullComments)
        .' | payload_len='.strlen($payloadPreview)
        .' | name_present='.($name!==''?'1':'0')
        .' | email_present='.($email!==''?'1':'0')
        .' | phone_present='.($phone!==''?'1':'0')
    );

    // 8) Отправка в B24 + подробные логи ответа
    try {
    	// UTM → отдельные поля лида (если маппинг настроен)
		foreach (B24_UTM_FIELD_MAP as $k => $b24Field) {
    		$val = utm_get($k);
    		if ($b24Field && $val !== '') { $fields[$b24Field] = $val; }
		}
        $res = b24_call('crm.lead.add', ['FIELDS'=>$fields]);

        log_append(
            LOG_LEAD,
            'lead.add(order) AFTER_SEND'
            .' | http_code='.($res['info']['http_code'] ?? '')
            .' | total_time='.($res['info']['total_time'] ?? '')
            .' | namelookup_time='.($res['info']['namelookup_time'] ?? '')
            .' | connect_time='.($res['info']['connect_time'] ?? '')
            .' | starttransfer_time='.($res['info']['starttransfer_time'] ?? '')
            .' | size_upload='.($res['info']['size_upload'] ?? '')
            .' | size_download='.($res['info']['size_download'] ?? '')
            .' | url='.$res['url']
            .' | curl_err='.$res['err']
        );

        // Тело ответа целиком (можно закомментировать, если будет слишком шумно)
        log_append(LOG_LEAD, 'lead.add(order) RESP_BODY='.$res['response']);

        // На всякий случай — что именно отправили
        log_append(LOG_LEAD, 'lead.add(order) PAYLOAD_QS='.$res['payload_qs']);
        
        // === Привязать товары к лиду ===
		$leadId = (int)($res['json']['result'] ?? 0);
		if ($leadId > 0 && !empty($cart)) {
		    $rows = build_b24_rows($cart);
		    $resRows = b24_call('crm.lead.productrows.set', [
		        'id'   => $leadId,
		        'rows' => $rows,
		    ]);
		
		    log_append(
		        LOG_LEAD,
		        'lead.productrows.set(order)'
		        .' | lead_id='.$leadId
		        .' | rows_count='.count($rows)
		        .' | http_code='.($resRows['info']['http_code'] ?? '')
		        .' | curl_err='.$resRows['err']
		    );
		    log_append(LOG_LEAD, 'lead.productrows.set(order) RESP_BODY='.$resRows['response']);
		}

    } catch (\Throwable $e) {
        log_append(LOG_LEAD, 'lead.exception(order): '.$e->getMessage());
    }
    
    unset($_SESSION["CART"], $_SESSION["CART_DETAILS"], $_SESSION["CART_NAMES"], $_SESSION["CART_PRICES"]);

    return ["STATUS"=>$status, "HTML"=>renderSuccessHtmlOrder()];
}

/**
 * Нормализованная корзина -> product rows для Bitrix24.
 * По умолчанию используем свободные позиции (PRODUCT_NAME), чтобы не требовалась синхронизация ID.
 */
function build_b24_rows(array $cart): array {
    $rows = [];
    foreach ($cart as $item) {
        $name  = (string)($item['NAME'] ?? $item['TITLE'] ?? 'Позиция');
        $qty   = (float) ($item['QUANTITY'] ?? $item['QTY'] ?? 1);
        $price = (float) ($item['PRICE'] ?? $item['AMOUNT'] ?? 0);

        $rows[] = [
            'PRODUCT_NAME' => $name,
            'PRICE'        => $price,
            'QUANTITY'     => $qty,
            'CURRENCY_ID'  => 'RUB',
        ];

        // Если ID сайта == ID товара в каталоге B24 — можно так:
        // $rows[] = ['PRODUCT_ID' => (int)($item['ID'] ?? 0), 'PRICE'=>$price, 'QUANTITY'=>$qty, 'CURRENCY_ID'=>'RUB'];
    }
    return $rows;
}

/* ==================== Роутер ==================== */
switch ($event) {
    case 'showForm':
        $arResult = showForm($data);
        echo json_encode(['status'=>$arResult["STATUS"], 'html'=>$arResult["HTML"]]);
        break;

    case 'sendForm':
        $arResult = sendForm($data);
        echo json_encode(['status'=>$arResult["STATUS"], 'html'=>$arResult["HTML"]]);
        break;

    case 'sendOrderForm':
        $arResult = sendOrderForm($data);
        echo json_encode(['status'=>$arResult["STATUS"], 'html'=>$arResult["HTML"]]);
        break;

    default:
        echo json_encode(['status'=>false, 'html'=>'']);
        break;
}
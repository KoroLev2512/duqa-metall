<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: application/json; charset=UTF-8');

use Webcomp\Market\Settings;

// используем тот же константный вебхук, как в cart/index.php
const B24_BASE_WEBHOOK = 'https://prombooks.bitrix24.ru/rest/379/v26qgl8jrkfro3t0/';
const LOG_LEAD = '/upload/bitrix24_crm_lead_log.log';

function log_append(string $file, string $line): void {
    @file_put_contents($_SERVER['DOCUMENT_ROOT'].$file, date('c').' | '.$line.PHP_EOL, FILE_APPEND);
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

    log_append(LOG_LEAD, "[popup_lead] call {$method} | http_code=".($info['http_code']??'')." | err={$err}");
    return ['json' => @json_decode((string)$response, true), 'raw'=>$response, 'info'=>$info, 'err'=>$err];
}

// принимаем JSON и обычный POST
$raw = file_get_contents('php://input');
$data = $_POST;
if (empty($data) && $raw) {
    $j = json_decode($raw, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($j)) { $data = $j; }
}

$name  = trim((string)($data['NAME']  ?? ''));
$phone = trim((string)($data['PHONE'] ?? ''));
$email = trim((string)($data['EMAIL'] ?? ''));
$comment = "Всплывающая форма на сайте\nURI: ".($_SERVER['REQUEST_URI'] ?? '')."\nIP: ".($_SERVER['REMOTE_ADDR'] ?? '');

$fields = [
    'TITLE'     => $name ? ("Заявка (попап): {$name}") : 'Заявка (попап)',
    'SOURCE_ID' => 'WEB',
    'COMMENTS'  => $comment,
];

if ($name  !== '') $fields['NAME']  = $name;
if ($phone !== '') $fields['PHONE'] = [['VALUE'=>$phone, 'VALUE_TYPE'=>'WORK']];
if ($email !== '') $fields['EMAIL'] = [['VALUE'=>$email, 'VALUE_TYPE'=>'WORK']];

try {
    $res = b24_call('crm.lead.add', ['FIELDS'=>$fields]);
    if (isset($res['json']['result']) && (int)$res['json']['result'] > 0) {
        echo json_encode(['ok'=>true, 'id'=>(int)$res['json']['result']]);
    } else {
        log_append(LOG_LEAD, '[popup_lead] resp='.$res['raw']);
        echo json_encode(['ok'=>false, 'error'=>'BITRIX24_ERROR']);
    }
} catch (\Throwable $e) {
    log_append(LOG_LEAD, '[popup_lead] exception: '.$e->getMessage());
    echo json_encode(['ok'=>false, 'error'=>'SERVER_EXCEPTION']);
}
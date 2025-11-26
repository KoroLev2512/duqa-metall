<?php
use Bitrix\Main\Context;

/**
 * Глобальная SEO-надстройка:
 *  - canonical с учётом только PAGEN_1
 *  - дефолтный description, если не задан на странице/разделе
 */
if (!defined('ADMIN_SECTION') || ADMIN_SECTION !== true) {

    $context = Context::getCurrent();
    $request = $context->getRequest();
    $scheme  = $request->isHttps() ? 'https' : 'http';
    $host    = $request->getHttpHost();
    $uri     = $request->getRequestUri();

    $parts = parse_url($uri);
    $path  = $parts['path'] ?? '/';
    $query = [];

    if (!empty($parts['query'])) {
        parse_str($parts['query'], $query);

        // Оставляем только параметр пагинации
        $allowed = ['PAGEN_1' => true];
        $query   = array_intersect_key($query, $allowed);

        // Не канонизируем PAGEN_1=1
        if (isset($query['PAGEN_1']) && (int)$query['PAGEN_1'] <= 1) {
            unset($query['PAGEN_1']);
        }
    }

    $canonical = $scheme.'://'.$host.$path;
    if ($query) {
        $canonical .= '?'.http_build_query($query);
    }

    if ($canonical !== $scheme.'://'.$host.'/') {
        $canonical = rtrim($canonical, '/');
    }

    global $APPLICATION;
    if (is_object($APPLICATION)) {
        // canonical
        $APPLICATION->AddHeadString(
            '<link rel="canonical" href="'.htmlspecialcharsbx($canonical).'" />',
            true
        );

        // дефолтный description (если нигде не задан)
        $desc = trim($APPLICATION->GetPageProperty('description'));
        if ($desc === '') {
            $desc = 'Металлопрокат, трубы, балки, уголки, потолки, нержавеющая и медная сталь. Продажа, резка и доставка — DUQA Metall.';
            $APPLICATION->SetPageProperty('description', $desc);
        }
    }
}
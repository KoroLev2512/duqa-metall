<?
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arrTemplate = [
    [
        "EVENT_NAME" => "WEBCOMP_ASK_QUESTION",
        "EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
        "EMAIL_TO" => "#DEFAULT_EMAIL_FROM#",
        'SUBJECT' => "#SITE_NAME#: Сообщение из формы задать вопрос",
        'MESSAGE'=>"Информационное сообщение с сайта #SITE_NAME#<br>
------------------------------------------<br>
<br>
Вам было отправлено сообщение через форму \"Задать вопрос\"<br>
<br>
#MESSAGE#<br>
<br>
Сообщение сгенерировано автоматически."
    ],[
        "EVENT_NAME" => "WEBCOMP_CALLORDER",
        "EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
        "EMAIL_TO" => "#DEFAULT_EMAIL_FROM#",
        'SUBJECT' => "#SITE_NAME#: Сообщение из формы заказать звонок",
        'MESSAGE'=>"Информационное сообщение с сайта #SITE_NAME#<br>
------------------------------------------<br>
<br>
Вам было отправлено сообщение через форму \"Заказать звонок\"<br>
<br>
#MESSAGE#<br>
<br>
Сообщение сгенерировано автоматически."
    ],[
        "EVENT_NAME" => "WEBCOMP_ORDER_SERVICE",
        "EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
        "EMAIL_TO" => "#DEFAULT_EMAIL_FROM#",
        'SUBJECT' => "#SITE_NAME#: Сообщение из формы заказать услугу",
        'MESSAGE'=>"Информационное сообщение с сайта #SITE_NAME#<br>
------------------------------------------<br>
<br>
Вам было отправлено сообщение через форму \"Заказать услугу\"<br>
<br>
#MESSAGE#<br>
<br>
Сообщение сгенерировано автоматически."
    ],[
        "EVENT_NAME" => "WEBCOMP_ONE_CLICK_BUY",
        "EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
        "EMAIL_TO" => "#DEFAULT_EMAIL_FROM#",
        'SUBJECT' => "#SITE_NAME#: Купить в 1 клик",
        'MESSAGE'=>"Информационное сообщение с сайта #SITE_NAME#<br>
------------------------------------------<br>
<br>
Вам было отправлено сообщение через форму \"Купить в 1 клик\"<br>
<br>
#MESSAGE#<br>
<br>
Сообщение сгенерировано автоматически."
    ],[
        "EVENT_NAME" => "WEBCOMP_REVIEWS",
        "EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
        "EMAIL_TO" => "#DEFAULT_EMAIL_FROM#",
        'SUBJECT' => "#SITE_NAME#: Оставить отзыв",
        'MESSAGE'=>"Информационное сообщение с сайта #SITE_NAME#<br>
------------------------------------------<br>
<br>
Вам было отправлено сообщение через форму \"Оставить отзыв\"<br>
<br>
#MESSAGE#<br>
<br>
Сообщение сгенерировано автоматически."
    ],[
        "EVENT_NAME" => "WEBCOMP_FEEDBACK",
        "EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
        "EMAIL_TO" => "#DEFAULT_EMAIL_FROM#",
        'SUBJECT' => "#SITE_NAME#: Сообщение из формы обратной связи",
        'MESSAGE'=>"Информационное сообщение с сайта #SITE_NAME#<br>
------------------------------------------<br>
<br>
Вам было отправлено сообщение через форму обратной связи<br>
<br>
#MESSAGE#<br>
<br>
Сообщение сгенерировано автоматически."
    ],[
        "EVENT_NAME" => "WEBCOMP_NEW_ORDER",
        "EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
        "EMAIL_TO" => "#DEFAULT_EMAIL_FROM#",
        'SUBJECT' => "#SITE_NAME#: Оформление заказа",
        'MESSAGE'=>"Информационное сообщение с сайта #SITE_NAME#<br>
------------------------------------------<br>
<br>
Оформление нового заказа на сайте #SITE_NAME#<br>
<br>
#MESSAGE#<br>
<br>
Сообщение сгенерировано автоматически."
    ],[
        "EVENT_NAME" => "WEBCOMP_ORDER_PROJECT",
        "EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
        "EMAIL_TO" => "#DEFAULT_EMAIL_FROM#",
        'SUBJECT' => "#SITE_NAME#: Сообщение из формы заказать проект",
        'MESSAGE'=>"Информационное сообщение с сайта #SITE_NAME#<br>
------------------------------------------<br>
<br>
Вам было отправлено сообщение через форму \"Заказать проект\"<br>
<br>
#MESSAGE#<br>
<br>
Сообщение сгенерировано автоматически."
    ]
];


$obTemplate = new CEventMessage;

foreach ($arrTemplate as $template) {
    $arr["ACTIVE"] = "Y";
    $arr["EVENT_NAME"] = $template["EVENT_NAME"];
    $arr["LID"] = ["s1"];
    $arr["EMAIL_FROM"] = $template["EMAIL_FROM"];
    $arr["EMAIL_TO"] = $template["EMAIL_TO"];
    $arr["BCC"] = "";
    $arr["SUBJECT"] = $template["SUBJECT"];
    $arr["BODY_TYPE"] = "html";
    $arr["MESSAGE"] = $template["MESSAGE"];

    $ID = $obTemplate->Add($arr);
}


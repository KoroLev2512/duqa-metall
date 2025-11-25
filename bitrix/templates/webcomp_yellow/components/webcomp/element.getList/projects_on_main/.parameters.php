<? $arTemplateParameters = [
    "TITLE"                 => [
        "PARENT"  => "BASE",
        "NAME"    => "Заголовок",
        "TYPE"    => "STRING",
        "DEFAULT" => "Наши услуги",
    ],
    "LINK_TITLE"            => [
        "PARENT"  => "BASE",
        "NAME"    => "Заголовок ссылки",
        "TYPE"    => "STRING",
        "DEFAULT" => "Все услуги",
    ],
    "LINK_LINK"             => [
        "PARENT"  => "BASE",
        "NAME"    => "Ссылка",
        "TYPE"    => "STRING",
        "DEFAULT" => "/",
    ],
    "PAGINATION"            => [
        "PARENT"  => "BASE",
        "NAME"    => "Включить пагинацию",
        "TYPE"    => "CHECKBOX",
        "DEFAULT" => "Y",
    ],
    "AUTO_PLAY"             => [
        "PARENT"  => "BASE",
        "NAME"    => "Авто-прокрутка",
        "TYPE"    => "CHECKBOX",
        "DEFAULT" => "Y",
    ],
    "AUTO_PLAY_SPEED"       => [
        "PARENT"  => "BASE",
        "NAME"    => "Скорость прокрутки",
        "TYPE"    => "STRING",
        "DEFAULT" => "500",
    ],
    "AUTO_PLAY_DELAY_SPEED" => [
        "PARENT"  => "BASE",
        "NAME"    => "Скорость смены слайдера",
        "TYPE"    => "STRING",
        "DEFAULT" => "7000",
    ],
]; ?>
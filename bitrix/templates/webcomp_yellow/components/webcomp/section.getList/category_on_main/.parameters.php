<? $arTemplateParameters = [
    "TITLE"       => [
        "PARENT"  => "BASE",
        "NAME"    => "Заголовок",
        "TYPE"    => "STRING",
        "DEFAULT" => "Наши услуги",
    ],
    "LINK_TITLE"  => [
        "PARENT"  => "BASE",
        "NAME"    => "Заголовок ссылки",
        "TYPE"    => "STRING",
        "DEFAULT" => "Все услуги",
    ],
    "LINK_LINK"   => [
        "PARENT"  => "BASE",
        "NAME"    => "Ссылка",
        "TYPE"    => "STRING",
        "DEFAULT" => "/",
    ],
    "USE_FILTER"  => [
        "PARENT"  => "BASE",
        "NAME"    => "Использовать фильтр",
        "TYPE"    => "CHECKBOX",
        "DEFAULT" => "N",
    ],
    "FILTER_NAME" => [
        "PARENT"  => "BASE",
        "NAME"    => "Название фильтра",
        "TYPE"    => "STRING",
        "DEFAULT" => "arrFilter",
    ],
]; ?>
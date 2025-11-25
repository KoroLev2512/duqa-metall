<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;
$module_id = "webcomp.market";
$defaultOptions = Option::getDefaults($module_id);

$arRequired = [
    "TITLE" => Loc::getMessage("WEBCOMP_TAB_REQUIRED"),
    "OPTIONS" => [
//        [
//            "NAME" => "WEBCOMP_SELECT_SITE_THEME_COLOR",
//            "TITLE" => Loc::getMessage("WEBCOMP_SELECT_SITE_THEME_COLOR"),
//            "TYPE" => "SELECT_THEME",
//            "SORT" => 5,
//            "DEFAULT" => "1",
//            "VALUES" => [
//                "1" => "#f0ac0d",
//                "2" => "#f35c50",
//                "3" => "#0aa360",
//                "4" => "#2196e0",
//                "5" => "#7a4cd9",
//            ],
//            "DESCRIPTION" => [],
//            "STYLE" => [
//                "CLASS" => "WEBCOMP_SELECT_SITE_THEME_COLOR",
//            ],
//        ],
        [
            "NAME" => "WEBCOMP_STRING_SLOGAN",
            "TITLE" => Loc::getMessage("WEBCOMP_STRING_SLOGAN"),
            "TYPE" => "STRING",
            "SORT" => 10,
            "DEFAULT" => Loc::getMessage("DEFAULT_WEBCOMP_STRING_SLOGAN"),
            "STYLE" => [
                "CLASS" => "WEBCOMP_STRING_SLOGAN",
                "WIDTH" => "SMALL",
            ],
        ],
        [
            "NAME" => "WEBCOMP_FILE_SITE_FAVICON",
            "TITLE" => Loc::getMessage("WEBCOMP_FILE_SITE_FAVICON"),
            "TYPE" => "FILE",
            "SORT" => 20,
            "DEFAULT" => $defaultOptions["WEBCOMP_FILE_SITE_FAVICON"] ?: "",
            "WITH_DESCRIPTION" => "N",
            "MULTIPLE" => "N",
            "DESCRIPTION" => [],
            "STYLE" => [
                "CLASS" => "WEBCOMP_FILE_SITE_FAVICON",
            ],
        ],
        [
            "NAME" => "WEBCOMP_FILE_SITE_LOGO_DARK",
            "TITLE" => Loc::getMessage("WEBCOMP_FILE_SITE_LOGO_DARK"),
            "TYPE" => "FILE",
            "SORT" => 30,
            "DEFAULT" => $defaultOptions["WEBCOMP_FILE_SITE_LOGO_DARK"] ?: "",
            "WITH_DESCRIPTION" => "N",
            "MULTIPLE" => "N",
            "DESCRIPTION" => [],
            "STYLE" => [
                "CLASS" => "WEBCOMP_FILE_SITE_LOGO_DARK",
            ],
        ],
        [
            "NAME" => "WEBCOMP_FILE_SITE_LOGO_LIGHT",
            "TITLE" => Loc::getMessage("WEBCOMP_FILE_SITE_LOGO_LIGHT"),
            "TYPE" => "FILE",
            "SORT" => 40,
            "DEFAULT" => $defaultOptions["WEBCOMP_FILE_SITE_LOGO_LIGHT"] ?: "",
            "WITH_DESCRIPTION" => "N",
            "MULTIPLE" => "N",
            "DESCRIPTION" => [],
            "STYLE" => [
                "CLASS" => "WEBCOMP_FILE_SITE_LOGO_LIGHT",
            ],
        ],
        [
            "NAME" => "WEBCOMP_CHECKBOX_LK",
            "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_LK"),
            "TYPE" => "CHECKBOX",
            "SORT" => 50,
            "DEFAULT" => "Y",
            "DESCRIPTION" => [
                "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_LK_DESCRIPTION"),
            ],
            "STYLE" => [
                "CLASS" => "WEBCOMP_CHECKBOX_LK",
            ],
        ],
        [
            "NAME" => "WEBCOMP_CHECKBOX_E-SHOP",
            "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_E-SHOP"),
            "TYPE" => "CHECKBOX",
            "SORT" => 60,
            "DEFAULT" => "Y",
            "DESCRIPTION" => [
                "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_E-SHOP_DESCRIPTION"),
            ],
            "STYLE" => [
                "CLASS" => "WEBCOMP_CHECKBOX_E-SHOP",
            ],
        ],
        [
            "NAME" => "WEBCOMP_CHECKBOX_SERVICES_WITH_CATEGORY",
            "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_SERVICES_WITH_CATEGORY"),
            "TYPE" => "CHECKBOX",
            "SORT" => 70,
            "DEFAULT" => "Y",
            "DESCRIPTION" => [
                "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_SERVICES_WITH_CATEGORY_DESCRIPTION"),
            ],
            "STYLE" => [
                "CLASS" => "WEBCOMP_CHECKBOX_SERVICES_WITH_CATEGORY",
            ],
        ],
        [
            "TITLE" => Loc::getMessage("WEBCOMP_HEADING_POLICY") ?: "",
            "TYPE" => "HEADING",
            "SORT" => 80,
            "STYLE" => [
                "CLASS" => "WEBCOMP_HEADING_POLICY",
                "BOLD" => "Y"
            ],
        ],
        [
            "NAME" => "WEBCOMP_CHECKBOX_USE_POLICY",
            "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_USE_POLICY"),
            "TYPE" => "CHECKBOX",
            "SORT" => 90,
            "DEFAULT" => "Y",
            "DESCRIPTION" => [
                "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_USE_POLICY_DESCRIPTION"),
                "LINK" => [
                    "TEXT" => Loc::getMessage("WEBCOMP_MORE_TEXT"),
                    "HREF" => Loc::getMessage("WEBCOMP_CHECKBOX_USE_POLICY_LINK"),
                ],
            ],
            "STYLE" => [
                "CLASS" => "WEBCOMP_CHECKBOX_USE_POLICY",
            ],
        ],
        [
            "NAME" => "WEBCOMP_CHECKBOX_DEFAULT_CHECK",
            "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_DEFAULT_CHECK"),
            "TYPE" => "CHECKBOX",
            "SORT" => 100,
            "DEFAULT" => "Y",
            "DESCRIPTION" => [
                "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_DEFAULT_CHECK_DESCRIPTION"),
            ],
            "STYLE" => [
                "CLASS" => "WEBCOMP_CHECKBOX_DEFAULT_CHECK",
            ],
        ],
        [
            "NAME" => "WEBCOMP_EDITOR_FORM_POLICY_TEXT",
            "TITLE" => Loc::getMessage("WEBCOMP_EDITOR_FORM_POLICY_TEXT"),
            "TYPE" => "EDITOR",
            "NOT_USE_HTML" => "N",
            "FILE" => "policy_form_text.php",
            "SORT" => 110,
            "DESCRIPTION" => [],
            "STYLE" => [
                "CLASS" => "WEBCOMP_EDITOR_FORM_POLICY_TEXT",
            ],
        ],
        [
            "NAME" => "WEBCOMP_EDITOR_PAGE_POLICY_TEXT",
            "TITLE" => Loc::getMessage("WEBCOMP_EDITOR_PAGE_POLICY_TEXT"),
            "TYPE" => "EDITOR",
            "NOT_USE_HTML" => "N",
            "FILE" => "policy_page_text.php",
            "SORT" => 120,
            "DESCRIPTION" => [],
            "STYLE" => [
                "CLASS" => "WEBCOMP_EDITOR_PAGE_POLICY_TEXT",
            ],
        ],
        [
            "TITLE" => Loc::getMessage("WEBCOMP_HEADING_VALIDATION_FORM"),
            "TYPE" => "HEADING",
            "SORT" => 130,
            "STYLE" => [
                "CLASS" => "WEBCOMP_HEADING_VALIDATION_FORM",
                "BOLD" => "Y"
            ],
        ],
        [
            "NAME" => "WEBCOMP_STRING_PHONE_MASK",
            "TITLE" => Loc::getMessage("WEBCOMP_STRING_PHONE_MASK"),
            "TYPE" => "STRING",
            "SORT" => 140,
            "DEFAULT" => "+7 (999) 999-99-99",
            "DESCRIPTION" => [],
            "STYLE" => [
                "CLASS" => "WEBCOMP_STRING_PHONE_MASK",
            ],
        ],
        [
            "TITLE" => Loc::getMessage("WEBCOMP_HEADING_OPTIMIZATION") ?: "",
            "TYPE" => "HEADING",
            "SORT" => 150,
            "STYLE" => [
                "CLASS" => "WEBCOMP_HEADING_OPTIMIZATION",
                "BOLD" => "Y"
            ],
        ],
        [
            "NAME" => "WEBCOMP_CHECKBOX_PAGE_SPEED_OPTIMIZATION",
            "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_PAGE_SPEED_OPTIMIZATION"),
            "TYPE" => "CHECKBOX",
            "SORT" => 160,
            "DEFAULT" => "Y",
            "DESCRIPTION" => [
                "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_PAGE_SPEED_OPTIMIZATION_DESCRIPTION"),
            ],
            "STYLE" => [
                "CLASS" => "WEBCOMP_CHECKBOX_PAGE_SPEED_OPTIMIZATION",
            ],
        ],
        [
            "TITLE" => Loc::getMessage("WEBCOMP_HEADING_CATALOG") ?: "",
            "TYPE" => "HEADING",
            "SORT" => 170,
            "STYLE" => [
                "CLASS" => "WEBCOMP_HEADING_CATALOG",
                "BOLD" => "Y"
            ],
        ],
        [
            "NAME" => "WEBCOMP_STRING_DECIMAL",
            "TITLE" => Loc::getMessage("WEBCOMP_STRING_DECIMAL"),
            "TYPE" => "STRING",
            "SORT" => 180,
            "DEFAULT" => 0,
            "DESCRIPTION" => [],
            "STYLE" => [
                "CLASS" => "WEBCOMP_STRING_DECIMAL",
                "WIDTH" => "MINI",
            ],
        ],
        [
            "NAME" => "WEBCOMP_STRING_DECIMAL_POINT",
            "TITLE" => Loc::getMessage("WEBCOMP_STRING_DECIMAL_POINT"),
            "TYPE" => "STRING",
            "SORT" => 190,
            "DEFAULT" => ".",
            "DESCRIPTION" => [],
            "STYLE" => [
                "CLASS" => "WEBCOMP_STRING_DECIMAL_POINT",
                "WIDTH" => "MINI",
            ],
        ],
        [
            "NAME" => "WEBCOMP_STRING_THOUSANDTH_SEPORATOR",
            "TITLE" => Loc::getMessage("WEBCOMP_STRING_THOUSANDTH_SEPORATOR"),
            "TYPE" => "STRING",
            "SORT" => 200,
            "DEFAULT" => " ",
            "DESCRIPTION" => [],
            "STYLE" => [
                "CLASS" => "WEBCOMP_STRING_THOUSANDTH_SEPORATOR",
                "WIDTH" => "MINI",
            ],
        ],

        [
            "TITLE" => Loc::getMessage("WEBCOMP_HEADING_SECTIONS") ?: "",
            "TYPE" => "HEADING",
            "SORT" => 210,
            "STYLE" => [
                "CLASS" => "WEBCOMP_HEADING_SECTIONS",
                "BOLD" => "Y"
            ],
        ],
        [
            "NAME" => "WEBCOMP_CHECKBOX_SECTIONS",
            "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_SECTIONS"),
            "TYPE" => "SECTIONS",
            "SORT" => 220,
            "DEFAULT" => "Y",
            "DESCRIPTION" => [
                "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_SECTIONS_DESCRIPTION"),
            ],
            "STYLE" => [
                "CLASS" => "WEBCOMP_CHECKBOX_SECTIONS",
                "DISABLED" => "N",
            ],
            "VALUES" => [
                0 => [
                    "NAME" => Loc::getMessage("WEBCOMP_MARKET_ADVANTAGES"),
                    "ID" => 'advantages',
                    "ORDER" => 0,
                    "CHECKED" => "checked"
                ],
                1 => [
                    "NAME" => Loc::getMessage("WEBCOMP_MARKET_SERVICES"),
                    "ID" => 'services',
                    "ORDER" => 1,
                    "CHECKED" => "checked"
                ],
                2 => [
                    "NAME" => Loc::getMessage("WEBCOMP_MARKET_PROJECTS"),
                    "ID" => 'projects',
                    "ORDER" => 2,
                    "CHECKED" => "checked"
                ],
                3 => [
                    "NAME" => Loc::getMessage("WEBCOMP_MARKET_POPULAR_WORKS"),
                    "ID" => 'popular',
                    "ORDER" => 3,
                    "CHECKED" => "checked"
                ],
                4 => [
                    "NAME" => Loc::getMessage("WEBCOMP_MARKET_GOOD_OFFERS"),
                    "ID" => 'reccomended',
                    "ORDER" => 4,
                    "CHECKED" => "checked"
                ],
                5 => [
                    "NAME" => Loc::getMessage("WEBCOMP_MARKET_PROMO_BANNER"),
                    "ID" => 'promo',
                    "ORDER" => 5,
                    "CHECKED" => "checked"
                ],
                6 => [
                    "NAME" => Loc::getMessage("WEBCOMP_MARKET_POPULAR_CATEGORY"),
                    "ID" => 'categories',
                    "ORDER" => 6,
                    "CHECKED" => "checked"
                ],
                7 => [
                    "NAME" => Loc::getMessage("WEBCOMP_MARKET_PROMOTION"),
                    "ID" => 'actions',
                    "ORDER" => 7,
                    "CHECKED" => "checked"
                ],
                8 => [
                    "NAME" => Loc::getMessage("WEBCOMP_MARKET_NEWS"),
                    "ID" => 'news',
                    "ORDER" => 8,
                    "CHECKED" => "checked"
                ],
                9 => [
                    "NAME" => Loc::getMessage("WEBCOMP_MARKET_ABOUT"),
                    "ID" => 'about',
                    "ORDER" => 9,
                    "CHECKED" => "checked"
                ],
                10 => [
                    "NAME" => Loc::getMessage("WEBCOMP_MARKET_REVIEWS"),
                    "ID" => 'reviews',
                    "ORDER" => 10,
                    "CHECKED" => "checked"
                ],
                11 => [
                    "NAME" => Loc::getMessage("WEBCOMP_MARKET_BRANDS"),
                    "ID" => 'brands',
                    "ORDER" => 11,
                    "CHECKED" => "checked"
                ]
            ],
        ],
        [
            "TITLE" => Loc::getMessage("WEBCOMP_HEADING_SEO") ?: "",
            "TYPE" => "HEADING",
            "SORT" => 300,
            "STYLE" => [
                "CLASS" => "WEBCOMP_HEADING_SEO",
                "BOLD" => "Y"
            ],
        ],
        [
            "NAME" => "WEBCOMP_SEO_YANDEX_CHECKBOX",
            "TITLE" => Loc::getMessage("WEBCOMP_SEO_YANDEX_CHECKBOX"),
            "TYPE" => "CHECKBOX",
            "SORT" => 310,
            "DEFAULT" => "N",
            "DESCRIPTION" => [
                "TITLE" => Loc::getMessage("WEBCOMP_SEO_YANDEX_CHECKBOX_DESCRIPTION"),
            ],
            "STYLE" => [
                "CLASS" => "seo-check",
            ],
        ],
        [
            "NAME" => "WEBCOMP_SEO_YANDEX_CODE",
            "TITLE" => Loc::getMessage("WEBCOMP_SEO_YANDEX_CODE"),
            "TYPE" => "EDITOR",
            "NOT_USE_HTML" => "N",
            "FILE" => "yandex_metrica.php",
            "SORT" => 320,
            "DESCRIPTION" => [],
            "STYLE" => [
                "CLASS" => "seo-check__hidden hidden",
            ],
        ],
        [
            "NAME" => "WEBCOMP_SEO_YANDEX_COUNT",
            "TITLE" => Loc::getMessage("WEBCOMP_SEO_YANDEX_COUNT"),
            "TYPE" => "STRING",
            "SORT" => 330,
            "DEFAULT" => "",
            "DESCRIPTION" => [],
            "STYLE" => [
                "CLASS" => "seo-check__hidden hidden",
            ],
            "INFO" => Loc::getMessage("WEBCOMP_MARKET_SEO_TARGET_TEXT"),
        ],
    ],
];
$arMain = [
    "TITLE" => Loc::getMessage("WEBCOMP_TAB_MAIN"),
    "OPTIONS" => [
        [
            "NAME" => "WEBCOMP_VIEW_FOOTER",
            "TITLE" => "Вид подвала",
            "TYPE" => "VIEW",
            "SORT" => 10,
            "DEFAULT" => "v1",
            "VALUES" => [
                "0" => [
                    "TITLE" => "Темный информационный",
                    "VALUE" => "v1",
                    "IMAGE" => "/bitrix/images/webcomp.market/views/footer/v1.png"
                ],
                "1" => [
                    "TITLE" => "Темный компактный",
                    "VALUE" => "v2",
                    "IMAGE" => "/bitrix/images/webcomp.market/views/footer/v2.png"
                ],
            ],
            "DESCRIPTION" => [],
            "STYLE" => [
                "CLASS" => "WEBCOMP_VIEW_FOOTER",
                "MAX" => 1,
            ],
        ]
    ],
];
$arGoogleCAPTCHA = [
    "TITLE" => "Google reCaptcha",
    "OPTIONS" => [
        [
            "NAME" => "WEBCOMP_RECAPTCHA_CHECKBOX",
            "TITLE" => Loc::getMessage("WEBCOMP_RECAPTCHA_CHECKBOX"),
            "TYPE" => "CHECKBOX",
            "SORT" => 20,
            "DEFAULT" => "N",
            "DESCRIPTION" => [
                "TITLE" => Loc::getMessage("WEBCOMP_RECAPTCHA_CHECKBOX"),
            ],
            "STYLE" => [
                "CLASS" => "WEBCOMP_RECAPTCHA_CHECKBOX",
            ],
        ],
        [
            "NAME" => "WEBCOMP_RECAPTCHA_PUBLIC_CODE",
            "TITLE" => Loc::getMessage("WEBCOMP_RECAPTCHA_PUBLIC_CODE"),
            "TYPE" => "STRING",
            "SORT" => 30,
            "DEFAULT" => "",
            "DESCRIPTION" => [],
            "STYLE" => [
                "CLASS" => "WEBCOMP_RECAPTCHA_PUBLIC_CODE",
                "WIDTH" => "MEDIUM",
            ],
        ],
        [
            "NAME" => "WEBCOMP_RECAPTCHA_SECRET_CODE",
            "TITLE" => Loc::getMessage("WEBCOMP_RECAPTCHA_SECRET_CODE"),
            "TYPE" => "STRING",
            "SORT" => 40,
            "DEFAULT" => "",
            "DESCRIPTION" => [],
            "STYLE" => [
                "CLASS" => "WEBCOMP_RECAPTCHA_SECRET_CODE",
                "WIDTH" => "MEDIUM",
            ],
        ],
        [
            "NAME" => "WEBCOMP_RECAPTCHA_SCORE",
            "TITLE" => Loc::getMessage("WEBCOMP_RECAPTCHA_SCORE"),
            "TYPE" => "SELECT",
            "SORT" => 50,
            "DEFAULT" => "0.5",
            "VALUES" => [
                "0.1" => "0.1",
                "0.2" => "0.2",
                "0.3" => "0.3",
                "0.4" => "0.4",
                "0.5" => "0.5",
                "0.6" => "0.6",
                "0.7" => "0.7",
                "0.8" => "0.8",
                "0.9" => "0.9",
            ],
            "DESCRIPTION" => [],
            "STYLE" => [
                "CLASS" => "WEBCOMP_RECAPTCHA_SCORE",
                "HEIGHT" => 1,
            ],
        ]
    ]
];

return [
    [
        "DIV" => "webcomp_tab",
        "TAB" => Loc::getMessage("WEBCOMP_TAB_NAME"),
        "TITLE" => "",
        "TABS" => [
           "REQUIRED" => $arRequired,
           "MAIN" => $arMain,
           "GOOGLE_RECAPTCHA" => $arGoogleCAPTCHA,
        ]
    ]
];

$arTabs = [
        [
        "DIV" => "webcomp_tab",
        "TAB" => Loc::getMessage("WEBCOMP_TAB_NAME"),
        "TITLE" => "",
        "OPTIONS" => [
            // Тип строка
            /*
            [
                "NAME" => "webcomp_slogan",
                "TITLE" => "Слоган на сайте",
                "TYPE" => "STRING",
                "SORT" => 1,
                "DEFAULT" => $defaultOptions["webcomp_slogan"] ?: "",
                "DESCRIPTION" => [
                ],
                "STYLE" => [
                    "CLASS" => "dop_class",
                    "WIDTH" => 30,
                    "HEIGHT" => 10,
                    "DATA" => [
                        "ID" => "10",
                        "COUNT" => "5"
                    ],
                    "DISABLED" => "N",
                ],
            ],

            // Тип заголовок
            [
                "TITLE" => "Разделитель",
                "TYPE" => "HEADING",
                "SORT" => 2,
                "STYLE" => [
                    "CLASS" => "dop_class_2",
                ],
            ],

            // Тип Информация
            [
                "TITLE" => "Предупреждение или информация о каком то блоке, можно использовать для вывода каких то предупреждений пользователю<br>
                так же можно использовать html, для более красивого вывода",
                "TYPE" => "INFO",
                "SORT" => 3,
                "STYLE" => [
                    "CLASS" => "dop_class_3",
                ],
            ],

            // Тип чекбокс
            [
                "NAME" => "webcomp_theme_check",
                "TITLE" => "Отображать переключатель тем",
                "TYPE" => "CHECKBOX",
                "SORT" => 4,
                "DEFAULT" => $defaultOptions["webcomp_theme_check"] ?: "N",
                "DESCRIPTION" => [
                    "TITLE" => "Данная опция отвечает за показ панели переключения тем в пользовательском режиме",
                    "LINK" => [
                        "TEXT" => "Подробнее",
                        "HREF" => "https://web-comp.ru",
                        "NEW_WINDOW" => "Y",
                    ],
                ],
                "STYLE" => [
                    "CLASS" => "dop_class_check",
                    "DATA" => [
                        "ID" => "10",
                        "COUNT" => "5"
                    ],
                    "DISABLED" => "N",
                ],
            ],

            // Тип select
            [
                "NAME" => "webcomp_theme_color",
                "TITLE" => "Цветовая гамма сайта",
                "TYPE" => "SELECT",
                "SORT" => 5,
                "DEFAULT" => $defaultOptions["webcomp_theme_color"] ?: "default",
                "VALUES" => [
                    "default" => "Не выбрано",
                    "orange" => "Оранжевая",
                    "red" => "Красная",
                    "black" => "Черная",
                    "white" => "Белая",
                    "green" => "Зеленая",
                ],
                "DESCRIPTION" => [
                    // "TITLE" => "Тут выводится описание подсказки",
                    // "LINK" => [
                    // 	"TEXT" => "Текст ссылки",
                    // 	"HREF" => "https://web-comp.ru",
                    // 	"NEW_WINDOW" => "Y",
                    // ],
                ],
                "STYLE" => [
                    "CLASS" => "dop_class_check",
                    "HEIGHT" => 1,
                    "DATA" => [
                        "ID" => "10",
                        "COUNT" => "5"
                    ],
                    "DISABLED" => "N",
                ],
            ],

            // Тип Строка множественная
            [
                "NAME" => "webcomp_phones",
                "TITLE" => "Телефоны",
                "TYPE" => "MULTIPLE_STRING",
                "SORT" => 6,
                "DEFAULT" => $defaultOptions["webcomp_phones"] ?: "",
                "DESCRIPTION" => [
                ],
                "STYLE" => [
                    "CLASS" => "dop_class",
                    "WIDTH" => 30,
                    "HEIGHT" => 10,
                    "DATA" => [
                        "ID" => "10",
                        "COUNT" => "5"
                    ],
                    "DISABLED" => "N",
                ],
            ],

            // Тип select множественная
            [
                "NAME" => "webcomp_form_fields",
                "TITLE" => "Поля для формы",
                "TYPE" => "MULTIPLE_SELECT",
                "SORT" => 7,
                "DEFAULT" => $defaultOptions["webcomp_form_fields"] ?: "",
                "VALUES" => [
                    "name" => "Имя",
                    "phone" => "Телефон",
                    "email" => "Email",
                    "zip" => "Индекс",
                    "comment" => "Комментарий",
                ],
                "DESCRIPTION" => [
                ],
                "STYLE" => [
                    "CLASS" => "dop_class",
                    "HEIGHT" => 4,
                    "DATA" => [
                        "ID" => "10",
                        "COUNT" => "5"
                    ],
                    "DISABLED" => "N",
                ],
            ],

            // Тип редактор
            [
                "NAME" => "webcomp_policy_editor",
                "TITLE" => "Текст политики",
                "TYPE" => "EDITOR",
                "FILE" => "policy_text.php",
                "SORT" => 8,
                "DEFAULT" => $defaultOptions["webcomp_policy_editor"] ?: "",
                "DESCRIPTION" => [
                ],
                "STYLE" => [
                    "CLASS" => "dop_class",
                    "DATA" => [
                        "ID" => "10",
                        "COUNT" => "5"
                    ],
                    "DISABLED" => "N",
                ],
            ],

            // Тип файл
            [
                "NAME" => "webcomp_site_logo",
                "TITLE" => "Логотип сайта",
                "TYPE" => "FILE",
                "SORT" => 9,
                "DEFAULT" => $defaultOptions["webcomp_site_logo"] ?: "",
                "WITH_DESCRIPTION" => "N",
                "MULTIPLE" => "N",
                "DESCRIPTION" => [
                ],
                "STYLE" => [
                    "CLASS" => "dop_class",
                    "DATA" => [
                        "ID" => "10",
                        "COUNT" => "5"
                    ],
                    "DISABLED" => "N",
                ],
            ],

            // Тип файл
            [
                "NAME" => "webcomp_site_favicon",
                "TITLE" => "Favicon сайта",
                "TYPE" => "FILE",
                "SORT" => 10,
                "DEFAULT" => $defaultOptions["webcomp_site_favicon"] ?: "",
                "WITH_DESCRIPTION" => "N",
                "MULTIPLE" => "N",
                "DESCRIPTION" => [
                ],
                "STYLE" => [
                    "CLASS" => "dop_class",
                    "DATA" => [
                        "ID" => "10",
                        "COUNT" => "5"
                    ],
                    "DISABLED" => "N",
                ],
            ],
            */
            // CurrentOptions
            [
                "TITLE" => Loc::getMessage("WEBCOMP_HEADING_MAIN") ?: "",
                "TYPE" => "HEADING",
                "SORT" => 100,
                "STYLE" => [
                    "CLASS" => "WEBCOMP_HEADING_MAIN",
                ],
            ],
            [
                "NAME" => "WEBCOMP_STRING_SLOGAN",
                "TITLE" => Loc::getMessage("WEBCOMP_STRING_SLOGAN"),
                "TYPE" => "STRING",
                "SORT" => 101,
                "DEFAULT" => $defaultOptions["WEBCOMP_STRING_SLOGAN"] ?: "",
                "DESCRIPTION" => [],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_STRING_SLOGAN",
                    "WIDTH" => 30,
                    "HEIGHT" => 10,
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "NAME" => "WEBCOMP_FILE_SITE_FAVICON",
                "TITLE" => Loc::getMessage("WEBCOMP_FILE_SITE_FAVICON"),
                "TYPE" => "FILE",
                "SORT" => 102,
                "DEFAULT" => $defaultOptions["WEBCOMP_FILE_SITE_FAVICON"] ?: "",
                "WITH_DESCRIPTION" => "N",
                "MULTIPLE" => "N",
                "DESCRIPTION" => [
                ],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_FILE_SITE_FAVICON",
                    "DATA" => [
                        "ID" => "10",
                        "COUNT" => "5"
                    ],
                    "DISABLED" => "N",
                ],
            ],
            [
                "NAME" => "WEBCOMP_FILE_SITE_LOGO_DARK",
                "TITLE" => Loc::getMessage("WEBCOMP_FILE_SITE_LOGO_DARK"),
                "TYPE" => "FILE",
                "SORT" => 103,
                "DEFAULT" => $defaultOptions["WEBCOMP_FILE_SITE_LOGO_DARK"] ?: "",
                "WITH_DESCRIPTION" => "N",
                "MULTIPLE" => "N",
                "DESCRIPTION" => [],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_FILE_SITE_LOGO_DARK",
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "NAME" => "WEBCOMP_FILE_SITE_LOGO_LIGHT",
                "TITLE" => Loc::getMessage("WEBCOMP_FILE_SITE_LOGO_LIGHT"),
                "TYPE" => "FILE",
                "SORT" => 104,
                "DEFAULT" => $defaultOptions["WEBCOMP_FILE_SITE_LOGO_LIGHT"] ?: "",
                "WITH_DESCRIPTION" => "N",
                "MULTIPLE" => "N",
                "DESCRIPTION" => [],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_FILE_SITE_LOGO_LIGHT",
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "NAME" => "WEBCOMP_CHECKBOX_LK",
                "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_LK"),
                "TYPE" => "CHECKBOX",
                "SORT" => 105,
                "DEFAULT" => $defaultOptions["WEBCOMP_CHECKBOX_DEFAULT_CHECK"] ?: "Y",
                "DESCRIPTION" => [
                    "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_LK_DESCRIPTION"),
                ],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_CHECKBOX_LK",
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "NAME" => "WEBCOMP_CHECKBOX_E-SHOP",
                "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_E-SHOP"),
                "TYPE" => "CHECKBOX",
                "SORT" => 106,
                "DEFAULT" => $defaultOptions["WEBCOMP_CHECKBOX_DEFAULT_CHECK"] ?: "Y",
                "DESCRIPTION" => [
                    "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_E-SHOP_DESCRIPTION"),
                ],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_CHECKBOX_E-SHOP",
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "NAME" => "WEBCOMP_CHECKBOX_SERVICES_WITH_CATEGORY",
                "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_SERVICES_WITH_CATEGORY"),
                "TYPE" => "CHECKBOX",
                "SORT" => 106,
                "DEFAULT" => $defaultOptions["WEBCOMP_CHECKBOX_DEFAULT_CHECK"] ?: "Y",
                "DESCRIPTION" => [
                    "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_SERVICES_WITH_CATEGORY_DESCRIPTION"),
                ],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_CHECKBOX_SERVICES_WITH_CATEGORY",
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "TITLE" => Loc::getMessage("WEBCOMP_HEADING_POLICY") ?: "",
                "TYPE" => "HEADING",
                "SORT" => 200,
                "STYLE" => [
                    "CLASS" => "WEBCOMP_HEADING_POLICY",
                ],
            ],
            [
                "NAME" => "WEBCOMP_CHECKBOX_USE_POLICY",
                "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_USE_POLICY"),
                "TYPE" => "CHECKBOX",
                "SORT" => 201,
                "DEFAULT" => $defaultOptions["WEBCOMP_CHECKBOX_USE_POLICY"] ?: "Y",
                "DESCRIPTION" => [
                    "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_USE_POLICY_DESCRIPTION"),
                    "LINK" => [
                        "TEXT" => Loc::getMessage("WEBCOMP_MORE_TEXT"),
                        "HREF" => Loc::getMessage("WEBCOMP_CHECKBOX_USE_POLICY_LINK"),
                        "NEW_WINDOW" => "Y",
                    ],
                ],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_CHECKBOX_USE_POLICY",
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "NAME" => "WEBCOMP_CHECKBOX_DEFAULT_CHECK",
                "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_DEFAULT_CHECK"),
                "TYPE" => "CHECKBOX",
                "SORT" => 202,
                "DEFAULT" => $defaultOptions["WEBCOMP_CHECKBOX_DEFAULT_CHECK"] ?: "Y",
                "DESCRIPTION" => [
                    "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_DEFAULT_CHECK_DESCRIPTION"),
                ],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_CHECKBOX_DEFAULT_CHECK",
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "NAME" => "WEBCOMP_EDITOR_FORM_POLICY_TEXT",
                "TITLE" => Loc::getMessage("WEBCOMP_EDITOR_FORM_POLICY_TEXT"),
                "TYPE" => "EDITOR",
                "NOT_USE_HTML" => "N",
                "FILE" => "policy_form_text.php",
                "SORT" => 203,
                "DESCRIPTION" => [],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_EDITOR_FORM_POLICY_TEXT",
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "NAME" => "WEBCOMP_EDITOR_PAGE_POLICY_TEXT",
                "TITLE" => Loc::getMessage("WEBCOMP_EDITOR_PAGE_POLICY_TEXT"),
                "TYPE" => "EDITOR",
                "NOT_USE_HTML" => "N",
                "FILE" => "policy_page_text.php",
                "SORT" => 204,
                "DESCRIPTION" => [],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_EDITOR_PAGE_POLICY_TEXT",
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "TITLE" => Loc::getMessage("WEBCOMP_HEADING_VALIDATION_FORM"),
                "TYPE" => "HEADING",
                "SORT" => 300,
                "STYLE" => [
                    "CLASS" => "WEBCOMP_HEADING_VALIDATION_FORM",
                ],
            ],
            [
                "NAME" => "WEBCOMP_STRING_PHONE_MASK",
                "TITLE" => Loc::getMessage("WEBCOMP_STRING_PHONE_MASK"),
                "TYPE" => "STRING",
                "SORT" => 301,
                "DEFAULT" => $defaultOptions["WEBCOMP_STRING_PHONE_MASK"],
                "DESCRIPTION" => [],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_STRING_PHONE_MASK",
                    "WIDTH" => 30,
                    "HEIGHT" => 10,
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "TITLE" => Loc::getMessage("WEBCOMP_HEADING_OPTIMIZATION") ?: "",
                "TYPE" => "HEADING",
                "SORT" => 400,
                "STYLE" => [
                    "CLASS" => "WEBCOMP_HEADING_OPTIMIZATION",
                ],
            ],
            [
                "NAME" => "WEBCOMP_CHECKBOX_PAGE_SPEED_OPTIMIZATION",
                "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_PAGE_SPEED_OPTIMIZATION"),
                "TYPE" => "CHECKBOX",
                "SORT" => 401,
                "DEFAULT" => $defaultOptions["WEBCOMP_CHECKBOX_DEFAULT_CHECK"] ?: "Y",
                "DESCRIPTION" => [
                    "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_PAGE_SPEED_OPTIMIZATION_DESCRIPTION"),
                ],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_CHECKBOX_PAGE_SPEED_OPTIMIZATION",
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "TITLE" => Loc::getMessage("WEBCOMP_HEADING_CATALOG") ?: "",
                "TYPE" => "HEADING",
                "SORT" => 500,
                "STYLE" => [
                    "CLASS" => "WEBCOMP_HEADING_CATALOG",
                ],
            ],
            [
                "NAME" => "WEBCOMP_STRING_DECIMAL",
                "TITLE" => Loc::getMessage("WEBCOMP_STRING_DECIMAL"),
                "TYPE" => "STRING",
                "SORT" => 501,
                "DEFAULT" => $defaultOptions["WEBCOMP_STRING_DECIMAL"] ?: 0,
                "DESCRIPTION" => [],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_STRING_DECIMAL",
                    "WIDTH" => 1,
                    "HEIGHT" => 10,
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "NAME" => "WEBCOMP_STRING_DECIMAL_POINT",
                "TITLE" => Loc::getMessage("WEBCOMP_STRING_DECIMAL_POINT"),
                "TYPE" => "STRING",
                "SORT" => 502,
                "DEFAULT" => $defaultOptions["WEBCOMP_STRING_DECIMAL_POINT"] ?: ".",
                "DESCRIPTION" => [],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_STRING_DECIMAL_POINT",
                    "WIDTH" => 1,
                    "HEIGHT" => 10,
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "NAME" => "WEBCOMP_STRING_THOUSANDTH_SEPORATOR",
                "TITLE" => Loc::getMessage("WEBCOMP_STRING_THOUSANDTH_SEPORATOR"),
                "TYPE" => "STRING",
                "SORT" => 503,
                "DEFAULT" => $defaultOptions["WEBCOMP_STRING_DECIMAL_POINT"] ?: " ",
                "DESCRIPTION" => [],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_STRING_THOUSANDTH_SEPORATOR",
                    "WIDTH" => 1,
                    "HEIGHT" => 10,
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "TITLE" => Loc::getMessage("WEBCOMP_HEADING_SECTIONS") ?: "",
                "TYPE" => "HEADING",
                "SORT" => 600,
                "STYLE" => [
                    "CLASS" => "WEBCOMP_HEADING_SECTIONS",
                ],
            ],
            [
                "NAME" => "WEBCOMP_CHECKBOX_SECTIONS",
                "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_SECTIONS"),
                "TYPE" => "SECTIONS",
                "SORT" => 601,
                "DEFAULT" => $defaultOptions["WEBCOMP_CHECKBOX_SECTIONS"] ?: "Y",
                "DESCRIPTION" => [
                    "TITLE" => Loc::getMessage("WEBCOMP_CHECKBOX_SECTIONS_DESCRIPTION"),
                ],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_CHECKBOX_SECTIONS",
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
                "VALUES" => [
                    0 => [
                        "NAME" => Loc::getMessage("WEBCOMP_MARKET_ADVANTAGES"),
                        "ID" => 'advantages',
                        "ORDER" => 0,
                        "CHECKED" => "checked"
                    ],
                    1 => [
                        "NAME" => Loc::getMessage("WEBCOMP_MARKET_SERVICES"),
                        "ID" => 'services',
                        "ORDER" => 1,
                        "CHECKED" => "checked"
                    ],
                    2 => [
                        "NAME" => Loc::getMessage("WEBCOMP_MARKET_PROJECTS"),
                        "ID" => 'projects',
                        "ORDER" => 2,
                        "CHECKED" => "checked"
                    ],
                    3 => [
                        "NAME" => Loc::getMessage("WEBCOMP_MARKET_POPULAR_WORKS"),
                        "ID" => 'popular',
                        "ORDER" => 3,
                        "CHECKED" => "checked"
                    ],
                    4 => [
                        "NAME" => Loc::getMessage("WEBCOMP_MARKET_GOOD_OFFERS"),
                        "ID" => 'reccomended',
                        "ORDER" => 4,
                        "CHECKED" => "checked"
                    ],
                    5 => [
                        "NAME" => Loc::getMessage("WEBCOMP_MARKET_PROMO_BANNER"),
                        "ID" => 'promo',
                        "ORDER" => 5,
                        "CHECKED" => "checked"
                    ],
                    6 => [
                        "NAME" => Loc::getMessage("WEBCOMP_MARKET_POPULAR_CATEGORY"),
                        "ID" => 'categories',
                        "ORDER" => 6,
                        "CHECKED" => "checked"
                    ],
                    7 => [
                        "NAME" => Loc::getMessage("WEBCOMP_MARKET_PROMOTION"),
                        "ID" => 'actions',
                        "ORDER" => 7,
                        "CHECKED" => "checked"
                    ],
                    8 => [
                        "NAME" => Loc::getMessage("WEBCOMP_MARKET_NEWS"),
                        "ID" => 'news',
                        "ORDER" => 8,
                        "CHECKED" => "checked"
                    ],
                    9 => [
                        "NAME" => Loc::getMessage("WEBCOMP_MARKET_ABOUT"),
                        "ID" => 'about',
                        "ORDER" => 9,
                        "CHECKED" => "checked"
                    ],
                    10 => [
                        "NAME" => Loc::getMessage("WEBCOMP_MARKET_REVIEWS"),
                        "ID" => 'reviews',
                        "ORDER" => 10,
                        "CHECKED" => "checked"
                    ],
                    11 => [
                        "NAME" => Loc::getMessage("WEBCOMP_MARKET_BRANDS"),
                        "ID" => 'brands',
                        "ORDER" => 11,
                        "CHECKED" => "checked"
                    ]
                ],
            ],
            [
                "TITLE" => Loc::getMessage("WEBCOMP_HEADING_SEO") ?: "",
                "TYPE" => "HEADING",
                "SORT" => 700,
                "STYLE" => [
                    "CLASS" => "WEBCOMP_HEADING_SEO",
                ],
            ],
            [
                "NAME" => "WEBCOMP_SEO_YANDEX_CHECKBOX",
                "TITLE" => Loc::getMessage("WEBCOMP_SEO_YANDEX_CHECKBOX"),
                "TYPE" => "CHECKBOX",
                "SORT" => 701,
                "DEFAULT" => $defaultOptions["WEBCOMP_CHECKBOX_DEFAULT_CHECK"] ?: "Y",
                "DESCRIPTION" => [
                    "TITLE" => Loc::getMessage("WEBCOMP_SEO_YANDEX_CHECKBOX"),
                ],
                "STYLE" => [
                    "CLASS" => "seo-check",
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "NAME" => "WEBCOMP_SEO_YANDEX_CODE",
                "TITLE" => Loc::getMessage("WEBCOMP_SEO_YANDEX_CODE"),
                "TYPE" => "EDITOR",
                "NOT_USE_HTML" => "N",
                "FILE" => "yandex_metrica.php",
                "SORT" => 702,
                "DESCRIPTION" => [],
                "STYLE" => [
                    "CLASS" => "seo-check__hidden hidden",
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "NAME" => "WEBCOMP_SEO_YANDEX_COUNT",
                "TITLE" => Loc::getMessage("WEBCOMP_SEO_YANDEX_COUNT"),
                "TYPE" => "STRING",
                "SORT" => 703,
                "DEFAULT" => $defaultOptions["WEBCOMP_STRING_DECIMAL_POINT"] ?: " ",
                "DESCRIPTION" => [],
                "STYLE" => [
                    "CLASS" => "seo-check__hidden hidden",
                    "WIDTH" => 20,
                    "HEIGHT" => 10,
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "TITLE" => Loc::getMessage("WEBCOMP_MARKET_SEO_TARGET_TEXT"),
                "TYPE" => "INFO",
                "SORT" => 704,
                "STYLE" => [
                    "CLASS" => "seo-check__hidden hidden seo-check__info",
                ],
            ],
            [
                "TITLE" => Loc::getMessage("WEBCOMP_HEADING_RECAPTCHA") ?: "",
                "TYPE" => "HEADING",
                "SORT" => 800,
                "STYLE" => [
                    "CLASS" => "WEBCOMP_HEADING_RECAPTCHA",
                ],
            ],
            [
                "NAME" => "WEBCOMP_RECAPTCHA_CHECKBOX",
                "TITLE" => Loc::getMessage("WEBCOMP_RECAPTCHA_CHECKBOX"),
                "TYPE" => "CHECKBOX",
                "SORT" => 801,
                "DEFAULT" => $defaultOptions["WEBCOMP_CHECKBOX_DEFAULT_CHECK"] ?: "Y",
                "DESCRIPTION" => [
                    "TITLE" => Loc::getMessage("WEBCOMP_RECAPTCHA_CHECKBOX"),
                ],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_RECAPTCHA_CHECKBOX",
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "NAME" => "WEBCOMP_RECAPTCHA_PUBLIC_CODE",
                "TITLE" => Loc::getMessage("WEBCOMP_RECAPTCHA_PUBLIC_CODE"),
                "TYPE" => "STRING",
                "SORT" => 802,
                "DEFAULT" => $defaultOptions["WEBCOMP_STRING_DECIMAL_POINT"] ?: " ",
                "DESCRIPTION" => [],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_RECAPTCHA_PUBLIC_CODE",
                    "WIDTH" => 50,
                    "HEIGHT" => 10,
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            [
                "NAME" => "WEBCOMP_RECAPTCHA_SECRET_CODE",
                "TITLE" => Loc::getMessage("WEBCOMP_RECAPTCHA_SECRET_CODE"),
                "TYPE" => "STRING",
                "SORT" => 803,
                "DEFAULT" => $defaultOptions["WEBCOMP_STRING_DECIMAL_POINT"] ?: " ",
                "DESCRIPTION" => [],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_RECAPTCHA_SECRET_CODE",
                    "WIDTH" => 50,
                    "HEIGHT" => 10,
                    "DATA" => [],
                    "DISABLED" => "N",
                ],
            ],
            // Тип select
            [
                "NAME" => "WEBCOMP_RECAPTCHA_SCORE",
                "TITLE" => Loc::getMessage("WEBCOMP_RECAPTCHA_SCORE"),
                "TYPE" => "SELECT",
                "SORT" => 804,
                "DEFAULT" => $defaultOptions["webcomp_theme_color"] ?: "default",
                "VALUES" => [
                    "0.1" => "0.1",
                    "0.2" => "0.2",
                    "0.3" => "0.3",
                    "0.4" => "0.4",
                    "0.5" => "0.5",
                    "0.6" => "0.6",
                    "0.7" => "0.7",
                    "0.8" => "0.8",
                    "0.9" => "0.9",
                ],
                "DESCRIPTION" => [
                    // "TITLE" => "Тут выводится описание подсказки",
                    // "LINK" => [
                    // 	"TEXT" => "Текст ссылки",
                    // 	"HREF" => "https://web-comp.ru",
                    // 	"NEW_WINDOW" => "Y",
                    // ],
                ],
                "STYLE" => [
                    "CLASS" => "WEBCOMP_RECAPTCHA_SCORE",
                    "HEIGHT" => 1,
                    "DATA" => [
                        "ID" => "10",
                        "COUNT" => "5"
                    ],
                    "DISABLED" => "N",
                ],
            ],

        ]
    ],
    // Второй таб
//    [
//        "DIV" => "webcomp_tab2",
//        "TAB" => Loc::getMessage("WEBCOMP_MARKET_ACCESS_RIGHTS"),
//        "TITLE" => Loc::getMessage("WEBCOMP_MARKET_TAB_RIGHTS_TITLE"),
//    ]
];



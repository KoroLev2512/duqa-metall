<?

use Bitrix\Main\Localization\Loc;

$arTemplateParameters['VIDEO'] = array(
    'PARENT' => 'ADDITIONAL_SETTINGS',
    'NAME' => Loc::getMessage("VIDEO_MANIAL"),
    'TYPE' => 'CUSTOM',
    'DEFAULT' => '',
    'JS_FILE' => '/bitrix/components/webcomp/element.getList/manual/manual.js',
    'JS_EVENT' => 'initVideoManual',
    'JS_DATA' => json_encode(["SOURCE" => "https://www.youtube.com/embed/Q6gn_g-Dlhs"])
);
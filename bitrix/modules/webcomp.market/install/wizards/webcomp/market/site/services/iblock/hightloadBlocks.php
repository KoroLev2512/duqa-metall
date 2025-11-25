<?php
define('ADMIN_MODULE_NAME', 'highloadblock');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
\Bitrix\Main\Loader::includeModule('highloadblock');
CModule::IncludeModule("highloadblock");
use Bitrix\Main\Localization\Loc;
use Bitrix\Highloadblock as HL;
$hBlockList = [
    "askQuestion",
    "simpleBlock",
    "social",
    "payments",
    "delivery",
    "orders",
    "colors",
    "orderPosition",
];
// functions
function __getEnumUserFields($ufId=false, $clear=false)
{
    static $userFieldsEnums = array();

    if ($clear && $ufId !== false &&array_key_exists($ufId, $userFieldsEnums))
    {
        unset($userFieldsEnums[$ufId]);
    }

    if ($ufId===false || !array_key_exists($ufId, $userFieldsEnums))
    {
        if ($ufId !== false)
        {
            $userFieldsEnums[$ufId] = array();
        }
        $res = \CUserFieldEnum::GetList(
            array(),
            array('USER_FIELD_ID' => $fId)
        );
        while ($row = $res->fetch())
        {
            if (!isset($userFieldsEnums[$row['USER_FIELD_ID']]))
            {
                $userFieldsEnums[$row['USER_FIELD_ID']] = array();
            }
            $userFieldsEnums[$row['USER_FIELD_ID']][$row['ID']] = $row['VALUE'];
        }
    }

    return $ufId === false ? $userFieldsEnums : $userFieldsEnums[$ufId];
}
function __hlImportPrepareField($value, &$userField, array $params=array())
{
    $userField['BASE_TYPE'] = $userField['USER_TYPE_ID'];
    if (is_array($value))
    {
        foreach ($value as &$v)
        {
            $v = __hlImportPrepareField($v, $userField, $params);
        }
        unset($v);
    }
    elseif (trim($value) != '')
    {
        // file get from local folder
        if ($userField['BASE_TYPE'] == 'file')
        {
            if (file_exists($value))
            {
                $value = \CFile::MakeFileArray($value);
            }
            else
            {
                $value = \CFile::MakeFileArray($params['path'] . '/' . $value);
            }
        }
        // for enums get the vals
        elseif ($userField['BASE_TYPE'] == 'enum' && is_array($userField['ENUMS']))
        {
            $enums = array_flip($userField['ENUMS']);
            if (isset($enums[$value]))
            {
                $value = $enums[$value];
            }
            // add new enum
            else
            {
                $userFieldEnums = new \CUserFieldEnum;
                $userFieldEnums->setEnumValues($userField['ID'], array(
                    'n0' => array(
                        'VALUE' => $value
                    )
                ));
                $userField['ENUMS'] = __getEnumUserFields($userField['ID'], true);
                $enums = array_flip($userField['ENUMS']);
                if (isset($enums[$value]))
                {
                    $value = $enums[$value];
                }
            }
        }
    }

    return $value;
}
function __prepareArrayFromXml(array $item, $code = false)
{
    $fields = array();
    if ($code !== false)
    {
        if (isset($item[$code]) && is_array($item[$code]))
        {
            $item = $item[$code];
        }
        else
        {
            $item = array();
        }
    }
    if (isset($item['#']) && is_array($item['#']))
    {
        foreach ($item['#'] as $key => $value)
        {
            if (is_array($value))
            {
                $value = array_shift($value);
            }
            if (is_array($value['#']))
            {
                $fields[mb_strtoupper($key)] = __prepareArrayFromXml($value);
            } else {
                $fields[mb_strtoupper($key)] = $value['#'];
            }
        }
    }

    return $fields;
}

/*мотод получения id поля в базе при привязке к элементам*/
function getIdFromUserFieldsHL($HLBLOCK_ID, $FIELD_NAME)
{
    $dbUserFields = \Bitrix\Main\UserFieldTable::getList([
        'filter' => [
            'ENTITY_ID'  => 'HLBLOCK_'.$HLBLOCK_ID,
            'FIELD_NAME' => $FIELD_NAME,
        ],
        'select' => ['ID'],
    ]);
    if ($arUserField = $dbUserFields->fetch()) {
        return $arUserField['ID'];
    }

    return '';
}

// init data
$hls = [];
$hlsOriginal = [];
$hlTables = [];
$xmlFields = [];
$userFelds = [];
foreach ($hBlockList as $hBlock) {
    $res = HL\HighloadBlockTable::getList([
        'select' => [
            '*',
            'NAME_LANG' => 'LANG.NAME',
        ],
        'order'  => [
            'NAME_LANG' => 'ASC',
            'NAME'      => 'ASC',
        ],
    ]);
    while ($row = $res->fetch()) {
        $hlsOriginal[$row['ID']] = $row;

        $row['NAME'] = $row['NAME_LANG'] != '' ? $row['NAME_LANG']
            : $row['NAME'];

        // get fields for HL
        $row['FIELDS'] = [
            'ID' => 'ID',
        ];
        $resF = \CUserTypeEntity::GetList(
            [],
            [
                'ENTITY_ID' => 'HLBLOCK_'.$row['ID'],
                'LANG'      => LANG,
            ]
        );
        while ($rowF = $resF->fetch()) {
            if (isset($USER_FIELD_MANAGER)) {
                $type = $USER_FIELD_MANAGER->GetUserType($rowF['USER_TYPE_ID']);
                if (is_array($type) && isset($type['BASE_TYPE'])) {
                    if (in_array($type['BASE_TYPE'], ['string', 'int'])) {
                        $row['FIELDS'][$rowF['FIELD_NAME']]
                            = $rowF['EDIT_FORM_LABEL'] != ''
                            ? $rowF['EDIT_FORM_LABEL']
                            : $rowF['FIELD_NAME'];
                    }
                }
            }
        }

        $xmlFields[$row['ID']] = $row['FIELDS'];
        $hls[$row['ID']] = $row;
        $hlTables[$row['TABLE_NAME']] = $row['ID'];
    }

    // data for next hit
    $NS = [
        'url_data_file'  => WIZARD_SERVICE_RELATIVE_PATH
            ."/xml/ru/hightload/$hBlock.xml",
        'object'         => 0,
        'xml_id'         => '',
        'import_hl'      => 1,
        'import_data'    => 1,
        'save_reference' => 1,
        'step'           => 0,
        'last_id'        => 0,
        'count'          => 0,
        'has_files'      => 1,
        'xml_pos'        => 0,
        'left_margin'    => 0,
        'right_margin'   => 0,
        'all'            => 0,
        'percent'        => 0,
        'time_limit'     => 300,
        'finish'         => false,
    ];

    // init
    $errors = [];
    $langs = [];
    $userFelds = [];
    $userFieldsEnums = __getEnumUserFields();
    $dataExist = false;
    $startTime = time();
    $import = new CXMLFileStream;
    $filesPath = $_SERVER['DOCUMENT_ROOT'].mb_substr($NS['url_data_file'], 0,
            -4).'_files';

    // get langs
    $langs = [];
    $res = \CLanguage::GetList($lby = 'sort', $lorder = 'asc');
    while ($row = $res->getNext()) {
        $langs[$row['LID']] = $row;
    }

    // get user fields
    if ($NS['object'] > 0) {
        $res = \CUserTypeEntity::GetList(
            [],
            [
                'ENTITY_ID' => 'HLBLOCK_'.$NS['object']
            ]
        );
        while ($row = $res->fetch()) {
            $userFelds[$row['FIELD_NAME']] = $row;
        }
    }

    // import hiblock
    $import->registerNodeHandler(
        '/hiblock/hiblock',
        function (CDataXML $xmlObject) use (&$NS, &$hls, &$errors) {
            if ($NS['import_hl'] && ! $NS['object'] && empty($errors)) {
                $hiblock = __prepareArrayFromXml($xmlObject->GetArray(),
                    'hiblock');
                if ( ! empty($hiblock)) {
                    if (isset($hiblock['ID'])) {
                        unset($hiblock['ID']);
                    }
                    $result = HL\HighloadBlockTable::add($hiblock);
                    if ($result->isSuccess()) {
                        $NS['object'] = $result->getId();
                        $hls[$NS['object']] = $hiblock;
                    } else {
                        $errors = array_merge($errors,
                            $result->getErrorMessages());
                    }
                } else {
                    $errors[]
                        = Loc::getMessage('ADMIN_TOOLS_ERROR_HB_NOT_CREATE');
                }
            } elseif ( ! $NS['object']) {
                $errors[] = Loc::getMessage('ADMIN_TOOLS_ERROR_HB_NOT_FOUND');
            } elseif ($NS['object']) {
                if ( ! HL\HighloadBlockTable::getById($NS['object'])->fetch()) {
                    $errors[]
                        = Loc::getMessage('ADMIN_TOOLS_ERROR_HB_NOT_FOUND');
                }
            }
        }
    );

    // import langs
    $import->registerNodeHandler(
        '/hiblock/langs/lang',
        function (CDataXML $xmlObject) use (&$NS, &$errors) {
            if ($NS['import_hl'] && $NS['object'] && empty($errors)) {
                $lang = __prepareArrayFromXml($xmlObject->GetArray(), 'lang');
                if ( ! empty($lang)) {
                    $lang['ID'] = $NS['object'];
                    // delete if exist
                    $res = HL\HighloadBlockLangTable::getList([
                        'filter' => [
                            'ID'  => $lang['ID'],
                            'LID' => $lang['LID']
                        ]
                    ]);
                    if ($row = $res->fetch()) {
                        HL\HighloadBlockLangTable::delete($row['ID']);
                    }
                    // add new
                    HL\HighloadBlockLangTable::add($lang);
                }
            }
        }
    );

    // import uf
    $import->registerNodeHandler(
        '/hiblock/fields/field',
        function (CDataXML $xmlObject) use (
            &$NS,
            $hlTables,
            &$userFelds,
            &
            $userFieldsEnums,
            $langs,
            &$errors,
            $APPLICATION
        ) {
            if ($NS['import_hl'] && $NS['object'] && empty($errors)) {
                $field = __prepareArrayFromXml($xmlObject->GetArray(), 'field');
                if ( ! empty($field)) {
                    // add new field, if no exist
                    if ( ! isset($userFelds[$field['FIELD_NAME']])) {
                        if (isset($field['ID'])) {
                            unset($field['ID']);
                        }
                        // re-set some settings
                        if (isset($field['SETTINGS'])
                            && is_array($field['SETTINGS'])
                        ) {
                            if (isset($field['SETTINGS']['HLBLOCK_TABLE'])
                                && $field['SETTINGS']['HLBLOCK_TABLE'] != ''
                            ) {
                                $field['SETTINGS']['HLBLOCK_ID']
                                    = $hlTables[$field["SETTINGS"]['HLBLOCK_TABLE']];

                                if (isset($field['SETTINGS']['HLFIELD_NAME'])
                                    && $field['SETTINGS']['HLFIELD_NAME'] != ''
                                ) {
                                    $field['SETTINGS']["HLFIELD_ID"]
                                        = getIdFromUserFieldsHL($field['SETTINGS']['HLBLOCK_ID'],
                                        $field['SETTINGS']['HLFIELD_NAME']);
                                }
                            }
                        }
                        // set language keys to lowercase
                        $codes = [
                            'EDIT_FORM_LABEL',
                            'LIST_COLUMN_LABEL',
                            'LIST_FILTER_LABEL',
                            'ERROR_MESSAGE',
                            'HELP_MESSAGE'
                        ];
                        foreach ($codes as $code) {
                            if (isset($field[$code])
                                && is_array($field[$code])
                            ) {
                                foreach ($langs as $lng => $lang) {
                                    if ($lng !== mb_strtoupper($lng)
                                        && isset($field[$code][mb_strtoupper($lng)])
                                    ) {
                                        $field[$code][$lng]
                                            = $field[$code][mb_strtoupper($lng)];
                                        unset($field[$code][mb_strtoupper($lng)]);
                                    }
                                }
                            }
                        }
                        // add field
                        $field['ENTITY_ID'] = 'HLBLOCK_'.$NS['object'];
                        $userField = new \CUserTypeEntity;
                        $fId = $userField->add($field);
                        if ($fId > 0) {
                            $userFelds[$field['FIELD_NAME']] = $field;
                            // set enumeration list
                            if (
                                $fId && $field['BASE_TYPE'] == 'enum'
                                && isset($field['ENUMS'])
                                && ! empty($field['ENUMS'])
                            ) {
                                $enums = [];
                                foreach (
                                    array_values($field['ENUMS']) as $k => $enum
                                ) {
                                    $enums['n'.$k] = [
                                        'VALUE'  => $enum['VALUE'],
                                        'DEF'    => $enum['DEF'],
                                        'SORT'   => $enum['SORT'],
                                        'XML_ID' => $enum['XML_ID']
                                    ];
                                }
                                $userFieldEnums = new \CUserFieldEnum;
                                $userFieldEnums->setEnumValues($fId, $enums);
                                // add new values
                                $userFieldsEnums[$fId]
                                    = __getEnumUserFields($fId, true);
                            }
                        } else {
                            if ($e = $APPLICATION->getException()) {
                                $errors[] = $e->getString();
                            }
                        }
                    }
                }
            }
        }
    );

    // import data
    $import->registerNodeHandler(
        '/hiblock/items/item',
        function (CDataXML $xmlObject) use (
            &$NS,
            $hls,
            $filesPath,
            $userFelds,
            &$errors,
            $USER_FIELD_MANAGER,
            $hlsOriginal
        ) {
            static $class = null;
            static $hlLocal = null;
            static $userFeldsLocal = null;
            static $userFeldsEnumLocal = null;
            if ($NS['object'] && empty($errors)) {
                // first refill some arrays if need
                if ( ! isset($hls[$NS['object']])) {
                    if ($hlLocal === null) {
                        $hlLocal = HL\HighloadBlockTable::getById($NS['object'])
                            ->fetch();
                    }
                    $hls[$NS['object']] = $hlLocal;
                }
                if ( ! $hls[$NS['object']]) {
                    $errors[]
                        = Loc::getMessage('ADMIN_TOOLS_ERROR_HB_NOT_FOUND');

                    return;
                }
                if (empty($userFelds)) {
                    if ($userFeldsLocal === null) {
                        $userFeldsLocal = [];
                        $res = \CUserTypeEntity::GetList(
                            [],
                            [
                                'ENTITY_ID' => 'HLBLOCK_'.$NS['object']
                            ]
                        );
                        while ($row = $res->fetch()) {
                            $userFeldsLocal[$row['FIELD_NAME']] = $row;
                        }
                    }
                    $userFelds = $userFeldsLocal;
                }
                if ($userFeldsEnumLocal === null) {
                    $userFeldsEnumLocal = __getEnumUserFields(false, true);
                }
                $userFieldsEnums = $userFeldsEnumLocal;
                // then add
                $item = __prepareArrayFromXml($xmlObject->GetArray(), 'item');
                if ( ! empty($item)) {
                    $NS['count']++;
                    if ( ! isset($item['ID'])) {
                        $item['ID'] = 'unknown';
                    }
                    if ($class === null) {
                        if (
                        $entity = HL\HighloadBlockTable::compileEntity(
                            isset($hlsOriginal[$NS['object']])
                                ? $hlsOriginal[$NS['object']]
                                : $hls[$NS['object']]
                        )
                        ) {
                            $class = $entity->getDataClass();
                        }
                    }
                    if ($class) {
                        // send event
                        $event = new \Bitrix\Main\Event(ADMIN_MODULE_NAME,
                            'onBeforeItemImportAdd', [
                                'ITEM'        => $item,
                                'USER_FIELDS' => $userFelds,
                                'NS'          => $NS,
                            ]);
                        $event->send();
                        foreach ($event->getResults() as $result) {
                            if ($result->getResultType()
                                != \Bitrix\Main\EventResult::ERROR
                            ) {
                                if (($modified = $result->getModified())) {
                                    if (isset($modified['ITEM'])) {
                                        $item = $modified['ITEM'];
                                    }
                                }
                                // here not used: $result->getUnset()
                            } elseif ($result->getResultType()
                                == \Bitrix\Main\EventResult::ERROR
                            ) {
                                if (($eventErrors = $result->getErrors())) {
                                    foreach ($eventErrors as $error) {
                                        $errors[]
                                            = Loc::getMessage('ADMIN_TOOLS_ERROR_IMPORT_ITEM',
                                                ['#ID#' => $item['ID']]).' '
                                            .$error->getMessage();
                                    }

                                    return;
                                }
                            }
                        }
                        // prepare array before add
                        $filesExist = false;
                        foreach ($item as $key => &$value) {
                            if ($key != 'ID' && ! isset($userFelds[$key])) {
                                $errors[]
                                    = Loc::getMessage('ADMIN_TOOLS_ERROR_IMPORT_ITEM',
                                        ['#ID#' => $item['ID']]).' '.
                                    Loc::getMessage('ADMIN_TOOLS_ERROR_IMPORT_ITEM_UNKNOWN',
                                        ['#CODE#' => $key]);

                                return;
                            }
                            if (mb_substr($value, 0, 10) == 'serialize#') {
                                $value = unserialize(mb_substr($value, 10));
                            }
                            // get enums
                            if ($userFelds[$key]['BASE_TYPE'] == 'enum') {
                                $userFelds[$key]['ENUMS']
                                    = $userFieldsEnums[$userFelds[$key]['ID']];
                            }
                            if ($userFelds[$key]['BASE_TYPE'] == 'file' || $userFelds[$key]['USER_TYPE_ID'] == 'file' ) {
                                $filesExist = true;
                            }
                            // prepare value
                            $value = __hlImportPrepareField(
                                $value,
                                $userFelds[$key],
                                [
                                    'path' => $filesPath,
                                ]);
                            // clear refernces
                            if ( ! $NS['save_reference']) {
                                $codeReferences = [
                                    'employee',
                                    'hlblock',
                                    'crm',
                                    'iblock_section',
                                    'iblock_element'
                                ];
                                if (in_array($userFelds[$key]['USER_TYPE_ID'],
                                    $codeReferences)
                                ) {
                                    $value = '';
                                }
                            }
                        }
                        unset($value);
                        // add / update item
                        $exist = false;
                        if ($NS['xml_id'] && isset($item[$NS['xml_id']])
                            && trim($item[$NS['xml_id']]) != ''
                        ) {
                            $exist = $class::getList($a = [
                                'filter' => [
                                    '='
                                    .$NS['xml_id'] => trim($item[$NS['xml_id']])
                                ]
                            ])->fetch();
                            if ($exist) {
                                if (isset($item['ID'])) {
                                    unset($item['ID']);
                                }
                                $result = $class::update($exist['ID'], $item);
                            }
                        }
                        if ( ! $exist) {
                            if (isset($item['ID'])) {
                                unset($item['ID']);
                            }
                            $result = $class::add($item);
                        }
                        if ($result->isSuccess()) {
                            // remove old files
                            if ($exist && $filesExist) {
                                foreach ($exist as $key => $value) {
                                    if ($userFelds[$key]['BASE_TYPE']
                                        == 'file'
                                    ) {
                                        if ( ! is_array($value)) {
                                            $value = [$value];
                                        }
                                        foreach ($value as $fid) {
                                            \CFile::delete($fid);
                                        }
                                    }
                                }
                            }
                        } else {
                            foreach ($result->getErrorMessages() as $message) {
                                $errors[]
                                    = Loc::getMessage('ADMIN_TOOLS_ERROR_IMPORT_ITEM',
                                        ['#ID#' => $item['ID']]).' '.$message;
                            }
                        }
                    }
                }
            }
        }
    );

    // work
    $import->setPosition($NS['xml_pos']);
    if ($import->openFile($_SERVER['DOCUMENT_ROOT'].$NS['url_data_file'])) {
        while ($import->findNext()) {
            if (time() - $NS['time_limit'] > $startTime) {
                break;
            }
        }
        // finish or not
        if ($import->endOfFile()) {
            $NS['percent'] = 100;
            $NS['finish'] = true;
        } else {
            // calc percent
            $NS['xml_pos'] = $import->getPosition();
            if (is_array($NS['xml_pos']) && isset($NS['xml_pos'][1])) {
                $curSize = $NS['xml_pos'][1];
                $allSize = filesize($_SERVER['DOCUMENT_ROOT']
                    .$NS['url_data_file']);
                $NS['percent'] = round($curSize / $allSize * 100, 2);
            }
            $NS['xml_pos'] = implode('|', $NS['xml_pos']);
        }
    } else {
        $errors[] = Loc::getMessage('XML_FILE_NOT_ACCESSIBLE');
    }
    $NS['step']++;

    // show message (error or processing)
    if ( ! empty($errors)) {
        \CAdminMessage::ShowMessage([
            'MESSAGE' => Loc::getMessage('ADMIN_TOOLS_ERROR_IMPORT'),
            'DETAILS' => implode('<br/>', $errors),
            'HTML'    => true,
            'TYPE'    => 'ERROR',
        ]);
    } else {
        $details = Loc::getMessage('ADMIN_TOOLS_PROCESS_PERCENT',
            [
                '#percent#' => $NS['percent'],
                '#count#'   => $NS['count'],
                '#all#'     => $NS['all'],
            ]);
        if ($NS['finish']) {
            $details .= '<br/>'.Loc::getMessage('ADMIN_TOOLS_PROCESS_FINAL');
        }
        \CAdminMessage::ShowMessage([
            'MESSAGE' => Loc::getMessage('ADMIN_TOOLS_PROCESS_IMPORT'),
            'DETAILS' => $details,
            'HTML'    => true,
            'TYPE'    => 'PROGRESS',
        ]);
        if ($NS['finish']) {
            \CAdminMessage::ShowMessage([
                'MESSAGE' => Loc::getMessage('ADMIN_TOOLS_PROCESS_FINISH_DELETE'),
                'DETAILS' => '',
                'HTML'    => true,
                'TYPE'    => 'ERROR',
            ]);
        }
    }
}
<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

#<editor-fold desc="Namespaces">
use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Diag,
    Bitrix\Main\Application,
    Bitrix\Main\Entity;
#Namespaces </editor-fold>

class ElementGetListComponent extends CBitrixComponent
{
    private $APPLICATION;
    /**
     * @var int
     */
    private $nPage;
    private $iblock;
    /**
     * @var array
     */
    private $arElements;
    private $arProperties;
    private $arPropertiesCodes;
    /**
     * @var array
     */
    private $orderSelect;
    /**
     * @var \Bitrix\Main\ORM\Query\Result
     */
    private $dbElements;
    /**
     * @var array
     */
    private $filesIdsTmp;
    /**
     * @var array
     */
    private $arPropertiesQuery;

    public function onPrepareComponentParams($arParams)
    {
        Loader::includeModule("iblock");
        global $APPLICATION;
        $this->APPLICATION = $APPLICATION;

        if (empty($this->arParams)) {
            $this->arParams = $arParams;
        }

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        $this->nPage = (int)$request["page"];

        $this->iblock
            = \Bitrix\Iblock\Iblock::wakeUp($this->arParams['IBLOCK_ID']);

        $this->arElements = [];
        $this->arProperties = [];
        $this->arPropertiesCodes = [];

        if (empty($this->arParams['SORT_BY1'])) {
            $this->arParams['SORT_BY1'] = 'ID';
        }

        if (empty($this->arParams['SORT_ORDER1'])) {
            $this->arParams['SORT_ORDER1'] = 'DESC';
        }

        if (empty($this->arParams['ELEMENTS_COUNT'])) {
            $this->arParams['ELEMENTS_COUNT'] = '20';
        }

        if ( ! empty($this->arParams['SORT_BY2'])
            && ! empty($this->arParams['SORT_ORDER2'])
        ) {
            $this->orderSelect = [
                $this->arParams['SORT_BY1'] => $this->arParams['SORT_ORDER1'],
                $this->arParams['SORT_BY2'] => $this->arParams['SORT_ORDER2'],
            ];
        } else {
            $this->orderSelect = [
                $this->arParams['SORT_BY1'] => $this->arParams['SORT_ORDER1'],
            ];
        }
        $this->arParams["FIELD_CODE"]['DETAIL_PAGE_URL']
            = 'IBLOCK.DETAIL_PAGE_URL';
        //для ЧПУ элементов обязательно выбираем IBLOCK_SECTION_ID
        $this->arParams["FIELD_CODE"]['IBLOCK_SECTION_ID']
            = 'IBLOCK_SECTION_ID';

        if ($this->arParams["USE_FILTER"] === "Y"
            && ! empty($this->arParams["FILTER_NAME"])
        ) {
            $this->arParams["FILTER"]
                = $GLOBALS[$this->arParams["FILTER_NAME"]];
        }
        return $this->arParams;
    }

    #<editor-fold desc="selectElementsFromDB">

    public function executeComponent()
    {
        if ($this->startResultCache()) {
            $this->selectPropertiesForIB();
            $this->selectElementsFromDB();
            $this->selectPropertiesAndAddToItems();
            $this->getAllFilesAndAddToItems();

            if($this->APPLICATION->GetShowIncludeAreas() && isset($this->arParams["IBLOCK_ID"])) {
                $arButtons = CIBlock::GetPanelButtons(
                    $this->arParams["IBLOCK_ID"],
                    0,
                    0,
                    array("SECTION_BUTTONS"=>false)
                );

                if($this->APPLICATION->GetShowIncludeAreas())
                    $this->addIncludeAreaIcons(CIBlock::GetComponentMenu($this->APPLICATION->GetPublicShowMode(), $arButtons));
            }

            if ($this->arParams['DONT_INCLUDE_TEMPLATE'] != 'Y') {
                $this->includeComponentTemplate();
            }
            return $this->arResult;
        }
    }
    #selectElementsFromDB </editor-fold>

    #<editor-fold desc="selectPropertiesForIB">

    protected function selectElementsFromDB()
    {
        \Webcomp\Market\Tools::clearEmptyValuesFromArParams($this->arParams);

        $arrFilter = [
            'IBLOCK_ID' => $this->arParams["IBLOCK_ID"],
            'ACTIVE'    => $this->arParams["SHOW_ONLY_ACTIVE"],
        ];
        if (isset($this->arParams["FILTER"])) {
            $arrFilter = array_merge($arrFilter, $this->arParams["FILTER"]);
        }

        if ( ! $this->propertiesInFilter()) {
            $this->dbElements = \Bitrix\Iblock\ElementTable::getList([
                'select' => $this->arParams["FIELD_CODE"],
                'filter' => $arrFilter,
                'limit'  => $this->arParams['ELEMENTS_COUNT'],
                'offset' => $this->arParams['ELEMENTS_COUNT'] * $this->nPage,
                'order'  => $this->orderSelect,
            ]);
        } else {
            $res = CIBlockElement::getList(
                $this->orderSelect,
                $arrFilter,
                false,
                [],
                ['ID']
            );
            while ($ob = $res->GetNextElement()) {
                $arrIDsToSelect[] = $ob->GetFields()['ID'];
            }

            //todo:: не выводятся множественные свойства
            if ( ! empty($arrIDsToSelect)) {
                $this->dbElements = \Bitrix\Iblock\ElementTable::getList([
                    'select' => $this->arParams["FIELD_CODE"],
                    'filter' => ['ID' => $arrIDsToSelect],
                    'limit'  => $this->arParams['ELEMENTS_COUNT'],
                    'order'  => $this->orderSelect,
                ]);
            }
            /*if(!empty($this->arParams["PROPERTY_CODE"])){
                foreach ($this->arParams["PROPERTY_CODE"] as $propertyCode){
                    $this->arParams["PROPERTY_PROPERTY_CODE"][] = 'PROPERTY_'.$propertyCode;
                }
            }

            $res = CIBlockElement::getList(
                $this->orderSelect,
                $arrFilter,
                false,
                [],
                array_merge($this->arParams["FIELD_CODE"],$this->arParams["PROPERTY_PROPERTY_CODE"])
            );

            while($ob = $res->GetNextElement()){
                $arrIDsToSelect[] = $ob->GetFields()['ID'];
            }

            $res = CIBlockElement::getList(
                [],
                ['ID'=>$arrIDsToSelect],
                false,
                [],
                array_merge($this->arParams["FIELD_CODE"],$this->arParams["PROPERTY_PROPERTY_CODE"])
            );
            while($ob = $res->GetNextElement()){
                $elements[] = $ob->GetFields();
            }
            var_dump($elements);*/
        }
    }
    #selectPropertiesForIB </editor-fold>

    #<editor-fold desc="selectPropertiesAndAddToItems">

    protected function selectPropertiesForIB()
    {
        //Выборка свойств инфоблока (с типами данных для распределения потом у кого какой тип данных)
        if ( ! empty($this->arParams['PROPERTY_CODE'])) {
            $rsProperty = \Bitrix\Iblock\PropertyTable::getList([
                'select' => [
                    'ID',
                    'NAME',
                    'CODE',
                    'LIST_TYPE',
                    'PROPERTY_TYPE',
                    'MULTIPLE',
                    'XML_ID',
                    'WITH_DESCRIPTION',
                ],
                'filter' => [
                    'IBLOCK_ID' => $this->arParams["IBLOCK_ID"],
                    'ACTIVE'    => 'Y',
                    'CODE'      => $this->arParams['PROPERTY_CODE'],
                ],
            ]);

            while ($arProperty = $rsProperty->fetch()) {
                $this->arProperties[$arProperty['CODE']] = $arProperty;
                $this->arPropertiesCodes[] = $arProperty['CODE'];
            }
            //Diag\Debug::dump($arProperties);
        }
    }
    #selectPropertiesAndAddToItems </editor-fold>

    /**
     * Метод проверки есть ли фильтрация по свойствам (PROPERTY_)
     */
    protected function propertiesInFilter()
    {
        if (is_array($this->arParams["FILTER"])
            && ! empty($this->arParams["FILTER"])
        ) {
            foreach ($this->arParams["FILTER"] as $CODE => $VALUE) {
                if (current($property_code = explode('_', $CODE))
                    == 'PROPERTY'
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Метод получения типа свойства
     * S - строка, N - число, F - файл, L - список, E - привязка к элементам, G - привязка к группам.
     *
     * @param $propertyCode
     *
     * @return string
     */
    protected function getTypeProperty($propertyCode)
    {
        return (string)$this->arProperties['PROPERTIES'][$propertyCode]['PROPERTY_TYPE'];
    }

    protected function getOperatorFilter($propArray)
    {

    }

#<editor-fold desc="getAllFilesAndAddToItems">

    protected function selectPropertiesAndAddToItems()
    {
        if (isset($this->dbElements)) {
            while ($arItem = $this->dbElements->Fetch()) {
                $arItem['DETAIL_PAGE_URL']
                    = CIBlock::ReplaceDetailUrl($arItem['DETAIL_PAGE_URL'],
                    $arItem, true, 'E');//frendly URL

                if ( ! empty($this->arPropertiesCodes)) {
                    $element
                        = $this->iblock->getEntityDataClass()::getByPrimary(
                        $arItem['ID'],
                        ['select' => $this->arPropertiesCodes]
                    )->fetchObject();

                    $propertiesCollection = $element->collectValues();
                    foreach ($propertiesCollection as $code => $value) {
                        if ( ! empty($this->arProperties[$code])
                            && $this->arProperties[$code]['MULTIPLE'] != 'Y'
                        ) {
                            switch ($this->arProperties[$code]["PROPERTY_TYPE"]) {
                                case "F":// File
                                    $this->arProperties[$code]['FILE_ID'] = $this->filesIdsTmp[] = isset($value)
                                        ? $value->getValue()
                                        : "";

                                    // Была ошибка с проверкой на пустоту
                                    // $this->filesIdsTmp[] = $fileId = $value->getValue();
                                    // $this->arProperties[$code]['FILE_ID'] = $fileId;
                                    break;
                                /*case "L":// List
                                    $prop = \Bitrix\Iblock\PropertyEnumerationTable::getList([
                                        "select" => ["*"],
                                    ])->fetchAll();

                                    print_r($prop);
                                    break; */
                                //ToDo:: OTHER CASE
                                default:
                                    // $this->arProperties[$code]['VALUE']
                                    //         = $value->getValue();

                                    // Миша, если значение поля пустое, то вылетает фатал. так же значение предыдущего элемента запоминается, для последующего элемента
                                    // Для этого если значение NULL то обнуляем его в пустую строку
                                    $this->arProperties[$code]['VALUE']
                                        = isset($value) ? $value->getValue()
                                        : "";
                                    //Проверяем есть ли у данного свойства "описание"
                                    if ($this->arProperties[$code]['WITH_DESCRIPTION']
                                        == 'Y'
                                    ) {
                                        $this->arProperties[$code]['DESCRIPTION']
                                            = isset($value)
                                            ? $value->getDescription() : "";
                                    } else {
                                        $this->arProperties[$code]['DESCRIPTION']
                                            = "";
                                    }
                                    break;
                            }
                            $arItem['PROPERTIES'][$code]
                                = $this->arProperties[$code];

                            //unset($this->arProperties[$code]);
                        } elseif ($this->arProperties[$code]['MULTIPLE']
                            // множественный тип
                            == 'Y'
                        ) {
                            foreach ($value->getAll() as $v) {

                                if ($this->arProperties[$code]['WITH_DESCRIPTION']
                                    == 'Y'
                                ) {
                                    $descriptionTmp = isset($value)
                                        ? $v->getDescription() : "";
                                } else {
                                    $descriptionTmp = "";
                                }
                                $this->arProperties[$code]['VALUES'][] = [
                                    'VALUE' => isset($v) ? $v->getValue() : "",
                                    'DESCRIPTION' => $descriptionTmp,
                                ];
                                unset($descriptionTmp);
                            }

                            $arItem['PROPERTIES'][$code]
                                = $this->arProperties[$code];

                            // Убиваем дубли, нужно тестить
                            if(isset($this->arProperties[$code]['VALUES']))
                                unset($this->arProperties[$code]['VALUES']);
                        }
                    }
                }

                $arButtons = CIBlock::GetPanelButtons(
                    $this->arParams["IBLOCK_ID"],
                    $arItem["ID"],
                    0,
                    ["SECTION_BUTTONS" => false, "SESSID" => false]
                );
                $arItem["EDIT_LINK"]
                    = $arButtons["edit"]["edit_element"]["ACTION_URL"];
                $arItem["DELETE_LINK"]
                    = $arButtons["edit"]["delete_element"]["ACTION_URL"];

                if ( ! empty($arItem['PREVIEW_PICTURE'])) {
                    $this->filesIdsTmp[] = $arItem['PREVIEW_PICTURE'];
                }
                if ( ! empty($arItem['DETAIL_PICTURE'])) {
                    $this->filesIdsTmp[] = $arItem['DETAIL_PICTURE'];
                }
                $this->arResult['ITEMS'][] = $arItem;
            }
        }
    }

    #getAllFilesAndAddToItems </editor-fold>


    protected function getAllFilesAndAddToItems()
    {
        $filesArTmpDb = \Bitrix\Main\FileTable::getList([
            'select' => [
                'ID',
                'TIMESTAMP_X',
                'HEIGHT',
                'WIDTH',
                'FILE_SIZE',
                'CONTENT_TYPE',
                'SUBDIR',
                'FILE_NAME',
                'ORIGINAL_NAME',
                'DESCRIPTION',
                'SRC',
            ],
            'filter' => [
                'ID' => $this->filesIdsTmp,
            ],
            'runtime' => [
                new Entity\ExpressionField('SRC',
                    "(CONCAT('/upload/',SUBDIR,'/',FILE_NAME))"),
            ],
        ]);
        $filesArTmp = $filesArTmpDb->fetchAll();
        foreach ($filesArTmp as $item) {
            $filesArTmpWithKeys[$item['ID']] = $item;
        }
        unset ($filesArTmp, $filesArTmpDb, $this->filesIdsTmp);

        if (isset($this->arResult['ITEMS'])
            && is_array($this->arResult['ITEMS'])
        ) {
            foreach ($this->arResult['ITEMS'] as &$ITEM) {
                if (isset($ITEM['PROPERTIES'])) {
                    foreach ($ITEM['PROPERTIES'] as &$PROPERTY) {
                        if (isset($PROPERTY['FILE_ID'])
                            && ! empty($PROPERTY['FILE_ID'])
                        ) {
                            $PROPERTY['VALUE']
                                = $filesArTmpWithKeys[$PROPERTY['FILE_ID']];
                        }
                    }
                }

                if ( ! empty($ITEM['PREVIEW_PICTURE'])) {
                    $ITEM['PREVIEW_PICTURE_VALUE']
                        = $filesArTmpWithKeys[$ITEM['PREVIEW_PICTURE']];
                }
                if ( ! empty($ITEM['DETAIL_PICTURE'])) {
                    $ITEM['DETAIL_PICTURE_VALUE']
                        = $filesArTmpWithKeys[$ITEM['DETAIL_PICTURE']];
                }
            }
        }
    }
}
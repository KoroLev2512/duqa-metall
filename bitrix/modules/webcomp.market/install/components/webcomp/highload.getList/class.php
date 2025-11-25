<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

#<editor-fold desc="Namespaces">
use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Diag,
    Bitrix\Main\Application,
    Bitrix\Main\Entity,
    Bitrix\Highloadblock as HL;

Loader::includeModule("highloadblock"); 

#Namespaces </editor-fold>

class HighLoadGetListComponent extends CBitrixComponent
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
    private $userFields;

    public function onPrepareComponentParams($arParams)
    {
        global $APPLICATION;
        $this->APPLICATION = $APPLICATION;

        if (empty($this->arParams)) {
            $this->arParams = $arParams;
        }

        $this->iblock
            = HL\HighloadBlockTable::getById($this->arParams['HLBLOCK_ID'])
            ->fetch();

        $this->arElements = [];
        $this->arProperties = [];
        $this->arPropertiesCodes = [];

        /**
         * Сортировка по умолчанию
         */
        if (empty($this->arParams['SORT_FILED'])) {
            $this->arParams['SORT_FILED'] = 'ID';
        }

        if (empty($this->arParams['SORT_ORDER'])) {
            $this->arParams['SORT_ORDER'] = 'DESC';
        }
        $this->orderSelect = [
            $this->arParams['SORT_FILED'] => $this->arParams['SORT_ORDER'],
        ];

        /**
         * Количество выбираемых элементов по умолчанию
         */
        if (empty($this->arParams['ELEMENTS_COUNT'])) {
            $this->arParams['ELEMENTS_COUNT'] = '20';
        }

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
          
            $this->selectElementsFromDB();

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

        $arrFilter = [];

        if (isset($this->arParams["FILTER"])) {
            $arrFilter = array_merge($arrFilter, $this->arParams["FILTER"]);
        }

        /**
         *
         */
        $this->userFields = Bitrix\Main\UserFieldTable::getList([
            'select' => ['*'],
            'filter' => [
                "ENTITY_ID" => "HLBLOCK_".$this->arParams['HLBLOCK_ID'],
                "FIELD_NAME" => $this->arParams["FIELD_CODE"],
            ],
        ])->fetchAll();
        //todo::сделать проверку на существование в таблице user fields
        if ( ! empty($this->userFields)) {
            $userFieldsThisHB = [];
            foreach ($this->userFields as $uField) {
                $userFieldsThisHB[$uField['FIELD_NAME']] = $uField;
            }
            $this->userFields = $userFieldsThisHB;
            unset($userFieldsThisHB);
        }

        $entity = HL\HighloadBlockTable::compileEntity($this->iblock);
        $entity_data_class = $entity->getDataClass();


        $this->dbElements = $entity_data_class::getList([
            'select' => $this->arParams["FIELD_CODE"],
            'filter' => $arrFilter,
            'limit'  => $this->arParams['ELEMENTS_COUNT'],
            'order'  => $this->orderSelect,
        ]);

        /**
         * Объединяем свойства полей сущности и значения
         * Обработка типов полей
         */
        while ($arItem = $this->dbElements->Fetch()) {
            foreach ($this->userFields as $k => $v) {
                $arItem[$k] = array_merge($v, ['VALUE' => $arItem[$k]]);
                switch ($arItem[$k]['USER_TYPE_ID']) {
                    case "file" :
                        /** Тип файл - далем запрос в базу файлов */
                        $arItem[$k]['VALUE'] = \Bitrix\Main\FileTable::getList([
                            'select' => ["*", 'SRC'],
                            'filter' => ["ID" => $arItem[$k]['VALUE']],
                            'runtime' => [
                                new Entity\ExpressionField('SRC',
                                    "(CONCAT('/upload/',SUBDIR,'/',FILE_NAME))"),
                            ],
                        ])->fetchAll();
                        break;
                }
            }
            $this->arResult['ITEMS'][] = $arItem;
        }
        
    }
    #selectPropertiesForIB </editor-fold>
}
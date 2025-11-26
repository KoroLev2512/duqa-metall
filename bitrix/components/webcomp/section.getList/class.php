<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class SectionGetListComponent extends CBitrixComponent
{
    /**
     * @var CAllMain|CMain
     */
    private $APPLICATION;
    /**
     * @var array
     */
    private $sectionsMenu;

    /**
     * Установка стандартных параметров компонента
     * @param $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams){
        global $APPLICATION;
        $this->APPLICATION = $APPLICATION;

        if(empty($this->arParams))
            $this->arParams = $arParams;

        if (empty($this->arParams['SORT_BY1'])) {
            $this->arParams['SORT_BY1'] = 'ID';
        }
        if (empty($this->arParams['SORT_ORDER1'])) {
            $this->arParams['SORT_ORDER1'] = 'DESC';
        }
        if (empty($this->arParams['ELEMENTS_COUNT'])) {
            $this->arParams['ELEMENTS_COUNT'] = '20';
        }

        if(!empty($this->arParams['PARAMS_CATALOG_FIELD_CODE'])){
            $this->arParams["FIELD_CODE"] = $this->arParams['PARAMS_CATALOG_FIELD_CODE'];
        }

        $this->arParams["FIELD_CODE"]['SECTION_PAGE_URL'] = 'IBLOCK.SECTION_PAGE_URL';



        // Новое не протестированное { 
        if($this->arParams["USE_FILTER"] === "Y" && !empty($this->arParams["FILTER_NAME"])) {
            $this->arParams["FILTER"] = $GLOBALS[$this->arParams["FILTER_NAME"]];
        }
        // } Новое не протестированное

        \Webcomp\Market\Tools::clearEmptyValuesFromArParams($this->arParams);
        return $this->arParams;
    }

    /**
     * Установка массива сортировки
     * Для правильно сортировки вложенных элементов в конец добавляем в начало массива сортировку LEFT_MARGIN
     * @return array
     */
    public function getOrderSelect(){
        if ( ! empty($this->arParams['SORT_BY2']) && ! empty($this->arParams['SORT_ORDER2'])) {
            $orderSelect =  [
                $this->arParams['SORT_BY1'] => $this->arParams['SORT_ORDER1'],
                $this->arParams['SORT_BY2'] => $this->arParams['SORT_ORDER2'],
            ];
        } else {
            $orderSelect = [
                $this->arParams['SORT_BY1'] => $this->arParams['SORT_ORDER1'],
            ];
        }
        return ['LEFT_MARGIN' => 'ASC'] + $orderSelect; // *1
    }

    /**
     * Выборка разделов
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getSections(){
        $arResult = [];

        // Новое не протестированное { 

        $arrFilter = [
            'IBLOCK_ID' => $this->arParams["IBLOCK_ID"],
            'ACTIVE'    => $this->arParams["SHOW_ONLY_ACTIVE"]=="Y" ? "Y" : "*",
            '<=DEPTH_LEVEL' => ((int) $this->arParams['MAX_DEPTH']==0 ? 1 : (int) $this->arParams['MAX_DEPTH']),
        ];

        // todo: Миша Нашел в инете такой способ, нужно будет посмотреть на сколько он нормальный, но фильтр норм отрабатывает ))

        if(isset($this->arParams["FILTER"])) {
            // Удаляем DEPTH_LEVEL так как фильт будет выводить категории из всего инфоблока, без учёта вложенности
            unset($arrFilter["<=DEPTH_LEVEL"]);
            $arrFilter = array_merge($arrFilter, $this->arParams["FILTER"]);
        }

        $entity
            = \Bitrix\Iblock\Model\Section::compileEntityByIblock($this->arParams["IBLOCK_ID"]);
        \Webcomp\Market\Tools::clearEmptyValuesFromArParams($this->arParams);

        $rsSections = $entity::getList([
            "filter" => $arrFilter,
            "select" => $this->arParams["FIELD_CODE"],
            'order'  => $this->getOrderSelect()
        ]);
        // } Новое не протестированное


        /*$rsSections = \Bitrix\Iblock\SectionTable::getList([
            'filter' => [
                'IBLOCK_ID' => $this->arParams["IBLOCK_ID"],
                'ACTIVE'    => $this->arParams["SHOW_ONLY_ACTIVE"]=="Y" ? "Y" : "*",
                '<=DEPTH_LEVEL' => ((int) $this->arParams['MAX_DEPTH']==0 ? 1 : (int) $this->arParams['MAX_DEPTH']),
            ],
            'select' => $this->arParams["FIELD_CODE"],
            'order' => $this->getOrderSelect()
        ]);*/

        while ($arSection = $rsSections->fetch()) {
            /** ЧПУ URL из параметров ИНФОБЛОКА*/
            $arSection['SECTION_PAGE_URL']
                = CIBlock::ReplaceDetailUrl($arSection['SECTION_PAGE_URL'],
                $arSection, true, 'S');
            /*
                    $arButtons = CIBlock::GetPanelButtons(
                        $this->arParams["IBLOCK_ID"],
                        0,
                        $arSection["ID"],
                        ["SECTION_BUTTONS" => false, "SESSID" => false]
                    );
            */
            $arResult[] = $arSection;
        }
        return $arResult;
    }

    /** Подключение шаблона компонента
     * @return array|mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function executeComponent()
    {
        $this->arResult["ITEMS"] = $this->getSections();

        if($this->APPLICATION->GetShowIncludeAreas() && isset($this->arParams["IBLOCK_ID"])) {
            $arButtons = CIBlock::GetPanelButtons(
                $this->arParams["IBLOCK_ID"],
                0,
                0,
                array("SECTION_BUTTONS"=>true)
            );

            if($this->APPLICATION->GetShowIncludeAreas())
                $this->addIncludeAreaIcons(CIBlock::GetComponentMenu($this->APPLICATION->GetPublicShowMode(), $arButtons));
        }

        $this->includeComponentTemplate();
        return $this->arResult;
    }

    public function getSectionsForMenu($arParams){
        $this->arParams = $this->onPrepareComponentParams($arParams);
        $this->arParams['FIELD_CODE'] = array_merge($arParams['PARAMS_CATALOG_FIELD_CODE'], ["ID","IBLOCK_SECTION_ID","DEPTH_LEVEL"]);
        $this->arParams["SHOW_ONLY_ACTIVE"]= $arParams['PARAMS_CATALOG_SHOW_ONLY_ACTIVE'];
        $this->arParams["FIELD_CODE"]['SECTION_PAGE_URL'] = 'IBLOCK.SECTION_PAGE_URL';
        $this->arParams["MAX_DEPTH"]= $arParams['PARAMS_CATALOG_MAX_DEPTH']; #глубина вложенности каталога


        $this->sectionsMenu = $this->getSections();

        if($this->sectionsMenu) {
            $this->arResult = $this->getTreeMenuCatalog($this->sectionsMenu);
        }
        return $this->arResult;
    }

    public function getTreeMenuCatalog($arrMenu){
        $parents = [];
        foreach ($arrMenu as $key => $item):
            $item['SELECTED'] = $this->isSelected($item['SECTION_PAGE_URL']);
        if($this->arParams['CATALOG_ONLY']!='Y') {
            $item['DEPTH_LEVEL']
                = ++$item['DEPTH_LEVEL']; # Для каталога уровень вложенности нужно увеличивать на 1 т.к. над ним уже есть родительское меню
        }
            if($item['IBLOCK_SECTION_ID']==null) {
                $item['IBLOCK_SECTION_ID'] = 0;
            }
            $parents[$item['IBLOCK_SECTION_ID']][$item['ID']] = $item;
        endforeach;

        $treeElem = $parents[0];
        $this->generateElemTreeMenuCatalog($treeElem, $parents);
        return $treeElem;
    }

    private function generateElemTreeMenuCatalog(&$treeElem, $parents)
    {
        foreach ($treeElem as $key => $item):
            if (array_key_exists($key, $parents)):
                $treeElem[$key]['IS_PARENT'] = true;
                $treeElem[$key]['CHILD'] = $parents[$key];

                $this->generateElemTreeMenuCatalog($treeElem[$key]['CHILD'], $parents);
            endif;
        endforeach;
    }

    protected function isSelected($url)
    {
        if ($this->APPLICATION->GetCurPage() == $url) {
            return true;
        }

        return false;
    }
}
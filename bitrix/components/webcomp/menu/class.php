<?php

use Bitrix\Main\Loader,
    Bitrix\Main\SystemException,
    Bitrix\Main\IO,
    Bitrix\Main\Application;

class MenuComponent extends CBitrixComponent
{
    protected $APPLICATION;
    protected $CMenu;
    protected $depthLevel;
    protected $menuIssetInDir;
    private $server;
    private $aMenuLinks;
    private $isCached = false;
    /**
     * @var bool
     */
    private $showChildMenu;
    /**
     * @var array
     */
    private $catalogMenu;

    /**
     * Обработка arParams перед работой компонента. Если нужна
     *
     * @param $arParams
     *
     * @return array
     * @throws SystemException
     */
    public function onPrepareComponentParams($arParams)
    {
        $arParams['TYPE_MENU'] = (string)$arParams['TYPE_MENU'] != ''
            ? (string)$arParams['TYPE_MENU'] : 'left';
        $arParams['MAX_DEPTH'] = (int)$arParams['MAX_DEPTH'] != 0
            ? (int)$arParams['MAX_DEPTH'] : 1;
        if ($arParams['START_DIRECTORY'] == 'THIS_DIR') {
            $arParams['START_DIRECTORY']
                = \Webcomp\Market\Tools::delRepeatSlashes(Application::getInstance()
                ->getContext()->getRequest()->getRequestedPageDirectory());
        }
        $arParams['START_DIRECTORY'] = (string)$arParams['START_DIRECTORY']
        != '' ? (string)$arParams['START_DIRECTORY'] : '/';

        $arParams['MENU_TITLE'] = $arParams['MENU_TITLE'] ?: "";

        return $arParams;
    }

    /**
     * Возврат готового меню
     *
     */
    public function init()
    {

        global $APPLICATION;
        $this->APPLICATION = $APPLICATION;
        $this->depthLevel = 0;
        $this->server = \Bitrix\Main\Context::getCurrent()->getServer();
        $this->CMenu = new \CMenu($this->arParams['TYPE_MENU']);

        if ($this->startResultCache()) {

            #Формирование меню без каталога
            $this->menuIssetInDir = $this->CMenu->Init($this->arParams['START_DIRECTORY'], true);

            // Тут содержатся все пункты меню, включая расширенные
            $this->aMenuLinksExt = $this->CMenu->arMenu;

            if ($this->menuIssetInDir) {
                if ($this->arParams['USE_CATALOG'] === 'Y'
                    && !empty($this->arParams['CATALOG_PATH'])
                ) {
                    $this->catalogMenu = $this->getCatalogMenu();
                }
                if ($this->arParams['CATALOG_ONLY'] == 'Y') {
                    return $this->catalogMenu;
                }

                return $this->createMenuArrOnDir($this->CMenu->MenuDir);
            }
        } else {
            $this->isCached = true;
        }
    }

    /**
     * Метод строит меню для указанного раздела с одним типом меню
     *
     * @param        $dir
     *
     * @param string $fileMenuPath
     *
     * @return array
     * @throws IO\FileNotFoundException
     */
    protected function createMenuArrOnDir($dir, $recourse = false)
    {
        if ($dir == "/" && $recourse == true)
            return;

        $dir = \Webcomp\Market\Tools::delRepeatSlashes($dir);

        $fileMenuPath = $this->fileMenuPath($dir);
        $fileMenuExpPath = $this->fileMenuPath($dir, true);

        if (!$menuThisLevelTmp)
            $menuThisLevelTmp = [];
        $tmpVariableName = '$this->aMenuLinks' . $this->depthLevel;
        if ($fileMenuPath) {
            include($fileMenuPath);

            // include menu_ext if exists file
            if($fileMenuExpPath) {
                include($fileMenuExpPath);
            }

            $$tmpVariableName = $aMenuLinks;
            $this->depthLevel++;

            foreach ($$tmpVariableName as $menuElement) {

                $menuThisLevelTmp[] = [
                    'NAME' => $menuElement[0],
                    'LINK' => $menuElement[1],
                    'SELECTED' => $this->isSelected(\Webcomp\Market\Tools::delRepeatSlashes($menuElement[1])),
                    "DEPTH_LEVEL" => $this->depthLevel,
                    "CATALOG_LEVEL" => $menuElement[1] == $this->arParams['CATALOG_PATH']
                ];
                $catalogLevel = ($menuElement[1] == $this->arParams['CATALOG_PATH']);

                if ($this->depthLevel < $this->arParams['MAX_DEPTH'] #не выходим за пределы вложенности
                    && ($this->fileMenuPath($menuElement[1]) || $catalogLevel) #проверка на существование файла меню в разделе / либо это каталог
                ) {
                    foreach ($menuThisLevelTmp as &$menuThisLevelTmpElement) {

                        #Если ссылка совпадает с каталогом то меню наполняяем каталогом
                        if ($menuThisLevelTmpElement['LINK'] == $menuElement[1]) {
                            if (!empty($tmpMenuLevel
                                = $this->createMenuArrOnDir($menuThisLevelTmpElement['LINK'],
                                true))
                            ) {
                                $menuThisLevelTmpElement['CHILD']
                                    = $tmpMenuLevel;
                                foreach ($tmpMenuLevel as $childMenu) {
                                    if ($childMenu["SELECTED"]
                                        || $childMenu["CHILD_SELECTED"]
                                    ) {
                                        $menuThisLevelTmpElement['CHILD_SELECTED']
                                            = true;
                                        break;
                                    }
                                }
                                $menuThisLevelTmpElement['IS_PARENT'] = true;
                            }
                        }

                        if ($menuThisLevelTmpElement["CATALOG_LEVEL"]) {
                            if (!empty($this->catalogMenu)) {
                                $menuThisLevelTmpElement['CHILD']
                                    = $this->catalogMenu;
                                $menuThisLevelTmpElement['IS_PARENT'] = true;
                                continue;
                            }
                        }
                    }

                }
            }
            unset($$tmpVariableName);
            $this->depthLevel--;
        }
        return $menuThisLevelTmp;
    }


    protected function fileMenuPath($dir, $ext = false)
    {
        $menu = ($ext) ? "menu_ext" : "menu";
        $fileMenuPath = Application::getDocumentRoot() . $dir . ".{$this->arParams['TYPE_MENU']}.$menu.php";
        $fileMenuObj = new IO\File($fileMenuPath);
        if ($fileMenuObj->isExists()) {
            return $fileMenuPath;
        } else {
            return false;
        }
    }

    /**
     * Определение раздела selected todo::пока только раздел со слешем на конце, но надо чтобы определялся и .php и без слеша
     *
     * @param $url
     *
     * @return bool
     */
    protected function isSelected($url)
    {
        if ($this->APPLICATION->GetCurPage() == $url) {
            return true;
        }

        return false;
    }

    /**
     * Создаем меню из разделов каталога с такой же структурой как и простое меню
     *
     * @param $arParamsCatalogMenu
     * @param $MENU
     */

    public function getCatalogMenu()
    {
        CBitrixComponent::includeComponentClass("webcomp:section.getList");
        $SectionGetListComponent = new SectionGetListComponent();
        return $SectionGetListComponent->getSectionsForMenu($this->arParams);
    }

    public function AddPanelButton()
    {
        global $APPLICATION, $USER;

        if ($USER->IsAuthorized() && !$APPLICATION->GetShowIncludeAreas())
            return;

        $menuType = $this->CMenu->type;

        $curDir = $APPLICATION->GetCurDir();
        $menuDir = $this->CMenu->MenuDir;

        $arMenuTypes = GetMenuTypes(SITE_ID);

        $bDefaultItem = ($curDir == "/" && $menuType == "top" || $curDir <> "/" && $menuType == "left");
        $buttonID = "menus";

        $menu_edit_url = $APPLICATION->GetPopupLink([
                "URL" => "/bitrix/admin/public_menu_edit.php?lang=" . LANGUAGE_ID .
                    "&site=" . SITE_ID . "&back_url=" . urlencode($_SERVER["REQUEST_URI"]) .
                    "&path=" . urlencode($menuDir) . "&name=" . $menuType
            ]
        );

        //Icons
        $arIcons[] = [
            "URL" => 'javascript:' . $menu_edit_url,
            "ICON" => "bx-context-toolbar-edit-icon",
            "TITLE" => GetMessage("MAIN_MENU_EDIT"),
            "DEFAULT" => true,
        ];

        //panel
        $static_var_name = 'BX_TOPPANEL_MENU_EDIT_' . $menuType;

        if (!defined($static_var_name)) {
            define($static_var_name, 1);

            $APPLICATION->AddPanelButton([
                "HREF" => ($bDefaultItem ? 'javascript:' . $menu_edit_url : ''),
                "ID" => "menus",
                "ICON" => "bx-panel-menu-icon",
                "ALT" => GetMessage('MAIN_MENU_TOP_PANEL_BUTTON_ALT')
                    . ($bDefaultItem ? ' ' . '"' . (isset($arMenuTypes[$menuType]) ? $arMenuTypes[$menuType] : $menuType) . '"' : ''),
                "TEXT" => GetMessage("MAIN_MENU_TOP_PANEL_BUTTON_TEXT"),
                "MAIN_SORT" => "300",
                "SORT" => 10,
                "RESORT_MENU" => true,
                "HINT" => [
                    "TITLE" => GetMessage('MAIN_MENU_TOP_PANEL_BUTTON_TEXT'),
                    "TEXT" => GetMessage('MAIN_MENU_TOP_PANEL_BUTTON_HINT'),
                ]
            ], $bDefaultItem);

            $aMenuItem = [
                "TEXT" => GetMessage(
                    'MAIN_MENU_TOP_PANEL_ITEM_TEXT',
                    ['#MENU_TITLE#' => (isset($arMenuTypes[$menuType]) ? $arMenuTypes[$menuType] : $menuType)]
                ),
                "TITLE" => GetMessage(
                    'MAIN_MENU_TOP_PANEL_ITEM_ALT',
                    ['#MENU_TITLE#' => (isset($arMenuTypes[$menuType]) ? $arMenuTypes[$menuType] : $menuType)]
                ),
                "SORT" => "100",
                "ICON" => "menu-edit",
                "ACTION" => $menu_edit_url,
                "DEFAULT" => $bDefaultItem,
            ];

            $APPLICATION->AddPanelButtonMenu($buttonID, $aMenuItem);

        }

        $this->AddIncludeAreaIcons($arIcons);

    }

    public function executeComponent()
    {
        $this->arResult['ITEMS'] = $this->init();
        $this->AddPanelButton();
        if (!$this->isCached)
            $this->includeComponentTemplate();
    }

}


<?
foreach($arResult['ITEMS'] as $arItem){
    if($SID = $arItem['IBLOCK_SECTION_ID']){
        $arSectionsIDs[] = $SID;
    }
}

if($arSectionsIDs){
    $sectList = CIBlockSection::GetList(['SORT' => 'ASC', 'ID' => 'DESC'], array("IBLOCK_ID"=> $arParams["IBLOCK_ID"], "ACTIVE"=>"Y") ,false, array("ID","IBLOCK_ID","IBLOCK_TYPE_ID","IBLOCK_SECTION_ID","CODE","SECTION_ID","NAME", "DESCRIPTION"));
    while ($sectListGet = $sectList->GetNext()) {
        $arResult['SECTIONS'][$sectListGet["ID"]] = $sectListGet;
    }
}

// group elements by sections
foreach($arResult['ITEMS'] as $arItem){
    $SID = ($arItem['IBLOCK_SECTION_ID'] ? $arItem['IBLOCK_SECTION_ID'] : 0);
    $arResult['SECTIONS'][$SID]['ITEMS'][$arItem['ID']] = $arItem;
}

// unset empty sections
if(is_array($arResult['SECTIONS'])){
    foreach($arResult['SECTIONS'] as $i => $arSection){
        if(!$arSection['ITEMS']){
            unset($arResult['SECTIONS'][$i]);
        }
    }
}
?>

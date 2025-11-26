<?
/*  // example include this type field
    [
        "NAME" => "name input (any string)",
        "TITLE" => "title block (any string)",
        "TYPE" => "EDITOR",
        "SORT" => 10 (number),
        "NOT_USE_HTML" => "use editor html or not (Y|N)",
        "FILE" => "name file from /include/ directory (fileName with extension)",
        "DESCRIPTION" => [
            "TITLE" => "title description in modal window (any string)",
            "LINK"  => [
                "TEXT" => "text link (any string)",
                "HREF" => "url address link (url string)",
            ]
        ],
        "STYLE" => [
            "CLASS" => "addition class (any string)",
            "BOLD" => "bold title or not (Y|N)",
            "DATA" => "array data attribute (array[id => 10]) - data-id=10)",
            "DISABLED" => "disabled field or not (Y|N)",
        ],
        "INFO" => "addition message info block with text (string)"
    ],
 */
?>

<? include_once 'function.php' ?>

<div class="webcomp-settings__field webcomp-settings__field--editor <?=$arOption["CLASS"]?>">
    <div class="webcomp-settings__field-title"><?=$arOption["TITLE"]?></div>
    <div class="webcomp-settings__field-wrap">
        <a class="adm-btn webcomp-settings__field-editor-btn"
           href = "javascript: new BX.CAdminDialog({'content_url':'/bitrix/admin/public_file_edit.php?site=s1&amp;bxpublic=Y&amp;from=includefile&amp;noeditor=<?=$arOption["NOT_USE_HTML"]?>&amp;templateID=1&amp;path=/include/<?=$arOption["FILE"]?>&amp;lang=ru&amp;template=&amp;subdialog=Y&amp;siteTemplateId=1','width':'1009','height':'503'}).Show();" name = "<?=$arOption["NAME"]?>" title="<?=$editTitle?>"><?=$editTitle?></a>
            <input type = "hidden" name = "<?=$arOption["NAME"]?>" value = "<?=$arOption["FILE"]?>">

    </div>
    <?=getInfoBlock($arOption["INFO"])?>
</div>

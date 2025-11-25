<?
/*  // example include this type field
    [
        "NAME" => "name input (any string)",
        "TITLE" => "title block (any string)",
        "TYPE" => "CHECKBOX",
        "SORT" => 10 (number),
        "DEFAULT" => "default value input (Y|N)",
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

<div class="webcomp-settings__field webcomp-settings__field--checkbox <?=$arOption["CLASS"]?>">
    <div class="webcomp-settings__field-wrap">
        <div class="value_wrapper">
            <input type="checkbox"
                   id="<?=$arOption["NAME"]?>"
                   name="<?=$arOption["NAME"]?>"
                   value="Y"
                   class="adm-designed-checkbox"
                <?=$arOption["CHECKED"]?>
                <?=$arOption["DISABLED"]?>>
            <label class="adm-designed-checkbox-label" for="<?=$arOption["NAME"]?>"></label>
        </div>
    </div>
    <div class="webcomp-settings__field-title"><?=$arOption["TITLE"]?></div>
    <?=getInfoBlock($arOption["INFO"])?>
</div>

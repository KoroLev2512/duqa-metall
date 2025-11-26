<?
/*  // example include this type field
    [
        "NAME" => "name input (any string)",
        "TITLE" => "title block (any string)",
        "TYPE" => "SELECT",
        "SORT" => 10 (number),
        "DEFAULT" => "default value input (any)",
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
            "WIDTH" => "input width (SMALL|MEDIUM|FULL)",
            "DATA" => "array data attribute (array[id => 10]) - data-id=10)",
            "DISABLED" => "disabled field or not (Y|N)",
        ],
        "INFO" => "addition message info block with text (string)"
    ],
 */
?>

<? include_once 'function.php' ?>

<div class="webcomp-settings__field webcomp-settings__field--select <?=$arOption["CLASS"]?>">
    <div class="webcomp-settings__field-title"><?=$arOption["TITLE"]?></div>
    <div class="webcomp-settings__field-wrap">
        <select name="<?=$arOption["NAME"]?>" class="typeselect" <?=$arOption["DATA"]?>>
            <? foreach ($arOption["arVALUES"] as $key => $value): ?>
                <? $optionSelected = ($key == $arOption["VALUE"]) ? "selected" : ""; ?>
                <option value="<?=$key?>" <?=$optionSelected?>><?=$value?></option>
            <? endforeach ?>
        </select>
    </div>
    <?=getInfoBlock($arOption["INFO"])?>
</div>

<?
/*  // example include this type field
    [
        "NAME" => "name input (any string)",
        "TITLE" => "title block (any string)",
        "TYPE" => "SELECT_THEME",
        "SORT" => 10 (number),
        "DEFAULT" => "default value input (number)",
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

<div class="webcomp-settings__field webcomp-settings__field--select-color <?=$arOption["CLASS"]?>">
    <div class="webcomp-settings__field-title"><?=$arOption["TITLE"]?></div>
    <div class="webcomp-settings__field-wrap">
        <div class="settings__theme-row">
            <? foreach ($arOption["arVALUES"] as $key => $value): ?>
                <? $optionSelected = ($key == $arOption["VALUE"]) ? "checked" : ""; ?>
                <div class="settings__theme-item">
                    <input class="settings__theme-input"
                           id="theme_<?=$key?>"
                           type="radio"
                           value="<?=$key?>"
                           name = "<?=$arOption["NAME"]?>"
                        <?=$arOption["DATA"]?>
                        <?=$optionSelected?>>
                    <label for="theme_<?=$key?>" class="settings__theme-label">
                        <span class="settings__theme-color" style="background-color: <?=$value?>"></span>
                    </label>
                </div >
            <? endforeach ?>

        </div>
    </div>
    <?=getInfoBlock($arOption["INFO"])?>
</div>

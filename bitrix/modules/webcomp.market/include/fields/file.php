<?
/*  // example include this type field
    [
        "NAME" => "name input (any string)",
        "TITLE" => "title block (any string)",
        "TYPE" => "FILE",
        "SORT" => 10 (number),
        "DEFAULT" => "default value input (any)",
        "WITH_DESCRIPTION" => "description in file (Y|N)",
        "MULTIPLE" => "multiple file (Y|N)",
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

<?
    use Webcomp\Market\Settings;
    include_once 'function.php'
?>

<div class="webcomp-settings__field webcomp-settings__field--file <?=$arResult["CLASS"]?>">
    <div class="webcomp-settings__field-title"><?=$arResult["TITLE"]?></div>
    <div class="webcomp-settings__field-wrap">
        <? Settings::ShowFilePropertyField($arResult["NAME"], $arOption, $arResult["VALUE"]) ?>
    </div>
    <?=getInfoBlock($arOption["INFO"])?>
</div>

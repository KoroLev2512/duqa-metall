<?
/*  // example include this type field
    [
        "NAME" => "name input (any string)",
        "TITLE" => "title block (any string)",
        "TYPE" => "VIEW",
        "SORT" => 10 (number),
        "DEFAULT" => "default value input (any)",
        "DESCRIPTION" => [
            "TITLE" => "title description in modal window (any string)",
            "LINK"  => [
                "TEXT" => "text link (any string)",
                "HREF" => "url address link (url string)",
            ]
        ],
        "VALUES" => [
            "VALUES" => [
                "1" => [
                    "TITLE" => "Темный информационный",
                    "VALUE" => "v1",
                    "IMAGE" => "/bitrix/images/webcomp.market/views/footer/v1.png"
                ],
                "2" => [
                    "TITLE" => "Темный компактный",
                    "VALUE" => "v2",
                    "IMAGE" => "/bitrix/images/webcomp.market/views/footer/v2.png"
                ],
            ],
        ]
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
<div class="webcomp-settings__field webcomp-settings__field--view <?=$arOption["CLASS"]?>">
    <div class="webcomp-settings__field-title"><?=$arOption["TITLE"]?></div>
    <div class="webcomp-settings__field-wrap webcomp-settings__view-wrap">
        <? foreach ($arOption["arVALUES"] as $id => $item): ?>
        <? $checked = ($arOption["VALUE"] === $item["VALUE"]) ? "checked" : ""?>
        <input type="radio" class="view-input"
               id="<?=$arOption["NAME"]."_".$id?>"
               name="<?=$arOption["NAME"]?>"
               value="<?=$item["VALUE"]?>"
               <?=$checked?>
        >
        <div class="webcomp-settings__view-item" title="<?=$item["TITLE"]?>">
            <label for="<?=$arOption["NAME"]."_".$id?>" class="webcomp-settings__view-label">
                <div class="webcomp-settings__field-title"><?=$item["TITLE"]?></div>
                <img class="webcomp-settings__view-image" src="<?=$item["IMAGE"]?>" alt="<?=$item["TITLE"]?>">
            </label>
        </div>
        <? endforeach ?>
    </div>
    <?=getInfoBlock($arOption["INFO"])?>
</div>

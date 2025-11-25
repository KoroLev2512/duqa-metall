<?
/*  // example include this type field
    [
        "TITLE" => "title block (any string)",
        "TYPE" => "HEADING",
        "SORT" => 10 (number),
        "DESCRIPTION" => [
            "TITLE" => "title description in modal window (any string)",
            "LINK"  => [
                "TEXT" => "text link (any string)",
                "HREF" => "url address link (url string)",
            ]
        ],
        "STYLE" => [
            "CLASS" => "addition class (any string)",
            "BOLD" => "bold title or not (Y|N)"
        ],
    ],
 */
?>

<?
    include_once 'function.php';
?>

<div class="webcomp-settings__field-heading <?=getCssClass($arOption["STYLE"])?>">
    <?=$arOption["TITLE"]?>
    <?=getDescription($arOption["DESCRIPTION"])?>
</div>

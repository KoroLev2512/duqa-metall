<?
/*  // example include this type field
    [
        "NAME" => "name input (any string)",
        "TITLE" => "title block (any string)",
        "TYPE" => "SECTIONS",
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
        0 => [
            "NAME" => Loc::getMessage("WEBCOMP_MARKET_ADVANTAGES"),
            "ID" => 'advantages',
            "ORDER" => 0,
            "CHECKED" => "checked"
        ],
        1 => [
            "NAME" => Loc::getMessage("WEBCOMP_MARKET_SERVICES"),
            "ID" => 'services',
            "ORDER" => 1,
            "CHECKED" => "checked"
        ],
        2 => [
            "NAME" => Loc::getMessage("WEBCOMP_MARKET_PROJECTS"),
            "ID" => 'projects',
            "ORDER" => 2,
            "CHECKED" => "checked"
        ],
        3 => [
            "NAME" => Loc::getMessage("WEBCOMP_MARKET_POPULAR_WORKS"),
            "ID" => 'popular',
            "ORDER" => 3,
            "CHECKED" => "checked"
        ],
        4 => [
            "NAME" => Loc::getMessage("WEBCOMP_MARKET_GOOD_OFFERS"),
            "ID" => 'reccomended',
            "ORDER" => 4,
            "CHECKED" => "checked"
        ],
        5 => [
            "NAME" => Loc::getMessage("WEBCOMP_MARKET_PROMO_BANNER"),
            "ID" => 'promo',
            "ORDER" => 5,
            "CHECKED" => "checked"
        ],
        6 => [
            "NAME" => Loc::getMessage("WEBCOMP_MARKET_POPULAR_CATEGORY"),
            "ID" => 'categories',
            "ORDER" => 6,
            "CHECKED" => "checked"
        ],
        7 => [
            "NAME" => Loc::getMessage("WEBCOMP_MARKET_PROMOTION"),
            "ID" => 'actions',
            "ORDER" => 7,
            "CHECKED" => "checked"
        ],
        8 => [
            "NAME" => Loc::getMessage("WEBCOMP_MARKET_NEWS"),
            "ID" => 'news',
            "ORDER" => 8,
            "CHECKED" => "checked"
        ],
        9 => [
            "NAME" => Loc::getMessage("WEBCOMP_MARKET_ABOUT"),
            "ID" => 'about',
            "ORDER" => 9,
            "CHECKED" => "checked"
        ],
        10 => [
            "NAME" => Loc::getMessage("WEBCOMP_MARKET_REVIEWS"),
            "ID" => 'reviews',
            "ORDER" => 10,
            "CHECKED" => "checked"
        ],
        11 => [
            "NAME" => Loc::getMessage("WEBCOMP_MARKET_BRANDS"),
            "ID" => 'brands',
            "ORDER" => 11,
            "CHECKED" => "checked"
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

<div class="webcomp-settings__field webcomp-settings__field--select <?=$arOption["CLASS"]?>">
    <div class="webcomp-settings__field-wrap">
        <div class="admin-sections">

            <?
            $arr = $arOption["arVALUES"];
            $data = unserialize($arOption["VALUE"]);
            if (!empty($data)){
                $arr = [];
                foreach ($data["ORDER"] as $i){
                    $x = array_search($i,array_column($arOption["arVALUES"],"ID"));
                    $arr[] = $arOption["arVALUES"][$x];
                }
            }

            foreach ($arr as $key => $value):
                $checked = $value["CHECKED"];
                $order = $value["ORDER"];

                if (!empty($data)) {
                    $checked = (in_array($value["ID"], $data['ID']) ? "checked" : "");
                    $order = array_search($value["ID"],$data["ORDER"]);
                }
                ?>
                <div class="admin-sections__section admin-section" data-order="<?=$order?>">
                    <div class="admin-section__btns">
                        <button class="admin-section__btn admin-section__btn--up" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px"
                                 viewBox="0 0 512.171 512.171">
                                <path d="M476.723,216.64L263.305,3.115C261.299,1.109,258.59,0,255.753,0c-2.837,0-5.547,1.131-7.552,3.136L35.422,216.64
                                c-3.051,3.051-3.947,7.637-2.304,11.627c1.664,3.989,5.547,6.571,9.856,6.571h117.333v266.667c0,5.888,4.779,10.667,10.667,10.667
                                h170.667c5.888,0,10.667-4.779,10.667-10.667V234.837h116.885c4.309,0,8.192-2.603,9.856-6.592
                                C480.713,224.256,479.774,219.691,476.723,216.64z"/>
                            </svg>
                        </button>
                        <button class="admin-section__btn admin-section__btn--down" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px"
                                 viewBox="0 0 512.171 512.171">
                                <path d="M476.723,216.64L263.305,3.115C261.299,1.109,258.59,0,255.753,0c-2.837,0-5.547,1.131-7.552,3.136L35.422,216.64
                                c-3.051,3.051-3.947,7.637-2.304,11.627c1.664,3.989,5.547,6.571,9.856,6.571h117.333v266.667c0,5.888,4.779,10.667,10.667,10.667
                                h170.667c5.888,0,10.667-4.779,10.667-10.667V234.837h116.885c4.309,0,8.192-2.603,9.856-6.592
                                C480.713,224.256,479.774,219.691,476.723,216.64z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="admin-section__left">
                        <div class="value_wrapper">
                            <input type="checkbox"
                                   id="<?= $arOption["NAME"] ?>-<?= $key ?>"
                                   name="<?= $arOption["NAME"] ?>[ID][]"
                                   value="<?= $value["ID"]?>" class="admin-section__input adm-designed-checkbox" <?= $checked ?>>
                            <label class="adm-designed-checkbox-label" for="<?= $arOption["NAME"] ?>-<?= $key ?>"></label>
                        </div>
                        <input type="hidden" name="<?= $arOption["NAME"] ?>[ORDER][]"
                               value="<?= $value["ID"]?>">
                    </div>
                    <div class="admin-section__right">
                        <label for="<?= $arOption["NAME"] ?>-<?= $key ?>" class="admin-section__label">
                            <?= $value["NAME"]?>
                        </label>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
    </div>
    <?=getInfoBlock($arOption["INFO"])?>
</div>

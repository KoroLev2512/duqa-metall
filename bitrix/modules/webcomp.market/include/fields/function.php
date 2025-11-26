<?
    if(!function_exists("getCssClass")) {
        function getCssClass($arStyle) {
            if(!empty($arStyle)) {
                $class = [];
                if(!empty($arStyle["CLASS"])) {
                    $class[] = $arStyle["CLASS"];
                }

                if($arStyle["BOLD"] === "Y") {
                    $class[] = 'field-heading--bold';
                }

                return implode(' ', $class);
            }
        }
    }

    if(!function_exists("getDescription")) {
        function getDescription($arDescription) {
            if(!empty($arDescription)) {
                $description = "";
                if((!empty($arDescription["TITLE"]))) {
                    $description = $arDescription["TITLE"];
                }

                if(!empty($arDescription["LINK"])) {
                    $description .= ' <a target="_blank" href="'.$arDescription["LINK"]["HREF"].'">'.$arDescription["LINK"]["TEXT"].'</a>';
                }

                $html = "";
                $html .= '<span class="webcomp-settings__field-description">';
                $html .= '<span class="webcomp-settings__description-symbol">?</span>';
                $html .= '</span>';
                $html .= '<span class="webcomp-settings__description-hidden">'.$description.'</span>';
                return $html;
            }
        }
    }

    if(!function_exists("getInfoBlock")) {
        function getInfoBlock($arInfo) {
            if(!empty($arInfo)) {
                return '<div class="webcomp-settings__field-info"><div class="adm-info-message">'.$arInfo.'</div></div>';
            }
        }
    }
?>

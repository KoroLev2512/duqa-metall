<?
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var array $arResult
 * @var array $arParam
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

if ( ! $arResult["NavShowAlways"]) {
    if ($arResult["NavRecordCount"] == 0
        || ($arResult["NavPageCount"] == 1
            && $arResult["NavShowAll"] == false)
    ) {
        return;
    }
}
?>
<div class="page-pagination">
    <?
    $strNavQueryString = ($arResult["NavQueryString"] != ""
        ? $arResult["NavQueryString"]."&amp;" : "");
    $strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?"
        .$arResult["NavQueryString"] : "");
    ?>

    <?
    if ($arResult["bDescPageNumbering"] === true):
        ?>
        <div class="main-ui-pagination-pages">

            <div class="pagination">
                <?
                if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
                    if ($arResult["nStartPage"] < $arResult["NavPageCount"]):
                        if ($arResult["bSavePage"]):
                            ?>
                            <a class="main-ui-pagination-page"
                               href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["NavPageCount"] ?>">1</a>
                        <?
                        else:
                            ?>
                            <a class="main-ui-pagination-page"
                               href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>">1</a>
                        <?
                        endif;
                        if ($arResult["nStartPage"] < ($arResult["NavPageCount"]
                                - 1)
                        ):
                            ?>
                            <a class="main-ui-pagination-page main-ui-pagination-dots"
                               href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= intVal($arResult["nStartPage"]
                                   + ($arResult["NavPageCount"]
                                       - $arResult["nStartPage"])
                                   / 2) ?>">...</a>
                        <?
                        endif;
                    endif;
                endif;

                do {
                    $NavRecordGroupPrint = $arResult["NavPageCount"]
                        - $arResult["nStartPage"] + 1;

                    if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
                        ?>
                        <span class="main-ui-pagination-page main-ui-pagination-active"><?= $NavRecordGroupPrint ?></span>
                    <?
                    elseif ($arResult["nStartPage"] == $arResult["NavPageCount"]
                        && $arResult["bSavePage"] == false
                    ):
                        ?>
                        <a class="main-ui-pagination-page"
                           href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>"><?= $NavRecordGroupPrint ?></a>
                    <?
                    else:
                        ?>
                        <a class="main-ui-pagination-page"
                           href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["nStartPage"] ?>"><?= $NavRecordGroupPrint ?></a>
                    <?
                    endif;

                    $arResult["nStartPage"]--;
                } while ($arResult["nStartPage"] >= $arResult["nEndPage"]);

                if ($arResult["NavPageNomer"] > 1):
                    if ($arResult["nEndPage"] > 1):
                        if ($arResult["nEndPage"] > 2):
                            ?>
                            <a class="main-ui-pagination-page main-ui-pagination-dots"
                               href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= round($arResult["nEndPage"]
                                   / 2) ?>">...</a>
                        <?
                        endif;
                        ?>
                        <a class="main-ui-pagination-page"
                           href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=1"><?= $arResult["NavPageCount"] ?></a>
                    <?
                    endif;
                endif;
                ?>
            </div>
        </div>

        <?
        if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
            if ($arResult["bSavePage"]):
                ?>
                <a class="main-ui-pagination-arrow main-ui-pagination-prev"
                   href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"]
                       + 1) ?>"><?= GetMessage("MAIN_UI_PAGINATION__PREV") ?></a>
            <?
            else:
                if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"]
                        + 1)
                ):
                    ?>
                    <a class="main-ui-pagination-arrow main-ui-pagination-prev"
                       href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>"><?= GetMessage("MAIN_UI_PAGINATION__PREV") ?></a>
                <?
                else:
                    ?>
                    <a class="main-ui-pagination-arrow main-ui-pagination-prev"
                       href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"]
                           + 1) ?>"><?= GetMessage("MAIN_UI_PAGINATION__PREV") ?></a>
                <?
                endif;
            endif;
        else:
            ?>
            <span class="main-ui-pagination-arrow main-ui-pagination-prev"><?= GetMessage("MAIN_UI_PAGINATION__PREV") ?></span>
        <?
        endif;

        if ($arResult["bShowAll"]):
            if ($arResult["NavShowAll"]):
                ?>
                <a class="main-ui-pagination-arrow"
                   href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>SHOWALL_<?= $arResult["NavNum"] ?>=0"><?= GetMessage("MAIN_UI_PAGINATION__PAGED") ?></a>
            <?
            else:
                ?>
                <a class="main-ui-pagination-arrow"
                   href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>SHOWALL_<?= $arResult["NavNum"] ?>=1"><?= GetMessage("MAIN_UI_PAGINATION__ALL") ?></a>
            <?
            endif;
        endif;

        if ($arResult["NavPageNomer"] > 1):
            ?>
            <a class="main-ui-pagination-arrow main-ui-pagination-next"
               href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"]
                   - 1) ?>"><?= GetMessage("MAIN_UI_PAGINATION__NEXT") ?></a>
        <?
        else:
            ?>
            <span class="main-ui-pagination-arrow main-ui-pagination-next"><?= GetMessage("MAIN_UI_PAGINATION__NEXT") ?></span>
        <?
        endif;

    else:
    ?>

    <div class="main-ui-pagination-pages">

        <div class="pagination">

            <!--- start_prev----->
            <?
            if ($arResult["NavPageNomer"] > 1):
                if ($arResult["bSavePage"]):
                    ?>

                    <a class="pagination__item pagination__item_left"
                       href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"]
                           - 1) ?>">
                        <?= CMarketView::showIcon("arr-l",
                            "pagination__item-svg") ?>
                    </a>

                <?
                else:
                    if ($arResult["NavPageNomer"] > 2):
                        ?>
                        <a class="pagination__item pagination__item_left"
                           href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"]
                               - 1) ?>">
                            <?= CMarketView::showIcon("arr-l",
                                "pagination__item-svg") ?>
                        </a>


                    <?
                    else:
                        ?>
                        <a class="pagination__item pagination__item_left"
                           href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>">
                            <?= CMarketView::showIcon("arr-l",
                                "pagination__item-svg") ?>
                        </a>

                    <?
                    endif;

                endif;
            else:
                ?>
                <span class="pagination__item pagination__item_left">
            <?= CMarketView::showIcon("arr-l", "pagination__item-svg") ?>

	  </span>
            <?
            endif;
            ?>

            <!--- -- end_prev ---->


            <?
            if ($arResult["NavPageNomer"] > 1):
                if ($arResult["nStartPage"] > 1):
                    if ($arResult["bSavePage"]):
                        ?>
                        <a class="pagination__item"
                           href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=1">
                            <span class="pagination__item-txt">1</span>
                        </a>
                    <?
                    else:
                        ?>
                        <a class="pagination__item"
                           href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>">
                            <span class="pagination__item-txt">1</span>
                        </a>

                    <?
                    endif;
                    if ($arResult["nStartPage"] > 2):
                        ?>
                        <a class="pagination__item"
                           href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= round($arResult["nStartPage"]
                               / 2) ?>">
                            <span class="pagination__item-txt">...</span>
                        </a>
                    <?
                    endif;
                endif;
            endif;

            ?>

            <?

            do {
                if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
                    ?>
                    <span class="pagination__item active">
        	<span class="pagination__item-txt"><?= $arResult["nStartPage"] ?></span>
      </span>

                <?
                elseif ($arResult["nStartPage"] == 1
                    && $arResult["bSavePage"] == false
                ):
                    ?>
                    <a class="pagination__item"
                       href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>">
                        <span class="pagination__item-txt"><?= $arResult["nStartPage"] ?></span>
                    </a>
                <?
                else:
                    ?>
                    <a class="pagination__item"
                       href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["nStartPage"] ?>">
                        <span class="pagination__item-txt"><?= $arResult["nStartPage"] ?></span>
                    </a>
                <?
                endif;
                $arResult["nStartPage"]++;
            } while ($arResult["nStartPage"] <= $arResult["nEndPage"]);

            if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
                if ($arResult["nEndPage"] < $arResult["NavPageCount"]):
                    if ($arResult["nEndPage"] < ($arResult["NavPageCount"]
                            - 1)
                    ):
                        ?>

                        <a class="pagination__item"
                           href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= round($arResult["nEndPage"]
                               + ($arResult["NavPageCount"]
                                   - $arResult["nEndPage"]) / 2) ?>">
                            <span class="pagination__item-txt">...</span>
                        </a>
                    <?
                    endif;
                    ?>
                    <a class="pagination__item"
                       href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["NavPageCount"] ?>">
                        <span class="pagination__item-txt"><?= $arResult["NavPageCount"] ?></span>
                    </a>

                <?
                endif;
            endif;
            ?>

            <!--- start_next----->

            <?
            if ($arResult["bShowAll"]):
                if ($arResult["NavShowAll"]):
                    ?>
                    <a class="pagination__item pagination__item_right"
                       href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>SHOWALL_<?= $arResult["NavNum"] ?>=0">
                        <?= CMarketView::showIcon("arr-r",
                            "pagination__item-svg") ?>
                    </a>

                <?
                else:
                    ?>
                    <a class="pagination__item pagination__item_right"
                       href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>SHOWALL_<?= $arResult["NavNum"] ?>=1">
                        <?= CMarketView::showIcon("arr-r",
                            "pagination__item-svg") ?>
                    </a>

                <?
                endif;
            endif;

            if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
                ?>
                <a class="pagination__item pagination__item_right"
                   href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"]
                       + 1) ?>">
                    <?= CMarketView::showIcon("arr-r",
                        "pagination__item-svg") ?>
                </a>

            <?
            else:
                ?>
                <span class="pagination__item pagination__item_right">
	    <?= CMarketView::showIcon("arr-r", "pagination__item-svg") ?>
    </span>

            <?
            endif;

            endif;

            ?>
            <!--- end_next----->
        </div>
    </div>




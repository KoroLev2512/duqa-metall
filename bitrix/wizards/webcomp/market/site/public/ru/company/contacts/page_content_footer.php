<?php
?>
</div><? //row?>
</div><? //container?>
</div><? //contacts__center?>

<div class="contacts__bottom">
    <div class="container">
        <div class="row">
            <? if (in_array(7, array_column($contacts["BOTTOM_BLOCKS"]["VALUES"], "VALUE"))): ?>
                <div class="contacts__column">
                    <div class="cblock">
                        <div class="cblock__top">
                            <div class="cblock__title">
                                <? $APPLICATION->IncludeComponent("bitrix:main.include",
                                    "", [
                                        "AREA_FILE_SHOW" => "file",
                                        "PATH" => "include/requisites_title.php",
                                        "AREA_FILE_SUFFIX" => "",
                                        "AREA_FILE_RECURSIVE" => "Y",
                                        "EDIT_TEMPLATE" => "standard.php",
                                    ]
                                ); ?>
                            </div>
                            <?= CMarketView::showIcon("bag", "cblock__svg cblock__svg_bag") ?>
                        </div>
                        <div class="cblock__bottom">
                            <div class="row cblock__row">
                                <div class="cblock__col">
                                    <? $APPLICATION->IncludeComponent("bitrix:main.include",
                                        "", [
                                            "AREA_FILE_SHOW" => "file",
                                            "PATH" => "include/requisites_text.php",
                                            "AREA_FILE_SUFFIX" => "",
                                            "AREA_FILE_RECURSIVE" => "Y",
                                            "EDIT_TEMPLATE" => "standard.php",
                                        ]
                                    ); ?>
                                </div>
                                <div class="cblock__col cblock__col_g">
                                    <? $APPLICATION->IncludeComponent("bitrix:main.include",
                                        "", [
                                            "AREA_FILE_SHOW" => "file",
                                            "PATH" => "include/requisites_right_block.php",
                                            "AREA_FILE_SUFFIX" => "",
                                            "AREA_FILE_RECURSIVE" => "Y",
                                            "EDIT_TEMPLATE" => "standard.php",
                                        ]
                                    ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <? endif; ?>
            <? if (in_array(8, array_column($contacts["BOTTOM_BLOCKS"]["VALUES"], "VALUE"))): ?>
                <div class="contacts__column">
                    <div class="cblock">
                        <div class="cblock__top">
                            <div class="cblock__title"><?=getMessage('WEBCOMP_CONTACTS_WAY')?></div>
                            <?= CMarketView::showIcon("metro", "cblock__svg cblock__svg_metro") ?>
                        </div>
                        <div class="cblock__bottom">
                            <div class="content">
                                <?= $previewText; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <? endif; ?>
        </div>
    </div>
</div>

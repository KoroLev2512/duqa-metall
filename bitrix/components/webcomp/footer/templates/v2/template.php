<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Localization\Loc;
?>

<footer class="footer footer-v2">
    <div class="footer__top">
        <div class="container">
            <div class="row footer__top-row">
                <div class="footer__col">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => SITE_TEMPLATE_PATH . "/include/footer/copy.php"
                        )
                    ); ?>
                </div>

                <div class="footer__col footer__col_x2">
                    <div class="row">
                        <div class="footer__contacts">
                            <a class="footer__contacts-title" href="/company/contacts/">
                                <?= Loc::getMessage("WEBCOMP_FOOTER_CONTACTS") ?>
                            </a>

                            <div class="footer__contacts-block">
                                <div class="footer__contacts-left">
                                    <? CMarketView::showPageBlock('footer_phones', 'footer') ?>

                                    <? $APPLICATION->IncludeComponent(
                                        "bitrix:main.include",
                                        "",
                                        array(
                                            "AREA_FILE_SHOW" => "file",
                                            "AREA_FILE_SUFFIX" => "inc",
                                            "EDIT_TEMPLATE" => "",
                                            "PATH" => SITE_TEMPLATE_PATH . "/include/footer/email.php"
                                        )
                                    ); ?>
                                </div>

                                <div class="footer__contacts-right">
                                    <? $APPLICATION->IncludeComponent(
                                        "bitrix:main.include",
                                        "",
                                        array(
                                            "AREA_FILE_SHOW" => "file",
                                            "AREA_FILE_SUFFIX" => "inc",
                                            "EDIT_TEMPLATE" => "",
                                            "PATH" => SITE_TEMPLATE_PATH . "/include/footer/addr.php"
                                        )
                                    ); ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer__col">
                    <div class="footer__socials">
                        <? $APPLICATION->IncludeComponent(
                            "webcomp:highload.getList",
                            "social_bottom",
                            [
                                "CACHE_TIME"         => "36000000",
                                "CACHE_TYPE"         => "A",
                                "ELEMENTS_COUNT"     => "20",
                                "FIELD_CODE"         => [
                                    0 => "UF_NAME",
                                    1 => "UF_ICON",
                                    2 => "UF_LINK",
                                ],
                                "HLBLOCK_ID"         => $GLOBALS['WEBCOMP']['HLBLOCKS']['WebCompMarketSocial'],
                                "USE_FILTER"         => "N",
                                "USE_SORT"           => "Y",
                                "COMPONENT_TEMPLATE" => "social_bottom",
                                "SORT_FILED"         => "ID",
                                "SORT_ORDER"         => "DESC",
                                "FILTER_NAME"        => "",
                            ],
                            false
                        ); ?>

                    </div>
                </div>


            </div>
        </div>
    </div>
    <div class="footer__bottom">
        <div class="container">
            <div class="footer__bottom-row">
                <?= CMarket::showVendor() ?>
            </div>
        </div>
    </div>
</footer>



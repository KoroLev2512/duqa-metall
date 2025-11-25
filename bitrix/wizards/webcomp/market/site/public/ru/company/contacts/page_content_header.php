<?php
global $WEBCOMP;
$contacts = $WEBCOMP['CONSTANTS']['CONTACTS']['PROPERTIES'];
$map = (!empty($contacts['MAP']['VALUE']))?explode(',', $contacts['MAP']['VALUE']): null;
$previewText = $WEBCOMP['CONSTANTS']['CONTACTS']['PREVIEW_TEXT'];
?>

<div class="contacts__row">
    <div class="container">
        <div class="row">
            <? if ( ! empty($contacts['ADDRESS']['VALUE'])
                || ! empty($contacts['METRO']['VALUES'])
            ): ?>
                <div class="contacts__col">
                    <? if ( ! empty($contacts['ADDRESS']['VALUE'])): ?>
                        <div class="contacts__item">
                            <div class="contact">
                                <div class="contact__top">
                                    <?=CMarketView::showIcon("pin","contact__svg contact__svg_pin")?>
                                    <div class="contact__name"><?=getMessage('WEBCOMP_CONTACTS_ADDRESS_TITLE')?></div>
                                </div>
                                <div class="contact__bottom">
                                    <a class="contact__link" href="https://yandex.ru/maps/-/CKqGjE4G">
                                        <?= $contacts['ADDRESS']['VALUE'] ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <? endif; ?>
                    <? if ( ! empty($contacts['METRO']['VALUES'])): ?>
                        <div class="contacts__item">
                            <div class="contact">
                                <div class="contact__top">
                                    <?=CMarketView::showIcon("metro","contact__svg contact__svg_metro")?>
                                    <div class="contact__name"><?=getMessage('WEBCOMP_CONTACTS_METRO_TITLE')?></div>
                                </div>
                                <? foreach (
                                    $contacts['METRO']['VALUES'] as $metro
                                ) : ?>
                                    <div class="contact__phone">
                                        <div class="contact__phone-val"><?= $metro['VALUE'] ?></div>
                                        <div class="contact__phone-dept"><?= $metro['DESCRIPTION'] ?></div>
                                    </div>
                                <? endforeach; ?>
                            </div>
                        </div>
                    <? endif; ?>
                </div>
            <? endif; ?>
            <? if ( ! empty($contacts['WORKTIME']['VALUES'])
                || ! empty($contacts['EMAIL']['VALUES'])
            ): ?>
                <div class="contacts__col">
                <? if ( ! empty($contacts['WORKTIME']['VALUES'])): ?>
                    <div class="contacts__item">
                        <div class="contact">
                            <div class="contact__top">
                                <?=CMarketView::showIcon("date","contact__svg contact__svg_date")?>
                                <div class="contact__name"><?=getMessage("WEBCOMP_CONTACTS_WORKTIME_TITLE")?></div>
                            </div>
                            <div class="contact__bottom">
                                <div class="contact__link">
                                    <? foreach (
                                        $contacts['WORKTIME']['VALUES'] as
                                        $workTime
                                    ) : ?>
                                        <?= $workTime['DESCRIPTION'] ?> : <?= $workTime['VALUE'] ?>
                                        <br>
                                    <? endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <? endif; ?>
                <? if ( ! empty($contacts['EMAIL']['VALUES'])): ?>
                    <div class="contacts__item">
                        <div class="contact">
                            <div class="contact__top">
                                <?=CMarketView::showIcon("email","contact__svg contact__svg_email")?>
                                <div class="contact__name"><?=getMessage('WEBCOMP_CONTACTS_EMAIL_TITLE')?></div>
                            </div>
                            <? foreach ($contacts['EMAIL']['VALUES'] as $email):
                                ?>
                                <a class="contact__phone"
                                   href="mailto:<?= $email['VALUE'] ?>">
                                    <div class="contact__phone-val contact__link"><?= $email['VALUE'] ?></div>
                                    <div class="contact__phone-dept"><?= $email['DESCRIPTION'] ?></div>
                                </a>
                            <? endforeach; ?>
                        </div>
                    </div>
                <? endif; ?>
                </div>
            <? endif; ?>

            <div class="contacts__col">
                <div class="contacts__item">
                    <div class="contact">
                        <div class="contact__top">
                            <?=CMarketView::showIcon("phone","contact__svg contact__svg_tel2")?>
                            <div class="contact__name"><?=getMessage('WEBCOMP_CONTACTS_PHONE_TITLE')?></div>
                        </div>
                        <div class="contact__bottom">
                            <? foreach ($contacts['PHONE']['VALUES'] as $phone):
                                ?>
                                <a class="contact__phone"
                                   href="tel:<?= $phone['VALUE'] ?>">
                                    <span class="contact__phone-val"><?= $phone['VALUE'] ?></span>
                                    <span class="contact__phone-dept"><?= $phone['DESCRIPTION'] ?></span>
                                </a>
                            <? endforeach; ?>

                            <? $APPLICATION->IncludeComponent(
                                "webcomp:highload.getList",
                                "social_contacts",
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
                <button class="contacts__btn btn2" data-trigger="click" data-target="QUESTION" type="button" >
                    <?=getMessage('WEBCOMP_CONTACTS_QUESTION_BTN')?>
                </button>
            </div>
        </div>
    </div>
</div>
<? if ( ! empty($map)): ?>
    <div class="contacts__map">
        <div id="map"
             data-center-x="<?= $map[0] ?>"
             data-center-y="<?= $map[1] ?>" data-zoom="17"
             data-baloon-x="<?= $map[0] ?>"
             data-baloon-y="<?= $map[1] ?>"
             data-baloon-header=""
             data-baloon-body=""
             data-baloon-footer=""
             data-baloon-content=""
             data-icon="<?= SITE_TEMPLATE_PATH ?>/images/icons/baloon.svg"
             data-icon-size-x="47"
             data-icon-size-y="57"
             data-icon-offset-x="-23"
             data-icon-offset-y="-57"></div>
    </div>
<? endif; ?>

<div class="contacts__call">
    <div class="container">

        <?
        $APPLICATION->IncludeComponent(
            "webcomp:form",
            "feedback",
            [
                "CACHE_FILTER" => "N",
                "CACHE_TIME" => "0",
                "CACHE_TYPE" => "A",
                "ELEMENTS_COUNT" => "20",
                "FIELD_CODE" => "",
                "FILTER_NAME" => "",
                "IBLOCK_ID" => $GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_feedback'],
                "IBLOCK_TYPE" => "forms",
                "PROPERTY_CODE" => "",
                "SHOW_ONLY_ACTIVE" => "Y",
                "SORT_BY1" => "SORT",
                "SORT_BY2" => "NAME",
                "SORT_ORDER1" => "ASC",
                "SORT_ORDER2" => "ASC",
                "COMPONENT_TEMPLATE" => "feedback",
                "EMAIL_EVENT_ID" => "WEBCOMP_FEEDBACK",
                "BIND_ELEMENTS" => "",
                "FORM_NAME" => "FEEDBACK"
            ],
            false
        );
        ?>


    </div>
</div>


<div class="contacts__center">
    <div class="container">
        <div class="row">

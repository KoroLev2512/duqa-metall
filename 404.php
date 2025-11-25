<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Страница не найдена - 404");

?>
    <div class="page_second page">
        <main class="notfound">
            <div class="container">
                <div class="notfound__bread bread"><a class="bread__link"
                                                      href="/">
                        <svg class="bread__link-svg">
                            <use xlink:href="/images/icons/sprite.svg#home"></use>
                        </svg>
                    </a><span class="bread__link active"><span
                                class="bread__link-txt">404</span></span></div>
                <div class="notfound__main">
                    <div class="notfound__title">Ошибка 404<br>Страница не
                        найдена
                    </div>
                    <img class="notfound__img"
                         src="<?= SITE_TEMPLATE_PATH ?>/images/content/404/404.svg"
                         alt="lorem"/>
                    <div class="notfound__sub">Неправильно набран адрес или
                        такой<br>страницы не существует
                    </div>
                    <a class="notfound__back btn" href="/">ПЕРЕЙТИ
                        НА
                        ГЛАВНУЮ</a>
                </div>
            </div>
        </main>
    </div>
<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
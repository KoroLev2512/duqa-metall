<?
require($_SERVER["DOCUMENT_ROOT"]
    ."/bitrix/modules/main/include/prolog_before.php");
\Webcomp\Market\Constants::getAllIblocks();
session_start();
header('Content-Type: application/json');
CModule::IncludeModule("iblock");

use Bitrix\Main\Loader;
use Webcomp\Market\Forms;

if ( ! Loader::includeSharewareModule("webcomp.market")) {
    //die('Для продолжения необходим модуль webcomp.market');
}

$data = $_POST;
$action = $data['action'];
switch ($action) {
    case 'cart':
        echo json_encode([
            'status' => true,
            'html'   => getCartForm(),
        ]);
        exit();
        break;
    case 'favorite':
        echo json_encode([
            'status' => true,
            'html'   => getFavoriteForm(),
        ]);
        exit();
        break;
    case 'callback':
        echo json_encode([
            'status' => true,
            'html'   => getCallbackForm(),
        ]);
        exit();
        break;
    case 'callbackSubmit':
        echo json_encode([
            'status' => true,
            'html'   => getCallbackSubmitForm(),
        ]);
        exit();
        break;
    case 'question':
        echo json_encode([
            'status' => true,
            'html'   => getQuestionForm(),
        ]);
        exit();
        break;
    case 'questionSubmit':
        echo json_encode([
            'status' => true,
            'html'   => getQuestionSubmitForm(),
        ]);
        exit();
        break;
    case 'review':
        echo json_encode([
            'status' => true,
            'html'   => getReviewForm(),
        ]);
        exit();
        break;
    case 'reviewSubmit':
        echo json_encode([
            'status' => true,
            'html'   => getReviewSubmitForm(),
        ]);
        exit();
        break;
    case 'reviewProduct':
        echo json_encode([
            'status' => true,
            'html'   => getReviewProductForm(),
        ]);
        exit();
        break;
    case 'city':
        echo json_encode([
            'status' => true,
            'html'   => getCityForm(),
        ]);
        exit();
        break;
    case 'search':
        echo json_encode([
            'status' => true,
            'html'   => getSearchResult(),
        ]);
        exit();
        break;
    case 'jsCompare':

        // Добавляем или удаляем в\из сравнения
        if ($data["method"] == "add") {
            $_SESSION["COMPARE"][$data["id"]] = 1;
        } else {
            unset($_SESSION["COMPARE"][$data["id"]]);
        }

        echo json_encode([
            'status' => true,
            'html'   => count($_SESSION["COMPARE"]) ?? 0,
        ]);
        exit();
        break;
    case 'jsFavorite':

        // Добавляем или удаляем в\из избранное
        if ($data["method"] == "add") {
            $_SESSION["FAVORITE"][$data["id"]] = 1;
        } else {
            unset($_SESSION["FAVORITE"][$data["id"]]);
        }

        echo json_encode([
            'status' => true,
            'html'   => count($_SESSION["FAVORITE"]) ?? 0,
        ]);
        exit();
        break;
    case 'addToBasket':

        // Обработка добавления в корзину
        if (isset($_SESSION["CART"][$data["id"]])) {
            $_SESSION["CART"][$data["id"]] = $_SESSION["CART"][$data["id"]]
                + $data["count"];
        } else {
            $_SESSION["CART"][$data["id"]] = $data["count"];
        }

        echo json_encode([
            'status' => true,
            'html'   => count($_SESSION["CART"]),
        ]);
        exit();
        break;
    case 'delPrdOfBasket':

        // Удаляем из корзины товар
        unset($_SESSION["CART"][$data["id"]]);

        echo json_encode([
            'status' => true,
            'html'   => count($_SESSION["CART"]) ?? 0,
        ]);
        exit();
        break;
    case 'delPrdOfFavorite':

        // Удаляем из избранного товар
        unset($_SESSION["FAVORITE"][$data["id"]]);

        echo json_encode([
            'status' => true,
            'html'   => count($_SESSION["FAVORITE"]) ?? 0,
        ]);
        exit();
        break;
    case 'delPrdOfCompare':

        // Удаляем из избранного товар
        unset($_SESSION["COMPARE"][$data["id"]]);

        echo json_encode([
            'status' => true,
            'html'   => count($_SESSION["COMPARE"]) ?? 0,
        ]);
        exit();
        break;

    case 'delAllPrdOfBasket':

        // Удаляем из корзины товар
        unset($_SESSION["CART"]);

        echo json_encode([
            'status' => true,
            'html'   => 0,
        ]);
        exit();
        break;
    case 'delAllPrdOfFavorite':
        // Удаляем из избранного все
        unset($_SESSION["FAVORITE"]);

        echo json_encode([
            'status' => true,
            'html'   => 0,
        ]);
        exit();
        break;
    case 'delAllPrdOfCompare':
        // Удаляем из избранного все
        unset($_SESSION["COMPARE"]);

        echo json_encode([
            'status' => true,
            'html'   => 0,
        ]);
        exit();
        break;
    case 'showForm':
        echo json_encode([
            'status' => true,
            'html'   => getForm(intval($data["iblock_id"])),
        ]);
        exit();
        break;
    case 'sendForm':
        echo json_encode([
            'status' => true,
            'html'   => sendForm($data),
        ]);
        exit();
        break;
    default:
        echo json_encode([
            'status' => false,
        ]);
        exit();
        break;
}

function getCartForm()
{

    ob_start();
    ?>

    <form class="cart">
        <div class="cart__top popup__top">
            <div class="cart__title popup__title">Корзина</div>
            <button class="cart__close popup__close jsFormClose" type="button">
                <svg class="popup__close-svg">
                    <use xlink:href="/images/icons/sprite.svg#close"></use>
                </svg>
            </button>
        </div>
        <div id="cartRender" class="popup-render">
            <? if (isset($_SESSION["CART"]) && ! empty($_SESSION["CART"])): ?>

                <?
                global $APPLICATION, $arrFilter;
                $arrFilter = ["ID" => array_keys($_SESSION["CART"])];
                $APPLICATION->IncludeComponent(
                    "webcomp:element.getList",
                    "products_in_basket",
                    [
                        "CACHE_FILTER"       => "N",
                        "CACHE_TIME"         => "0",
                        "CACHE_TYPE"         => "A",
                        "COMPONENT_TEMPLATE" => "product_in_basket",
                        "ELEMENTS_COUNT"     => "100",
                        "FIELD_CODE"         => [
                            0 => "ID",
                            1 => "NAME",
                            2 => "PREVIEW_PICTURE",
                            3 => "PREVIEW_TEXT",
                            4 => "CODE",
                        ],
                        "FILTER_NAME"        => "arrFilter",
                        "IBLOCK_ID"          => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'],
                        "IBLOCK_TYPE"        => "content",
                        "PROPERTY_CODE"      => [
                            0 => "PRICE",
                            1 => "OLD_PRICE",
                            2 => "AVAILABLE",
                        ],
                        "SHOW_ONLY_ACTIVE"   => "Y",
                        "SORT_BY1"           => "ACTIVE_FROM",
                        "SORT_BY2"           => "SORT",
                        "SORT_ORDER1"        => "DESC",
                        "SORT_ORDER2"        => "ASC",
                        "TITLE"              => "Корзина",
                        "USE_FILTER"         => "Y",
                    ],
                    false
                ); ?>

                <div class="cart__bottom popup__bottom">
                    <div class="cart__btns">
                        <div class="cart__btn">
                            <button class="cart__one btn3 cart__btn-btn"
                                    type="button"
                                    data-event="showForm"
                                    data-request="/ajax/cart/"
                                    data-form_name="ORDER"
                                    data-form_id=<?=$GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_callorder']?>
                                    data-email_event_id="WEBCOMP_NEW_ORDER">
                                <?= CMarketView::showIcon("one", "btn3__svg") ?>
                                <span class="btn3__txt">Быстрый заказ</span>
                            </button>
                            <div class="cart__btn-txt">Вам потребуется
                                указатьтолько имя и номер телефона
                            </div>
                        </div>
                        <div class="cart__btn">
                            <a href="/cart/"
                               class="cart__buy btn cart__btn-btn">
                                <span class="btn__txt">Перейти в корзину</span>
                            </a>
                            <div class="cart__btn-txt">Полноценное оформление
                                заказа
                            </div>
                        </div>
                    </div>
                </div>

            <? else: ?>
                <div class="cart-empty">
                    <div class="cart-empty__title">Корзина пуста</div>
                    <div class="cart-empty__text">Исправить это просто: выберите
                        в каталоге интересующий товар и нажмите кнопку "В
                        корзину"
                        <span class="popup__btn-img">
                    <svg class="popup__btn-svg popup__btn-svg_compare bread__link-svg">
                        <use xlink:href="/images/icons/sprite.svg#cart"></use>
                    </svg>
                </span>
                    </div>
                    <a class="cart-empty__btn btn"
                       href="/catalog/">
                        <span>В каталог</span>
                    </a>
                </div>
            <? endif ?>
        </div>
    </form>

    <?
    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}

function getFavoriteForm()
{
    ob_start();
    ?>
    <form class="cart">
        <div class="cart__top popup__top">
            <div class="cart__title popup__title">Избранное</div>
            <button class="cart__close popup__close jsFormClose" type="button">
                <svg class="popup__close-svg">
                    <use xlink:href="/images/icons/sprite.svg#close"></use>
                </svg>
            </button>
        </div>
        <div id="favoriteRender" class="popup-render">
            <? if (isset($_SESSION["FAVORITE"])
                && ! empty($_SESSION["FAVORITE"])
            ): ?>

                <?
                global $APPLICATION, $arrFilter;
                $arrFilter = ["ID" => array_keys($_SESSION["FAVORITE"])];
                $APPLICATION->IncludeComponent(
                    "webcomp:element.getList",
                    "products_in_favorite",
                    [
                        "CACHE_FILTER"       => "N",
                        "CACHE_TIME"         => "0",
                        "CACHE_TYPE"         => "A",
                        "COMPONENT_TEMPLATE" => "products_in_favorite",
                        "ELEMENTS_COUNT"     => "100",
                        "FIELD_CODE"         => [
                            0 => "ID",
                            1 => "NAME",
                            2 => "PREVIEW_PICTURE",
                            3 => "PREVIEW_TEXT",
                            4 => "CODE",
                        ],
                        "FILTER_NAME"        => "arrFilter",
                        "IBLOCK_ID"          => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'],
                        "IBLOCK_TYPE"        => "content",
                        "PROPERTY_CODE"      => [
                            0 => "PRICE",
                            1 => "OLD_PRICE",
                            2 => "AVAILABLE",
                        ],
                        "SHOW_ONLY_ACTIVE"   => "Y",
                        "SORT_BY1"           => "ACTIVE_FROM",
                        "SORT_BY2"           => "SORT",
                        "SORT_ORDER1"        => "DESC",
                        "SORT_ORDER2"        => "ASC",
                        "TITLE"              => "Корзина",
                        "USE_FILTER"         => "Y",
                    ],
                    false
                ); ?>

                <div class="cart__bottom popup__bottom">
                    <div class="cart__btns">
                        <div class="cart__btn">
                            <a class="cart__buy btn cart__btn-btn"
                               href="/cart/favorite/">
                                <span class="btn__txt">Перейти в избранное</span>
                            </a>
                        </div>
                    </div>
                </div>

            <? else: ?>
                <div class="cart-empty">
                    <div class="cart-empty__title">Список пуст</div>
                    <div class="cart-empty__text">Исправить это просто: выберите
                        в каталоге интересующий товар и нажмите кнопку добавить
                        в избранное.
                    </div>
                    <a class="cart-empty__btn btn"
                       href="/catalog/">
                        <span>В каталог</span>
                    </a>
                </div>
            <? endif ?>
        </div>
    </form>
    <?
    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}

function getForm($iblock_id)
{
    if (empty($iblock_id)) {
        return false;
    }

    ob_start();
    ?>

    <?
    global $APPLICATION;
    $APPLICATION->IncludeComponent(
        "webcomp:form",
        "oneClickBuy",
        [
            "CACHE_FILTER"       => "N",
            "CACHE_TIME"         => "0",
            "CACHE_TYPE"         => "A",
            "ELEMENTS_COUNT"     => "20",
            "FIELD_CODE"         => "",
            "FILTER_NAME"        => "",
            "IBLOCK_ID"          => $iblock_id,
            "IBLOCK_TYPE"        => "forms",
            "PROPERTY_CODE"      => "",
            "SHOW_ONLY_ACTIVE"   => "Y",
            "SORT_BY1"           => "SORT",
            "SORT_BY2"           => "NAME",
            "SORT_ORDER1"        => "ASC",
            "SORT_ORDER2"        => "ASC",
            "COMPONENT_TEMPLATE" => "oneClickBuy",
            "EMAIL_EVENT_ID"  => "WEBCOMP_CALLORDER",
            "BIND_ELEMENTS"      => $_POST["elem_id"],
        ],
        false
    );
    ?>

    <?
    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}

function sendForm($data)
{
    Forms::getInstance()->Send($data);

    ob_start();
    ?>
    <div class="popup__top">
        <button class="popup__close jsFormClose" type="button">
            <svg class="popup__close-svg">
                <use xlink:href="/images/icons/sprite.svg#close"></use>
            </svg>
        </button>
    </div>
    <div class="popup__success">
        <div class="psuccess">
            <div class="psuccess__img">
                <svg class="psuccess__svg">
                    <use xlink:href="/images/icons/sprite.svg#chech-round"></use>
                </svg>
            </div>
            <div class="psuccess__title">
                Спасибо!
            </div>
            <div class="psuccess__txt">
                Ваша заявка принята в работу.
                <br>
                Ожидайте звонка.
            </div>
            <button type="button" class="psuccess__close jsFormClose btn">
                Закрыть
            </button>
        </div>
    </div>
    <?
    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}

function getCallbackForm()
{
    ob_start();
    ?>

    <?
    global $APPLICATION;
    $APPLICATION->IncludeComponent(
        "webcomp:form",
        "callorder",
        [
            "CACHE_FILTER"       => "N",
            "CACHE_TIME"         => "0",
            "CACHE_TYPE"         => "A",
            "ELEMENTS_COUNT"     => "20",
            "FIELD_CODE"         => "",
            "FILTER_NAME"        => "",
            "IBLOCK_ID"          => $GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_callorder'],
            "IBLOCK_TYPE"        => "forms",
            "PROPERTY_CODE"      => "",
            "SHOW_ONLY_ACTIVE"   => "Y",
            "SORT_BY1"           => "SORT",
            "SORT_BY2"           => "NAME",
            "SORT_ORDER1"        => "ASC",
            "SORT_ORDER2"        => "ASC",
            "COMPONENT_TEMPLATE" => "callorder",
            "EMAIL_EVENT_ID"  => "WEBCOMP_CALLORDER",
        ],
        false
    );
    ?>

    <?
    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}

function getCallbackSubmitForm()
{

    Forms::getInstance()->Send($_POST);

    ob_start();
    ?>
    <div class="popup__top">
        <button class="popup__close jsFormClose" type="button">
            <svg class="popup__close-svg">
                <use xlink:href="/images/icons/sprite.svg#close"></use>
            </svg>
        </button>
    </div>
    <div class="popup__success">
        <div class="psuccess">
            <div class="psuccess__img">
                <svg class="psuccess__svg">
                    <use xlink:href="/images/icons/sprite.svg#chech-round"></use>
                </svg>
            </div>
            <div class="psuccess__title">
                Спасибо!
            </div>
            <div class="psuccess__txt">
                Ваша заявка принята в работу.
                <br>
                Ожидайте звонка.
            </div>
            <button type="button" class="psuccess__close jsFormClose btn">
                Закрыть
            </button>
        </div>
    </div>
    <?
    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}

function getQuestionForm()
{
    ob_start();
    ?>

    <?
    global $APPLICATION;
    $APPLICATION->IncludeComponent(
        "webcomp:form",
        "question",
        [
            "CACHE_FILTER"       => "N",
            "CACHE_TIME"         => "0",
            "CACHE_TYPE"         => "A",
            "ELEMENTS_COUNT"     => "20",
            "FIELD_CODE"         => "",
            "FILTER_NAME"        => "",
            "IBLOCK_ID"          => $GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_question'],
            "IBLOCK_TYPE"        => "forms",
            "PROPERTY_CODE"      => "",
            "SHOW_ONLY_ACTIVE"   => "Y",
            "SORT_BY1"           => "SORT",
            "SORT_BY2"           => "NAME",
            "SORT_ORDER1"        => "ASC",
            "SORT_ORDER2"        => "ASC",
            "COMPONENT_TEMPLATE" => "question",
            "EMAIL_EVENT_ID"  => "WEBCOMP_ASK_QUESTION",
        ],
        false
    );
    ?>

    <?
    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}

function getQuestionSubmitForm()
{

    Forms::getInstance()->Send($_POST);

    ob_start();
    ?>
    <div class="popup__top">
        <button class="popup__close jsFormClose" type="button">
            <svg class="popup__close-svg">
                <use xlink:href="/images/icons/sprite.svg#close"></use>
            </svg>
        </button>
    </div>
    <div class="popup__success">
        <div class="psuccess">
            <div class="psuccess__img">
                <svg class="psuccess__svg">
                    <use xlink:href="/images/icons/sprite.svg#chech-round"></use>
                </svg>
            </div>
            <div class="psuccess__title">
                Спасибо!
            </div>
            <div class="psuccess__txt">
                Ваше сообщение отправлено!
            </div>
            <button type="button" class="psuccess__close jsFormClose btn">
                Закрыть
            </button>
        </div>
    </div>
    <?
    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}

function getReviewForm()
{
    ob_start();
    ?>
    <div class="popup__top">
        <div class="popup__title">Оставить свой отзыв</div>
        <button class="popup__close jsFormClose" type="button">
            <svg class="popup__close-svg">
                <use xlink:href="/images/icons/sprite.svg#close"></use>
            </svg>
        </button>
    </div>
    <div class="popup__middle">
        <form class="popup__form" action="/ajax.php"
              method="post"
              enctype="multipart/form-data">
            <div class="popup__fields">
                <div class="popup__field">
                    <input class="popup__input" type="text" name="name"
                           data-rule-required="true"
                           data-msg-required="Введите Имя">
                    <div class="popup__placeholder">Ваще имя<i>*</i></div>
                </div>
                <div class="popup__field popup__field_m30">
                    <input class="popup__input" type="text" name="prof">
                    <div class="popup__placeholder">Должность</div>
                </div>
                <div class="popup__field popup__field_m30">
                    <div class="popup__label">Ваше фото</div>
                    <label class="file">
                        <input class="file__input" type="file" name="userPhoto"><span
                                class="file__fake">
                      <svg class="file__svg">
                        <use xlink:href="/images/icons/sprite.svg#staple"></use>
                      </svg><span class="file__title">Прикрепить</span>
                      <button class="file__del" type="button">
                        <svg class="file__del-svg">
                          <use xlink:href="/images/icons/sprite.svg#close"></use>
                        </svg>
                      </button></span>
                    </label>
                </div>
                <div class="popup__field">
                    <div class="popup__label">Отзыв<i>*</i></div>
                    <textarea class="popup__area" name="message"
                              data-rule-required="true"
                              data-msg-required="Добавьте отзыв"></textarea>
                </div>
            </div>
            <div class="popup__rating">
                <div class="rating2">
                    <div class="rating2__wrapper">
                        <input class="rating2__input" type="radio" name="rating"
                               value="Отлично" id="rating-5">
                        <label class="rating2__star" for="rating-5"
                               data-rate="Отлично">
                            <svg class="rating2__star-svg">
                                <use xlink:href="/images/icons/sprite.svg#star"></use>
                            </svg>
                        </label>
                        <input class="rating2__input" type="radio" name="rating"
                               value="Хорошо" id="rating-4">
                        <label class="rating2__star" for="rating-4"
                               data-rate="Хорошо">
                            <svg class="rating2__star-svg">
                                <use xlink:href="/images/icons/sprite.svg#star"></use>
                            </svg>
                        </label>
                        <input class="rating2__input" type="radio" name="rating"
                               value="Нормально" id="rating-3">
                        <label class="rating2__star" for="rating-3"
                               data-rate="Нормально">
                            <svg class="rating2__star-svg">
                                <use xlink:href="/images/icons/sprite.svg#star"></use>
                            </svg>
                        </label>
                        <input class="rating2__input" type="radio" name="rating"
                               value="Плохо" id="rating-2">
                        <label class="rating2__star" for="rating-2"
                               data-rate="Плохо">
                            <svg class="rating2__star-svg">
                                <use xlink:href="/images/icons/sprite.svg#star"></use>
                            </svg>
                        </label>
                        <input class="rating2__input" type="radio" name="rating"
                               value="Очень плохо" id="rating-1">
                        <label class="rating2__star" for="rating-1"
                               data-rate="Очень плохо">
                            <svg class="rating2__star-svg">
                                <use xlink:href="/images/icons/sprite.svg#star"></use>
                            </svg>
                        </label>
                    </div>
                    <div class="rating2__txt">Без оценки</div>
                </div>
            </div>
            <div class="popup__policy">Заполняя данную форму Вы соглашаетесь
                с<br><a href="policy.html">Политикой
                    обработки персональных данных</a></div>
            <button class="btn popup__submit" type="submit">Отправить</button>
            <input type="hidden" name="action" value="reviewSubmit">
        </form>
    </div>
    <?
    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}

function getReviewSubmitForm()
{
    ob_start();
    ?>
    <div class="popup__top">
        <button class="popup__close jsFormClose" type="button">
            <svg class="popup__close-svg">
                <use xlink:href="/images/icons/sprite.svg#close"></use>
            </svg>
        </button>
    </div>
    <div class="popup__success">
        <div class="psuccess">
            <div class="psuccess__img">
                <svg class="psuccess__svg">
                    <use xlink:href="/images/icons/sprite.svg#chech-round"></use>
                </svg>
            </div>
            <div class="psuccess__title">
                Спасибо!
            </div>
            <div class="psuccess__txt">
                Ваш отзыв появится на сайте сразу
                <br>
                после модерации.
            </div>
            <button type="button" class="psuccess__close jsFormClose btn">
                Закрыть
            </button>
        </div>
    </div>
    <?
    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}

function getReviewProductForm()
{
    ob_start();
    ?>
    <div class="popup__top">
        <div class="popup__title">Оставить свой отзыв</div>
        <button class="popup__close jsFormClose" type="button">
            <svg class="popup__close-svg">
                <use xlink:href="/images/icons/sprite.svg#close"></use>
            </svg>
        </button>
    </div>
    <div class="popup__middle">
        <form class="popup__form" action="/ajax.php"
              method="post"
              enctype="multipart/form-data">
            <div class="popup__fields">
                <div class="popup__field">
                    <input class="popup__input" type="text" name="name"
                           data-rule-required="true"
                           data-msg-required="Введите Имя">
                    <div class="popup__placeholder">Ваще имя<i>*</i></div>
                </div>
                <div class="popup__field popup__field_m30">
                    <input class="popup__input" type="text" name="prof">
                    <div class="popup__placeholder">Должность</div>
                </div>
                <div class="popup__field popup__field_m30">
                    <div class="popup__link">
                        <span class="popup__link-label">Товар: </span>
                        <a href="product.html" class="popup__link-link">Аккумуляторный
                            шуруповёрт ANGLE EXACT 30 без аккум. и ЗУ Bosch
                            Professional 0602490671</a>
                    </div>
                </div>

                <div class="popup__field popup__field_m30">
                    <div class="popup__label">Ваше фото</div>
                    <label class="file">
                        <input class="file__input" type="file" name="userPhoto"><span
                                class="file__fake">
                      <svg class="file__svg">
                        <use xlink:href="/images/icons/sprite.svg#staple"></use>
                      </svg><span class="file__title">Прикрепить</span>
                      <button class="file__del" type="button">
                        <svg class="file__del-svg">
                          <use xlink:href="/images/icons/sprite.svg#close"></use>
                        </svg>
                      </button></span>
                    </label>
                </div>
                <div class="popup__field">
                    <div class="popup__label">Отзыв<i>*</i></div>
                    <textarea class="popup__area" name="message"
                              data-rule-required="true"
                              data-msg-required="Добавьте отзыв"></textarea>
                </div>
            </div>
            <div class="popup__rating">
                <div class="rating2">
                    <div class="rating2__wrapper">
                        <input class="rating2__input" type="radio" name="rating"
                               value="Отлично" id="rating-5">
                        <label class="rating2__star" for="rating-5"
                               data-rate="Отлично">
                            <svg class="rating2__star-svg">
                                <use xlink:href="/images/icons/sprite.svg#star"></use>
                            </svg>
                        </label>
                        <input class="rating2__input" type="radio" name="rating"
                               value="Хорошо" id="rating-4">
                        <label class="rating2__star" for="rating-4"
                               data-rate="Хорошо">
                            <svg class="rating2__star-svg">
                                <use xlink:href="/images/icons/sprite.svg#star"></use>
                            </svg>
                        </label>
                        <input class="rating2__input" type="radio" name="rating"
                               value="Нормально" id="rating-3">
                        <label class="rating2__star" for="rating-3"
                               data-rate="Нормально">
                            <svg class="rating2__star-svg">
                                <use xlink:href="/images/icons/sprite.svg#star"></use>
                            </svg>
                        </label>
                        <input class="rating2__input" type="radio" name="rating"
                               value="Плохо" id="rating-2">
                        <label class="rating2__star" for="rating-2"
                               data-rate="Плохо">
                            <svg class="rating2__star-svg">
                                <use xlink:href="/images/icons/sprite.svg#star"></use>
                            </svg>
                        </label>
                        <input class="rating2__input" type="radio" name="rating"
                               value="Очень плохо" id="rating-1">
                        <label class="rating2__star" for="rating-1"
                               data-rate="Очень плохо">
                            <svg class="rating2__star-svg">
                                <use xlink:href="/images/icons/sprite.svg#star"></use>
                            </svg>
                        </label>
                    </div>
                    <div class="rating2__txt">Без оценки</div>
                </div>
            </div>
            <div class="popup__policy">Заполняя данную форму Вы соглашаетесь
                с<br><a href="policy.html">Политикой
                    обработки персональных данных</a></div>
            <button class="btn popup__submit" type="submit">Отправить</button>
            <input type="hidden" name="action" value="reviewSubmit">
        </form>
    </div>
    <?
    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}

function getCityForm()
{
    ob_start();
    ?>
    <div class="popup__top">
        <div class="popup__title">Выбор города</div>
        <button class="popup__close" type="button">
            <svg class="popup__close-svg">
                <use xlink:href="/images/icons/sprite.svg#close"></use>
            </svg>
        </button>
    </div>
    <div class="popup__middle">
        <div class="city">
            <div class="city__field">
                <input class="city__search" type="search" name="city"
                       placeholder="Введите название города">
                <svg class="city__svg">
                    <use xlink:href="/images/icons/sprite.svg#magnifier"></use>
                </svg>
            </div>
            <div class="city__cities">
                <div class="city__cities-title">Например:</div>
                <a class="city__item" href="">Москва</a><a class="city__item"
                                                           href="">Санкт-Петербург</a><a
                        class="city__item" href="">Москва</a><a
                        class="city__item" href="">Санкт-Петербург</a><a
                        class="city__item" href="">Москва</a><a
                        class="city__item" href="">Санкт-Петербург</a><a
                        class="city__item" href="">Москва</a><a
                        class="city__item" href="">Санкт-Петербург</a><a
                        class="city__item" href="">Москва</a><a
                        class="city__item" href="">Санкт-Петербург</a><a
                        class="city__item" href="">Москва</a><a
                        class="city__item" href="">Санкт-Петербург</a><a
                        class="city__item" href="">Москва</a><a
                        class="city__item" href="">Санкт-Петербург</a><a
                        class="city__item" href="">Москва</a><a
                        class="city__item" href="">Санкт-Петербург</a><a
                        class="city__item" href="">Москва</a><a
                        class="city__item" href="">Санкт-Петербург</a><a
                        class="city__item" href="">Москва</a><a
                        class="city__item" href="">Санкт-Петербург</a><a
                        class="city__item" href="">Москва</a><a
                        class="city__item" href="">Санкт-Петербург</a><a
                        class="city__item" href="">Москва</a><a
                        class="city__item" href="">Санкт-Петербург</a><a
                        class="city__item" href="">Москва</a><a
                        class="city__item" href="">Санкт-Петербург</a><a
                        class="city__item" href="">Москва</a><a
                        class="city__item" href="">Санкт-Петербург</a><a
                        class="city__item" href="">Москва</a><a
                        class="city__item" href="">Санкт-Петербург</a><a
                        class="city__item" href="">Москва</a><a
                        class="city__item" href="">Санкт-Петербург</a><a
                        class="city__item" href="">Москва</a><a
                        class="city__item" href="">Санкт-Петербург</a><a
                        class="city__item" href="">Москва</a><a
                        class="city__item" href="">Санкт-Петербург</a>
            </div>
        </div>
    </div>
    <?
    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}

function getSearchResult()
{
    ob_start();
    ?>
    <table class="hsearch__table">
        <tbody>
        <tr>
            <td class="hsearch__left">
                <div class="hsearch__title">Товары</div>
            </td>
            <td class="hsearch__right">
                <div class="hsearch__list"><a class="hsearch__item"
                                              href="product.html"><b>Шурупов</b>ерт
                        interscope</a><a
                            class="hsearch__item"
                            href="product.html"><b>Шурупов</b>ерты</a><a
                            class="hsearch__item"
                            href="product.html"><b>Шурупов</b>ерты
                        Bosh</a><a class="hsearch__item" href="product.html"><b>Шурупов</b>ерты
                        Зубр</a><a
                            class="hsearch__item" href="product.html">Аккумуляторный&nbsp;<b>шурупов</b>ёрт
                        ANGLE EXACT
                        30 без аккум. и ЗУ Bosch...</a></div>
            </td>
        </tr>
        <tr>
            <td class="hsearch__left">
                <div class="hsearch__title">Услуги</div>
            </td>
            <td class="hsearch__right">
                <div class="hsearch__list"><a class="hsearch__item"
                                              href="service.html"><b>Шурупов</b>ерт
                        interscope</a><a
                            class="hsearch__item"
                            href="service.html"><b>Шурупов</b>ерты</a><a
                            class="hsearch__item"
                            href="service.html"><b>Шурупов</b>ерты
                        Bosh</a><a class="hsearch__item" href="service.html"><b>Шурупов</b>ерты
                        Зубр</a></div>
            </td>
        </tr>
        </tbody>
    </table>
    <?
    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}

?>
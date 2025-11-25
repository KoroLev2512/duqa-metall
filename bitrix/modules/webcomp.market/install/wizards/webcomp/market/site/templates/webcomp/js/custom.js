function declOfNum(number, words) {
    return words[(number % 100 > 4 && number % 100 < 20) ? 2 : [2, 0, 1, 1, 1, 2][(number % 10 < 5) ? number % 10 : 5]];
}


if (typeof BX != 'undefined') {
    BX.addCustomEvent('OnEditorInitedBefore', function (toolbar) {
        var _this = this;

        // отучаю резать тэги
        BX.addCustomEvent(this, 'OnGetParseRules', BX.proxy(function () {
            _this.rules.tags.span = {};
            _this.rules.tags.svg = {};
            _this.rules.tags.use = {};
        }, this));
    });
}
;


//index.js
$(function () {

    // Валидация формы в подвале
    $('.ffeed').validate({
        onfocusout: false,
        submitHandler: function (form) {
            const $form = $(form),
                data = new FormData(),
                url = $form.attr('action'),
                inputs = $form.find('input[type!=file],textarea');

            inputs.each(function (x, i) {
                data.append(i.name, i.value);
            });

            new Recaptcha(form, {
                onSubmit: function () {
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: url,
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (result) {
                            if (result.status) {
                                $(".ffeed__right").html(result.html);

                                let formName = data.get("FORM_NAME");
                                if (JSSeo.Yandex.Enabled == "Y") {
                                    new SEO().goal(formName);
                                }

                            } else {
                                alert('Что-то пошло не так, попробуйте еще раз!!!');
                            }
                        },
                        error: function (result) {
                            alert('Что-то пошло не так, попробуйте еще раз!!!');
                        }
                    });
                }
            });

        },
        errorPlacement: function (error, element) {
            element[0].placeholder = error[0].innerText;
            // debugger;
        }
    });

    $('body').on('click', '[data-action=Compare]', function (e) {
        e.preventDefault();

        // Определяем удалять или добавлять товар в сравнение
        let method = ($(this).hasClass("active")) ? "del" : "add";


        let $this = $(this),
            data = {
                action: "jsCompare",
                id: $(this).data("id"),
                method: method
            },
            url = '/ajax.php';

        // debugger;

        $.ajax({
            dataType: "json",
            type: "POST",
            url: url,
            data: data,
            success: function (result) {
                if (result.status) {
                    //$this.toggleClass('active');
                    $(".compare-icon_count").html(result.html);
                    $("[data-action=Compare][data-id=" + data.id + "]").toggleClass('active');
                } else {
                    alert('Что-то пошло не так, попробуйте еще раз!!!');
                }
            },
            error: function (result) {
                alert('Что-то пошло не так, попробуйте еще раз!!!');
            }
        });
    });

    $('body').on('click', '[data-action=Favorite]', function (e) {
        e.preventDefault();

        // Определяем удалять или добавлять товар в сравнение
        let method = ($(this).hasClass("active")) ? "del" : "add";


        let $this = $(this),
            data = {
                action: "jsFavorite",
                id: $(this).data("id"),
                method: method
            },
            url = '/ajax.php';

        // debugger;

        $.ajax({
            dataType: "json",
            type: "POST",
            url: url,
            data: data,
            success: function (result) {
                if (result.status) {
                    //$this.toggleClass('active');
                    $(".favorite-icon_count").html(result.html);
                    $("[data-action=Favorite][data-id=" + data.id + "]").toggleClass('active');
                } else {
                    alert('Что-то пошло не так, попробуйте еще раз!!!');
                }
            },
            error: function (result) {
                alert('Что-то пошло не так, попробуйте еще раз!!!');
            }
        });
    });

    $('body').on('click', '[data-action=addToBasket]', function (e) {

        let $this = $(this),
            data = {
                action: 'addToBasket',
                id: $(this).data("id"),
                count: parseInt($("[data-count=" + $(this).data("id") + "]").val()) || 1
            },
            url = '/ajax.php';

        if (!$this.hasClass('add_in')) {
            e.preventDefault();

            $.ajax({
                dataType: "json",
                type: "POST",
                url: url,
                data: data,
                success: function (result) {
                    if (result.status) {
                        $this.addClass('add_in');
                        $(".cart-icon_count").html(result.html);

                        // При добавлении выезжает окно с корзиной
                        $("[data-action=cart]").trigger("click");

                        console.log('add')
                        let formName = "ADD";
                        if (JSSeo.Yandex.Enabled == "Y") {
                            new SEO().goal(formName);
                        }

                    } else {
                        alert('Что-то пошло не так, попробуйте еще раз!!!');
                    }
                },
                error: function (result) {
                    alert('Что-то пошло не так, попробуйте еще раз!!!');
                }
            });

        }

    });

    $('body').on('click', '[data-action=delPrdOfBasket]', function (e) {

        let $this = $(this),
            data = {
                action: 'delPrdOfBasket',
                id: $(this).data("id")
            },
            url = '/ajax.php';

        $.ajax({
            dataType: "json",
            type: "POST",
            url: url,
            data: data,
            success: function (result) {
                if (result.status) {

                    $(".cart-icon_count").html(result.html);

                    // Удаляем признак добавленного товара в корзину
                    $("[data-action=addToBasket][data-id=" + data.id + "]").removeClass("add_in");

                    // Если удалили последний товар то закрываем корзину
                    if (!parseInt(result.html)) {
                        $("[data-action=cart]").trigger("click");
                    }

                } else {
                    alert('Что-то пошло не так, попробуйте еще раз!!!');
                }
            },
            error: function (result) {
                alert('Что-то пошло не так, попробуйте еще раз!!!');
            }
        });

        $this.parents(".citem").remove();

    });

    $('body').on('click', '[data-action=delPrdOfFavorite]', function (e) {

        let $this = $(this),
            data = {
                action: 'delPrdOfFavorite',
                id: $(this).data("id")
            },
            url = '/ajax.php';

        $.ajax({
            dataType: "json",
            type: "POST",
            url: url,
            data: data,
            success: function (result) {
                if (result.status) {
                    if (parseInt(result.html) < 1) {
                        location = location
                    }
                    $(".favorite-icon_count").html(result.html);

                    // Удаляем признак добавленного товара в корзину
                    $("[data-action=Favorite][data-id=" + data.id + "]").removeClass("active");

                    // Если удалили последний товар то закрываем корзину
                    if (!parseInt(result.html)) {
                        $("[data-action=favorite]").trigger("click");
                    }
                    $('.btable__title-txt .count_favorite').html(result.html + ' ' + declOfNum(result.html, ['товар', 'товара', 'товаров']))


                } else {
                    alert('Что-то пошло не так, попробуйте еще раз!!!');
                }
            },
            error: function (result) {
                alert('Что-то пошло не так, попробуйте еще раз!!!');
            }
        });

        $this.parents(".citem").remove();

    });

    $('body').on('click', '[data-action=delPrdOfCompare]', function (e) {
        e.preventDefault();

        let $this = $(this),
            data = {
                action: 'delPrdOfCompare',
                id: $(this).data("id")
            },
            url = '/ajax.php';

        $.ajax({
            dataType: "json",
            type: "POST",
            url: url,
            data: data,
            success: function (result) {
                if (result.status) {
                    if (parseInt(result.html) < 1) {
                        location = location
                    }
                    $(".compare-icon_count").html(result.html);

                    // Удаляем признак добавленного товара в корзину
                    $("[data-action=Compare][data-id=" + data.id + "]").removeClass("active");

                    // Если удалили последний товар то закрываем корзину
                    if (!parseInt(result.html)) {
                        $("[data-action=compare]").trigger("click");
                    }
                    $('.compare__th-txt .count_compare').html(result.html + ' ' + declOfNum(result.html, ['товар', 'товара', 'товаров']))

                } else {
                    alert('Что-то пошло не так, попробуйте еще раз!!!');
                }
            },
            error: function (result) {
                alert('Что-то пошло не так, попробуйте еще раз!!!');
            }
        });

        $this.parents(".compare__item").remove();
        $("[data-slider2-compare-id=" + data.id + "]").remove();
        compareSlider2.update()
        compareSlider.update()
    });

    $('body').on('click', '[data-action=delAllPrdOfBasket]', function (e) {

        let $this = $(this),
            data = {
                action: 'delAllPrdOfBasket'
            },
            url = '/ajax.php';

        $.ajax({
            dataType: "json",
            type: "POST",
            url: url,
            data: data,
            success: function (result) {
                if (result.status) {

                    $(".cart-icon_count").html(result.html);

                    $("[data-action=cart]").trigger("click");

                    // Удаляем признак добавленного товара в корзину
                    $("[data-action=addToBasket]").removeClass("add_in");


                } else {
                    alert('Что-то пошло не так, попробуйте еще раз!!!');
                }
            },
            error: function (result) {
                alert('Что-то пошло не так, попробуйте еще раз!!!');
            }
        });

        $this.parents(".citem").remove();

    });
    $('body').on('click', '[data-action=delAllPrdOfFavorite]', function (e) {

        let $this = $(this),
            data = {
                action: 'delAllPrdOfFavorite'
            },
            url = '/ajax.php';

        $.ajax({
            dataType: "json",
            type: "POST",
            url: url,
            data: data,
            success: function (result) {
                if (result.status) {
                    if (parseInt(result.html) < 1) {
                        location = location
                    }

                } else {
                    alert('Что-то пошло не так, попробуйте еще раз!!!');
                }
            },
            error: function (result) {
                alert('Что-то пошло не так, попробуйте еще раз!!!');
            }
        });

        $this.parents(".citem").remove();

    });
    $('body').on('click', '[data-action=delAllPrdOfCompare]', function (e) {

        let $this = $(this),
            data = {
                action: 'delAllPrdOfCompare'
            },
            url = '/ajax.php';

        $.ajax({
            dataType: "json",
            type: "POST",
            url: url,
            data: data,
            success: function (result) {
                if (result.status) {
                    if (parseInt(result.html) < 1) {
                        location = location
                    }

                } else {
                    alert('Что-то пошло не так, попробуйте еще раз!!!');
                }
            },
            error: function (result) {
                alert('Что-то пошло не так, попробуйте еще раз!!!');
            }
        });

        $this.parents(".citem").remove();

    });


});

//popup.js
$(function () {
    // VARIABLES
    const popup = $('.popup'),
        btns = popup.find('.popup__btn'),
        tab = popup.find('.popup__tab');

    // EVENTS
    //Анимация плейсхолдера input формы
    $('body').on('focusin', '.popup__input', function (e) {
        let placeholder = getPlaceholder(this);
        placeholder.addClass('active');
    });
    $('body').on('focusout', '.popup__input', function (e) {
        let $this = $(this),
            type = $this.attr('type'),
            value = $this.val(),
            placeholder = getPlaceholder(this);

        if (type == 'tel') {
            if (!validateTel(value,this)) {
                $this.val('');
                placeholder.removeClass('active');
            }
        } else {
            if (!this.value) {
                placeholder.removeClass('active');
            }
        }
    });


    //Вызов формы "Корзина"
    $('.jsCartForm').on('click', function (e) {
        e.preventDefault();

        let btn = btns.filter('[data-action=cart]'),
            action = btn.data('action'),
            url = btn.data('ajax'),
            data = {
                action: action
            };

        if (btn.hasClass('active')) {
            deactivatePopup();
            return;
        }

        activatePopup(btn, 'loader_white');

        $.ajax({
            dataType: "json",
            type: "POST",
            url: url,
            data: data,
            success: function (result) {
                if (result.status) {
                    tab.html(result.html);
                    deactivateLoader();
                } else {
                    alert('Что-то пошло не так, попробуйте еще раз!!!');
                }
            },
            error: function (result) {
                alert('Что-то пошло не так, попробуйте еще раз!!!');
            }
        });

    });

    //Вызов формы "Избранное"
    $('.jsFavoriteForm').on('click', function (e) {
        e.preventDefault();

        let btn = btns.filter('[data-action=favorite]'),
            action = btn.data('action'),
            url = btn.data('ajax'),
            data = {
                action: action
            };

        if (btn.hasClass('active')) {
            deactivatePopup();
            return;
        }

        activatePopup(btn, 'loader_white');

        $.ajax({
            dataType: "json",
            type: "POST",
            url: url,
            data: data,
            success: function (result) {
                if (result.status) {
                    tab.html(result.html);
                    deactivateLoader();
                } else {
                    alert('Что-то пошло не так, попробуйте еще раз!!!');
                }
            },
            error: function (result) {
                alert('Что-то пошло не так, попробуйте еще раз!!!');
            }
        });

    });

    //Изменение выбора файла
    $('.popup').on('change', '.file__input', function (e) {
        let input = $(this),
            val = input.val(),
            item = input.closest('.file'),
            text = item.find('.file__title'),
            file = val.replace(/\\/g, '/').split('/').pop();

        if (val) {
            text.text(file);
            item.addClass('active');
        } else {
            text.text('Прикрепить');
            item.removeClass('active');
        }

    });

    //Удаление файла
    $('.popup').on('click', '.file__del', function (e) {
        e.preventDefault();
        let btn = $(this),
            item = btn.closest('.file'),
            text = item.find('.file__title'),
            input = item.find('.file__input');

        input.val('');
        text.text('Прикрепить');
        item.removeClass('active');
    });

    $('.popup').on('mouseover', '.rating2__star', function () {
        let star = $(this),
            rate = star.data('rate');

        setRatingText(rate);
    });

    $('.popup').on('mouseout', '.rating2__star', function () {
        let checked = $('.rating2__input:checked');
        if (checked.length == 0) {
            setRatingText('Без оценки');
        } else {
            setRatingText(checked.val());
        }
    });

    //Закрытие форм
    $('body').on('click', function (e) {
        let target = $(e.target);

        if (target.hasClass('popup active') || target.closest('.jsFormClose').length > 0) {
            deactivatePopup();
        }
    });

    //Смена количества
    $('body').on('click', '.count__minus', function (e) {
        e.preventDefault();
        let btn = $(this),
            count = btn.closest('.count'),
            input = count.find('.count__input')[0];
        input.stepDown();

        BX.fireEvent(input, "input");
    });

    $('body').on('click', '.count__plus', function (e) {
        e.preventDefault();
        let btn = $(this),
            count = btn.closest('.count'),
            input = count.find('.count__input')[0];
        input.stepUp();

        BX.fireEvent(input, "input");

    });

    // FUNCTIONS
    function getPlaceholder(i) {
        let input = $(i),
            field = input.closest('.popup__field'),
            placeholder = field.find('.popup__placeholder');

        return placeholder;
    }

    function activatePopup(btn, loaderClass) {
        popup.addClass('active');
        setOverflow();
        btns.removeClass('active');
        if (btn) {
            btn.addClass('active');
        }
        activateLoader(loaderClass);
        tab.html('');
    }

    function deactivatePopup() {
        popup.removeClass('active');
        removeOverflow();
        btns.removeClass('active');

        // TODO: Наверное стоит не обнулять Html так как возможно придется хешировать таблицу, чтобы не делались каждый раз запросы
        // при клацание на корзину
        tab.html('');
    }

    function activateLoader(loaderClass = 'loader_default') {
        popup.find('.popup__tabs').append(getLoader(loaderClass));
    }

    function deactivateLoader() {
        popup.find('.loader').remove();
    }

    function getLoader(cls) {
        let template = $(tmpl.content),
            loader = template.find('.loader').clone().addClass(cls);
        return loader;
    }

    function sendAjax(url, data) {
        $.ajax({
            dataType: "json",
            type: "POST",
            url: url,
            data: data,
            success: function (result) {
                if (result.status) {
                    tab.html(result.html);
                    deactivateLoader();

                    let form = tab.find('.popup__form');
                    $('input[type=tel]').mask('+7 (999) 999-99-99');

                    validateForm(form, tab)

                } else {
                    alert('Что-то пошло не так, попробуйте еще раз!!!');
                }
            },
            error: function (result) {
                alert('Что-то пошло не так, попробуйте еще раз!!!');
            }
        });
    }

    function validateForm(form, tab) {
        form.validate({
            onfocusout: false,
            submitHandler: function (form) {
                activateLoader('loader_white');
                let $form = $(form),
                    data = $form.serialize(),
                    url = $form.attr('action');

                $.ajax({
                    dataType: "json",
                    type: "POST",
                    url: url,
                    data: data,
                    success: function (result) {
                        if (result.status) {
                            tab.html(result.html);
                            deactivateLoader();


                        } else {
                            alert('Что-то пошло не так, попробуйте еще раз!!!');
                        }
                    },
                    error: function (result) {
                        alert('Что-то пошло не так, попробуйте еще раз!!!');
                    }
                });
            },
        });
    }

    function setRatingText(val) {
        $('.rating2__txt').text(val);
    }

    // show form in site
    const showForm = function (e) {
        e.preventDefault();

        const _this = $(this),
            btn = btns.filter('[data-form_name=' + $(this).data("form_name") + ']'),
            url = _this.data("request");

        const elements = {},
            quantity = _this.attr("data-quantity") || 1;

        if (_this.data("elements_id")) {
            elements[_this.data("elements_id")] = quantity;
        }

        // request data
        const data = {
            EVENT: _this.data("event") || '',
            ELEMENTS: elements || '',
            IBLOCK_ID: _this.data("form_id") || 0,
            FORM_NAME: _this.data("form_name") || '',
            EMAIL_EVENT_ID: _this.data("email_event_id") || 7,
        };

        if (btn.hasClass('active')) {
            deactivatePopup();
            return;
        }

        activatePopup(btn, 'loader_white');

        $.ajax({
            dataType: "json",
            type: "POST",
            url: url,
            data: data,
            success: function (result) {
                if (result.status) {
                    tab.html(result.html);
                    deactivateLoader();

                    const form = tab.find('.popup__form'),
                        tel = form.find('input[type=tel]');

                    if (tel.length > 0)
                        tel.mask(tel.data("mask"));

                    form.validate({
                        onfocusout: false,
                        submitHandler: function (form) {
                            activateLoader('loader_white');
                            const $form = $(form),
                                data = new FormData(),
                                url = $form.attr('action'),
                                file = form.querySelector('.file__input') || false,
                                inputs = $form.find('input[type!=file],textarea');


                            if (file)
                                if (file.files.length > 0) {
                                    data.append(file.name, file.files[0]);
                                }

                            inputs.each(function (x, i) {
                                if (i.type === 'radio') {
                                    if (i.checked)
                                        data.append(i.name, i.value);
                                } else {
                                    data.append(i.name, i.value);
                                }

                            });

                            $.ajax({
                                dataType: "json",
                                type: "POST",
                                url: url,
                                data: data,
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function (result) {
                                    if (result.status) {

                                        let formName = data.get("FORM_NAME");
                                        if (JSSeo.Yandex.Enabled == "Y") {
                                            new SEO().goal(formName);
                                        }


                                        if (!result.order) {
                                            tab.html(result.html);
                                            deactivateLoader();
                                        } else {
                                            window.location.href = "/cart/ok/";
                                        }

                                    } else {
                                        alert('Что-то пошло не так, попробуйте еще раз!!!');
                                    }
                                },
                                error: function (result) {
                                    alert('Что-то пошло не так, попробуйте еще раз!!!');
                                }
                            });
                        },
                    });

                } else {
                    alert('Что-то пошло не так, попробуйте еще раз!!!');
                }
            },
            error: function (result) {
                alert('Что-то пошло не так, попробуйте еще раз!!!');
            }
        });

    };

    const triggerClick = function (e) {
        e.preventDefault();

        const _this = $(this),
            target = _this.data("target"),
            elements_id = _this.data("elements_id"),
            btn = $("[data-form_name=" + target + "]");

        if (elements_id) {
            btn.data("elements_id", elements_id);
        }

        btn.trigger("click");
    };

    // Event open form in site
    $('body').on('click', '[data-event=showForm]', showForm);
    $('body').on('click', '[data-trigger=click]', triggerClick);

});

//catalog.js
$(function () {

    // VARIABLES
    const rangeFrom = $('.range__input_from'),
        rangeTo = $('.range__input_to'),
        range = $('.range__slider'),
        rangeMin = range.data('min'),
        rangeMax = range.data('max');

    // EVENTS
    $('.aitem__link_arr').on('click', function (e) {
        e.preventDefault();
        let $this = $(this),
            sub = $this.next();

        $this.toggleClass('active');
        sub.slideToggle(300);
    });

    // EVENTS
    $('.aitem__link_dropmenu').on('click', function (e) {
        e.preventDefault();
        let $this = $(this),
            sub = $this.parent().next();

        $this.parent().toggleClass('active');
        sub.slideToggle(300);
    });

    //Раскрытие фильтров
    $('.filter__title').on('click', function (e) {
        e.preventDefault();

        let $this = $(this),
            filter = $this.closest('.filter'),
            content = filter.find('.filter__content');


        content.slideToggle(300);
        filter.toggleClass('active');
    });

    //ввод цифр в поля
    $('.range__input').on('keypress', function (e) {
        if (e.key.match(/[^0-9]/g) || (this.value == 0 && e.key == 0)) {
            e.preventDefault();
        }
    });

    //Изменение данных
    $('.range__input').on('input', function (e) {
        let $this = $(this),
            val = '',
            from = getNumber(rangeFrom.val()),
            to = getNumber(rangeTo.val());

        if ($this.hasClass('range__input_from')) {
            if (from < rangeMin) {
                from = rangeMin;
            } else if (from > to) {
                from = to;
            }
            val = from;
        } else {
            if (to > rangeMax) {
                to = rangeMax;
            } else if (to < from) {
                to = from;
            }
            val = to;
        }

        let valPretty = prettify(val);

        rangeSlider.update({
            from: from,
            to: to
        });

        $this.val(valPretty);
    });

    //Открытие фильтров
    $('.filters-btn').on('click', function (e) {
        e.preventDefault();

        openFilters();
    });

    //Сброс фильтров
    $('.filters__reset').on('click', function (e) {
        let btn = $(this),
            form = btn.closest('.filters');
        form.trigger('reset');
        rangeUpdate();
    });

    //Закрытие фильтров
    $('body').on('click', function (e) {
        let target = $(e.target);

        if (target.hasClass('filters active') || target.closest('.filters__close').length > 0) {
            closeFilters();
        }
    });

    // FUNCTIONS
    function rangeUpdate() {
        let from = getNumber(rangeFrom.val()),
            to = getNumber(rangeTo.val());

        rangeSlider.update({
            from: from,
            to: to
        });
    }

    // Функция отрытия фильтров
    function openFilters() {
        let filters = $('.filters');
        filters.addClass('active');
        setOverflow();
    }

    // Функция закрытия фильтров
    function closeFilters() {
        let filters = $('.filters');
        filters.removeClass('active');
        removeOverflow();
    }


    //VENDORS
    $(".range__slider").ionRangeSlider({
        prettify_enabled: true,
        skin: 'round',
        hide_from_to: true,
        // hide_min_max: true,
        onChange: function (data) {
            rangeFrom.val(data.from_pretty);
            rangeTo.val(data.to_pretty);
        },
        onFinish: function (data) {
            //ToDo Здесь должень быть ajax на получение товаров
        },
    });
    let rangeSlider = $(".range__slider").data("ionRangeSlider");

});

//contacts.js
$(function () {

    // VARIABLES

    // EVENTS

    // FUNCTIONS

    //VENDORS

    // Валидация формы
    $('.ccall').validate({
        onfocusout: false,
        submitHandler: function (form) {
            const $form = $(form),
                data = new FormData(),
                url = $form.attr('action'),
                inputs = $form.find('input[type!=file],textarea');

            inputs.each(function (x, i) {
                data.append(i.name, i.value);
            });

            new Recaptcha(form, {
                onSubmit: function () {
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: url,
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (result) {
                            if (result.status) {
                                $(".ccall__container").html(result.html);
                            } else {
                                alert('Что-то пошло не так, попробуйте еще раз!!!');
                            }
                        },
                        error: function (result) {
                            alert('Что-то пошло не так, попробуйте еще раз!!!');
                        }
                    });
                }
            });


        },
        invalidHandler: function (event, validator) {
            // debugger;
        },
        errorPlacement: function (error, element) {
            element[0].placeholder = error[0].innerText;
            // debugger;
        }
    });

});

window.JSMarket = function (arParams) {
    // add class for elements (favorite, compare, cart) before loading dom
    window.JSChecker = new JSChecker(arParams);
    // render template for update
    window.JSRender = new JSRender();
    // catalog function
    window.JSCatalog = new JSCatalog(arParams);
    // cart function
    window.JSCart = new JSCart(arParams);
    // other function
    window.JSTools = new JSTools(arParams);

    if (arParams.isCartPage) {
        // only in cart page pay function
        window.JSCartPay = new JSCartPay(arParams.payItems);
        // only in cart page delivery function
        window.JSCartDelivery = new JSCartDelivery();
    }

};

window.JSChecker = function (arParams = {}) {
    const favoriteList = arParams.FavoriteList || false;
    const compareList = arParams.CompareList || false;
    const cartList = arParams.CartList || false;

    if (favoriteList) this.checkFavorite(favoriteList);
    if (compareList) this.checkCompare(compareList);
    if (cartList) this.checkCart(cartList);
};

window.JSChecker.prototype = {
    checkFavorite: function (favoriteList) {
        const favoriteIcons = document.querySelectorAll("[data-event='changeFavoriteList']");
        const favoriteCount = this.getCount(favoriteList);
        if (favoriteIcons.length)
            for (let i = 0; i < favoriteIcons.length; i++)
                favoriteIcons[i].classList.remove("active");

        for (let item in favoriteList) {
            const element = document.querySelectorAll("[data-event='changeFavoriteList'][data-id='" + item + "']");
            if (element.length)
                for (let i = 0; i < element.length; i++)
                    element[i].classList.add("active");
        }

        const favoriteCountIcon = document.querySelectorAll("[data-type='favoriteCount']");
        if (favoriteCountIcon.length)
            for (let i = 0; i < favoriteCountIcon.length; i++)
                favoriteCountIcon[i].innerHTML = favoriteCount.toString();

    },
    checkCompare: function (compareList) {
        const compareIcons = document.querySelectorAll("[data-event='changeCompareList']");
        const compareCount = this.getCount(compareList);
        if (compareIcons.length)
            for (let i = 0; i < compareIcons.length; i++)
                compareIcons[i].classList.remove("active");

        for (let item in compareList) {
            const element = document.querySelectorAll("[data-event='changeCompareList'][data-id='" + item + "']");
            if (element.length)
                for (let i = 0; i < element.length; i++)
                    element[i].classList.add("active");
        }

        const compareCountIcon = document.querySelectorAll("[data-type='compareCount']");
        if (compareCountIcon.length)
            for (let i = 0; i < compareCountIcon.length; i++)
                compareCountIcon[i].innerHTML = compareCount.toString();
    },
    checkCart: function (cartList) {
        const cartIcons = document.querySelectorAll("[data-event='addToCart']");
        const cartCount = this.getCount(cartList);
        if (cartIcons.length)
            for (let i = 0; i < cartIcons.length; i++)
                cartIcons[i].classList.remove("add_in");

        for (let item in cartList) {
            const element = document.querySelectorAll("[data-event='addToCart'][data-id='" + item + "']");
            if (element.length)
                for (let i = 0; i < element.length; i++)
                    element[i].classList.add("add_in");
        }

        const cartCountIcon = document.querySelectorAll("[data-type='cartCount']");
        if (cartCountIcon.length)
            for (let i = 0; i < cartCountIcon.length; i++)
                cartCountIcon[i].innerHTML = cartCount.toString();
    },
    getCount: function (elementList) {
        let elementCount = 0;

        for (let item in elementList)
            elementCount += parseInt(elementList[item]);

        return elementCount;
    }
};

window.JSRender = function () {
};

window.JSRender.prototype = {
    getTemplate: function (template = "#template") {
        if (template.nodeType === undefined)
            return document.querySelector(template);
        return template;
    },
    replaceMark: function (template, data) {
        if (template !== undefined) {
            if (data) {

                if (data.hasOwnProperty("CHECK_BLOCK")) {
                    template = this.removeFalseMark(template, data.CHECK_BLOCK);
                }

                let currentTemplate = template.innerHTML;

                for (let item in data) {
                    if (data.hasOwnProperty(item)) {
                        let find = new RegExp("{{" + item + "}}", "g");
                        currentTemplate = currentTemplate.replace(find, data[item]);
                    }
                }

                return currentTemplate;
            }
        }
    },
    removeFalseMark: function (template, data) {
        let _removedFalseMarkTpl = template;
        for (let item in data) {
            if (data[item] === false) {
                const node = _removedFalseMarkTpl.content.querySelectorAll("div[data-if='" + item + "']");
                if (node.length) {
                    for (let i = 0; i < node.length; i++) {
                        node[i].parentNode.removeChild(node[i]);
                    }
                }
            }
        }
        return _removedFalseMarkTpl;
    },
    render: function (template, target, data = {}) {
        const _tpl = this.getTemplate(template);
        if (_tpl) {
            const _trg = document.querySelectorAll(target);
            if (_trg && _trg.length) {
                const renderTemplate = window.JSRender.replaceMark(_tpl, data);
                if (renderTemplate !== undefined) {
                    for (let i = 0; i < _trg.length; i++) {
                        _trg[i].innerHTML = renderTemplate;
                    }
                }
            }
        }
    }
};

window.JSCatalog = function (arParams = {}) {
    this.bindEvent();
}

window.JSCatalog.prototype = {
    bindEvent: function () {
        BX.bindDelegate(document, "click", {attribute: {"data-event": "changeFavoriteList"}}, this.changeFavoriteList);
        BX.bindDelegate(document, "click", {attribute: {"data-event": "changeCompareList"}}, this.changeCompareList);
        BX.bindDelegate(document, "click", {attribute: {"data-event": "addToCart"}}, this.addToCart);
        BX.bindDelegate(document, "click", {attribute: {"data-event": "clearFavoriteList"}}, this.clearFavoriteList);
        BX.bindDelegate(document, "click", {attribute: {"data-event": "clearCompareList"}}, this.clearCompareList);
        BX.bindDelegate(document, "input", {attribute: {"data-event": "changeQuantity"}}, this.changeQuantity);
    },
    extensionEvent(element, event, result) {
        if (element) {
            if (event === "removeNodeFavoriteItem") {
                const removedParent = BX.findParent(element, {attribute: {"data-type": "item"}});
                if (removedParent) BX.remove(removedParent);

                if (result.html !== undefined) {
                    if (parseInt(result.html) === 0) {
                        window.JSRender.render("#tpl_emptyFavorite", "#favoriteRender");
                    } else {
                        const template = window.JSRender.getTemplate("#tpl_favoriteCount");
                        if (template) {
                            const num2word = window.JSTools.num2word(parseInt(result.html), ['товар', 'товара', 'товаров']),
                                data = {COUNT_PRD: parseInt(result.html) + " " + num2word};
                            window.JSRender.render(template, ".tpl_favoriteCountContainer", data);
                        }
                    }
                }
            }

            if (event === "removeNodeCompareItem") {
                const removedParent = BX.findParent(element, {attribute: {"data-type": "item"}});
                if (removedParent) BX.remove(removedParent);

                if (result.html !== undefined) {
                    if (parseInt(result.html) === 0) {
                        window.JSRender.render("#tpl_emptyCompare", "#compareRender");
                    } else {
                        const template = window.JSRender.getTemplate("#tpl_compareCount");
                        if (template) {
                            const num2word = window.JSTools.num2word(parseInt(result.html), ['товар', 'товара', 'товаров']),
                                data = {COUNT_PRD: parseInt(result.html) + " " + num2word};
                            window.JSRender.render(template, ".tpl_compareCountContainer", data);

                            // if compare page
                            const sliderElement = document.querySelector("[data-slider2-compare-id='" + element.dataset.id + "']");
                            if (sliderElement) BX.remove(sliderElement);
                        }
                    }
                }
            }
        }
    },
    changeFavoriteList: function (e) {
        e.preventDefault();

        const data = {
            EVENT: this.dataset.event || "",
            ID: this.dataset.id || "",
            EXT: this.dataset.ext || false,
        };

        BX.ajax({
            url: this.dataset.request,
            data: data,
            method: 'POST',
            dataType: 'json',
            timeout: 30,
            async: true,
            processData: true,
            scriptsRunFirst: true,
            emulateOnload: true,
            start: true,
            cache: false,
            onsuccess: (result) => {
                if (result.status) {
                    window.JSChecker.checkFavorite(result.data);
                    // if need use extension event in this element
                    if (data.EXT) window.JSCatalog.extensionEvent(this, data.EXT, result);
                }
            },
            onfailure: () => {
                console.error("add element to favoriteList failed");
            }
        });
    },
    clearFavoriteList: function (e) {
        e.preventDefault();

        const data = {
            EVENT: this.dataset.event || "",
        };

        BX.ajax({
            url: this.dataset.request,
            data: data,
            method: 'POST',
            dataType: 'json',
            timeout: 30,
            async: true,
            processData: true,
            scriptsRunFirst: true,
            emulateOnload: true,
            start: true,
            cache: false,
            onsuccess: (result) => {
                if (result.status) {
                    window.JSChecker.checkFavorite(result.data);
                    window.JSRender.render("#tpl_emptyFavorite", "#favoriteRender");
                }
            },
            onfailure: () => {
                console.error("add element to compareList failed");
            }
        });
    },
    changeCompareList: function (e) {
        e.preventDefault();

        const data = {
            EVENT: this.dataset.event || "",
            ID: this.dataset.id || "",
            EXT: this.dataset.ext || false,
        };

        BX.ajax({
            url: this.dataset.request,
            data: data,
            method: 'POST',
            dataType: 'json',
            timeout: 30,
            async: true,
            processData: true,
            scriptsRunFirst: true,
            emulateOnload: true,
            start: true,
            cache: false,
            onsuccess: (result) => {
                if (result.status) {
                    window.JSChecker.checkCompare(result.data);
                    // if need use extension event in this element
                    if (data.EXT) window.JSCatalog.extensionEvent(this, data.EXT, result);
                }
            },
            onfailure: () => {
                console.error("add element to compareList failed");
            }
        });
    },
    clearCompareList: function (e) {
        e.preventDefault();

        const data = {
            EVENT: this.dataset.event || "",
        };

        BX.ajax({
            url: this.dataset.request,
            data: data,
            method: 'POST',
            dataType: 'json',
            timeout: 30,
            async: true,
            processData: true,
            scriptsRunFirst: true,
            emulateOnload: true,
            start: true,
            cache: false,
            onsuccess: (result) => {
                if (result.status) {
                    window.JSChecker.checkCompare(result.data);
                    window.JSRender.render("#tpl_emptyCompare", "#compareRender");
                }
            },
            onfailure: () => {
                console.error("add element to compareList failed");
            }
        });
    },
    addToCart: function (e) {
        e.preventDefault();

        const _element = this;

        if (!BX.hasClass(_element, "add_in")) {

            let quantity = 1;
            const itemParent = BX.findParent(this, {attribute: {"data-type": "item"}});

            if (itemParent) {
                const inputQuantity = BX.findChild(itemParent, {attribute: {"data-type": "itemQuantity"}}, true);
                if (inputQuantity) quantity = parseInt(inputQuantity.value);
            }

            const data = {
                EVENT: this.dataset.event || "",
                ID: this.dataset.id || "",
                QUANTITY: quantity,
            };

            BX.ajax({
                url: this.dataset.request,
                data: data,
                method: 'POST',
                dataType: 'json',
                timeout: 30,
                async: true,
                processData: true,
                scriptsRunFirst: true,
                emulateOnload: true,
                start: true,
                cache: false,
                onsuccess: (result) => {
                    if (result.status) {
                        window.JSChecker.checkCart(result.data);
                        const cartBtn = document.querySelector("[data-action='cart']");
                        if (cartBtn) BX.fireEvent(cartBtn, "click");
                        if (JSSeo.Yandex.Enabled == "Y") {
                            new SEO().goal("ADD");
                        }
                    }
                },
                onfailure: () => {
                    console.error("add element to cart failed");
                }
            });
        }

    },
    changeQuantity: function () {
        const parent = BX.findParent(this, {attribute: {"data-type": "item"}}),
            target = BX.findChild(parent, {attribute: {"data-type": "changeQuantityBtn"}}, true),
            quantity = parseInt(this.value) || 1;

        if (target) target.dataset.quantity = quantity;
    },
    getFormatCurrency: function () {
        return "руб."
    },
    getFormatPrice: function (price) {
        const currentPrice = window.JSTools.numberFormat(price),
            currentCurrency = this.getFormatCurrency();

        return currentPrice + " " + currentCurrency;

    },
};

window.JSCart = function () {
    this.bindEvent();
};

window.JSCart.prototype = {
    bindEvent: function () {
        BX.bindDelegate(document, "click", {attribute: {"data-event": "clearCart"}}, this.clearCart);
        BX.bindDelegate(document, "change", {attribute: {"name": "DELIVERY"}}, this.changeTotal);
        BX.bindDelegate(document, "click", {attribute: {"data-event": "deleteProductInCart"}}, this.deleteProductInCart);
        BX.bindDelegate(document, "input", {attribute: {"data-event": "changeQuantityInCart"}}, this.changeQuantityInCart);
        BX.bindDelegate(document, "click", {attribute: {"data-event": "orderSubmit"}}, this.orderSubmit);
    },
    orderSubmit: function (e) {
        const form = $(this).parents("form");

        form.validate({
            onfocusout: false,
            submitHandler: function (form) {
                const $form = $(form),
                    data = new FormData(),
                    url = $form.attr('action'),
                    inputs = $form.find('input[type!=file],textarea');

                inputs.each(function (x, i) {
                    if (i.type === 'radio') {
                        if (i.checked)
                            data.append(i.name, i.value);
                    } else {
                        data.append(i.name, i.value);
                    }
                });

                $.ajax({
                    dataType: "json",
                    type: "POST",
                    url: url,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        if (result.status) {
                            if (JSSeo.Yandex.Enabled == "Y") {
                                new SEO().goal("ORDER");
                            }
                            window.location.href = result.html;
                        }
                    },
                    error: function (result) {
                        alert('Что-то пошло не так, попробуйте еще раз!!!');
                    }
                });
            },
            invalidHandler: function (event, validator) {
                // debugger;
            },
            errorPlacement: function (error, element) {

                element[0].placeholder = error[0].innerText;
                // debugger;
            }
        });
    },
    clearCart: function () {

        const data = {
            EVENT: this.dataset.event || "",
        };

        BX.ajax({
            url: this.dataset.request,
            data: data,
            method: 'POST',
            dataType: 'json',
            timeout: 30,
            async: true,
            processData: true,
            scriptsRunFirst: true,
            emulateOnload: true,
            start: true,
            cache: false,
            onsuccess: (result) => {
                if (result.status) {
                    window.JSChecker.checkCart(result.data);
                    const renderTemplate = window.JSRender.getTemplate("#tpl_emptyCart");
                    if (renderTemplate) window.JSRender.render(renderTemplate, "#cartRender", data);
                }
            },
            onfailure: () => {
                console.error("ajax request rejected");
            }
        });
    },
    deleteProductInCart: function () {
        const data = {
            EVENT: this.dataset.event || "",
            ID: this.dataset.id || ""
        };

        BX.ajax({
            url: this.dataset.request,
            data: data,
            method: 'POST',
            dataType: 'json',
            timeout: 30,
            async: true,
            processData: true,
            scriptsRunFirst: true,
            emulateOnload: true,
            start: true,
            cache: false,
            onsuccess: (result) => {
                if (result.status) {
                    const parent = BX.findParent(this, {attribute: {"data-type": "cartItem"}});
                    BX.remove(parent);

                    const cartCountIcon = document.querySelectorAll("[data-type='cartCount']");
                    if (cartCountIcon.length)
                        for (let i = 0; i < cartCountIcon.length; i++)
                            cartCountIcon[i].innerHTML = result.html;

                    window.JSChecker.checkCart(result.data);

                    if (parseInt(result.html) === 0) {
                        const renderTemplate = window.JSRender.getTemplate("#tpl_emptyCart");
                        if (renderTemplate) window.JSRender.render(renderTemplate, "#cartRender", data);
                    } else {
                        window.JSCart.changeTotal();
                    }

                }
            },
            onfailure: () => {
                console.error("delete products in cart failed");
            }
        });
    },
    changeQuantityInCart: function () {

        const parent = BX.findParent(this, {attribute: {"data-type": "cartItem"}}),
            target = BX.findChild(parent, {attribute: {"data-type": "cartItemTotalPrice"}}, true);

        const data = {
            EVENT: this.dataset.event || "",
            ID: this.dataset.id || "",
            COUNT: parseInt(this.value) || 1,
            PRICE: parent.dataset.price || 1,
        };

        BX.ajax({
            url: this.dataset.request,
            data: data,
            method: 'POST',
            dataType: 'json',
            timeout: 30,
            async: true,
            processData: true,
            scriptsRunFirst: true,
            emulateOnload: true,
            start: true,
            cache: false,
            onsuccess: (result) => {
                if (result.status) {
                    const cartCountIcon = document.querySelectorAll("[data-type='cartCount']");
                    target.innerHTML = window.JSCatalog.getFormatPrice(result.price);

                    if (cartCountIcon.length)
                        for (let i = 0; i < cartCountIcon.length; i++)
                            cartCountIcon[i].innerHTML = result.html;

                    window.JSCart.changeTotal();
                }
            },
            onfailure: () => {
                console.error("change quantity failed");
            }
        });
    },
    getQuantity: function (item) {
        const input = item.querySelector("input[data-type='cartItemQuantity']");
        if (input) return parseInt(input.value);

    },
    getTotalPrice: function () {
        const _elements = document.querySelectorAll("[data-type='cartItem']");
        let totalPrice = 0;

        if (_elements.length) {
            for (let i = 0; i < _elements.length; i++) {
                let count = this.getQuantity(_elements[i]);
                totalPrice += parseInt(count) * parseFloat(_elements[i].dataset.price);
            }
        }

        return totalPrice;
    },
    getTotalEconomy: function () {
        const _elements = document.querySelectorAll("[data-type='cartItem']");
        let totalEconomy = 0;

        if (_elements.length) {
            for (let i = 0; i < _elements.length; i++) {
                let count = this.getQuantity(_elements[i]);
                if (_elements[i].dataset.economy.length)
                    totalEconomy += parseInt(count) * parseFloat(_elements[i].dataset.economy);
            }
        }

        return totalEconomy;
    },
    getTotalQuantity: function () {
        const _elements = document.querySelectorAll("[data-type='cartItem']");
        let totalCount = 0;

        if (_elements.length) {
            for (let i = 0; i < _elements.length; i++) {
                totalCount += parseInt(this.getQuantity(_elements[i]));
            }
        }

        return totalCount;
    },
    changeTotalCount: function () {

        let template = window.JSRender.getTemplate("#tpl_productsCount");
        if (template) {
            const count = this.getTotalQuantity(),
                num2word = window.JSTools.num2word(count, ['товар', 'товара', 'товаров']),
                data = {
                    COUNT_PRD: count + " " + num2word
                };
            window.JSRender.render(template, ".tpl_productsCountContainer", data);

        }
    },
    changeTotalBottomBlock: function () {
        let template = window.JSRender.getTemplate("#tpl_totalBottom");
        if (template) {
            let totalProductPrice = this.getTotalPrice(),
                totalPrice = totalProductPrice,
                deliveryPrice = window.JSCartDelivery.data._PRICE || false;

            if (deliveryPrice) {
                totalPrice += parseInt(deliveryPrice);
            }

            const data = {
                TOTAL_PRD_PRICE: window.JSCatalog.getFormatPrice(totalProductPrice),
                TOTAL_DELIVERY_PRICE: (parseInt(deliveryPrice) === 0) ? "Бесплатно" : window.JSCatalog.getFormatPrice(deliveryPrice),
                TOTAL_PRICE: window.JSCatalog.getFormatPrice(totalPrice),
                CHECK_BLOCK: {
                    DELIVERY: !!deliveryPrice,
                },
            };

            window.JSRender.render(template, '#tpl_totalBottomContainer', data);
        }
    },
    changeTotalRightBlock: function () {
        let template = window.JSRender.getTemplate("#tpl_totalRight");
        if (template) {

            let totalProductPrice = this.getTotalPrice(),
                totalPrice = totalProductPrice,
                totalEconomy = this.getTotalEconomy() || 0,
                deliveryPrice = window.JSCartDelivery.data._PRICE || false;

            if (deliveryPrice) {
                totalPrice += parseInt(deliveryPrice);
            }

            const data = {
                TOTAL_PRD_PRICE: window.JSCatalog.getFormatPrice(totalProductPrice),
                TOTAL_DELIVERY_PRICE: (parseInt(deliveryPrice) === 0) ? "Бесплатно" : window.JSCatalog.getFormatPrice(deliveryPrice),
                TOTAL_PRICE: window.JSCatalog.getFormatPrice(totalPrice),
                TOTAL_PRD_OLD_PRICE: window.JSCatalog.getFormatPrice(totalProductPrice + totalEconomy),
                TOTAL_ECONOMY: window.JSCatalog.getFormatPrice(totalEconomy),
                CHECK_BLOCK: {
                    DELIVERY: !!deliveryPrice,
                    ECONOMY: (totalEconomy > 0),
                    OLD_PRICE: (totalEconomy > 0)
                },
            };

            window.JSRender.render(template, '#tpl_totalRightContainer', data);
        }
    },
    changeTotalFloatBlock: function () {
        let template = window.JSRender.getTemplate("#tpl_totalFloatCart");
        if (template) {
            let totalPrice = this.getTotalPrice();

            const data = {
                TOTAL_PRICE: window.JSCatalog.getFormatPrice(totalPrice)
            };

            window.JSRender.render(template, '#tpl_totalFloatCartContainer', data);
        }
    },
    changeTotal: function () {
        window.JSCart.changeTotalCount();
        window.JSCart.changeTotalBottomBlock();
        window.JSCart.changeTotalRightBlock();
        window.JSCart.changeTotalFloatBlock();
    },
};

window.JSCartDelivery = function () {
    this.bindEvent();
    this.run();
};

window.JSCartDelivery.prototype = {
    run: function () {
        this.getDelivery();
        window.JSRender.render("#tpl_delivery", '#tpl_deliveryContainer', this.data);
        this.additionalField();
    },
    bindEvent: function () {
        const elements = document.querySelectorAll('input[name="DELIVERY"]');
        if (elements.length)
            for (let i = 0; i < elements.length; i++) {
                let item = elements[i];
                BX.bind(item, 'click', this.changeDelivery);
            }
    },
    getDelivery: function () {
        const element = document.querySelector('input[name="DELIVERY"]:checked');

        window.JSCartPay.setCurrentItems(element.dataset.pay.split(","));
        window.JSCartPay.renderItems();

        if (element)
            this.data = {
                NAME: element.dataset.name,
                DESCRIPTION: element.dataset.description,
                PERIOD: element.dataset.period,
                PRICE: element.dataset.price_for_user || element.dataset.price,
                _PRICE: element.dataset.price,
                HIDE: element.dataset.hide_field,
            };
    },
    changeDelivery: function () {
        window.JSCartDelivery.run();

    },
    additionalField: function () {
        const field = document.querySelector('#additionalField');
        if (field) {
            (this.data.HIDE.length) ? BX.hide(field) : BX.show(field);
        }
    }
};

window.JSCartPay = function (items) {
    this.items = items;
    this.currentItems = this.items;
    this.itemsContainer = document.querySelector(".payopts");
    // this.run();
};

window.JSCartPay.prototype = {
    run: function () {
        this.getPay();
        window.JSRender.render("#tpl_pay", '#tpl_payContainer', this.data);
        this.renderItems();
    },
    bindEvent: function () {
        const elements = document.querySelectorAll('input[name="PAY"]');
        if (elements.length)
            for (let i = 0; i < elements.length; i++) {
                BX.bind(elements[i], 'click', this.changePay.bind(this));
            }
    },
    getPay: function () {
        const element = document.querySelector('input[name="PAY"]:checked');
        if (element)
            this.data = {
                NAME: element.dataset.name,
                DESCRIPTION: element.dataset.description,
            };
    },
    changePay: function () {
        this.getPay();
        window.JSRender.render("#tpl_pay", '#tpl_payContainer', this.data);
    },
    clearItems: function () {
        this.itemsContainer.innerHTML = '';
    },
    renderItems: function () {
        this.clearItems();
        this.currentItems.forEach((i) => {
            this.renderItem(i);
        });
        this.checkItems();
        this.getPay();
        window.JSRender.render("#tpl_pay", '#tpl_payContainer', this.data);
        this.bindEvent();
    },
    renderItem: function (i) {
        let templ = window.JSRender.getTemplate("#tpl_paopt");
        let item = window.JSRender.replaceMark(templ, i);
        this.itemsContainer.insertAdjacentHTML('beforeend', item);
    },
    checkItems: function () {
        let items = document.querySelectorAll('input[name="PAY"]');
        if (!Array.from(items).some(i => i.checked)) {
            items[0].checked = true;
        }
    },
    setCurrentItems: function (arr) {
        this.currentItems = this.items.filter(i => arr.includes(i.value));
    }
};

window.JSTools = function (arParams) {
    this.priceFormat = {
        decimals: arParams.CatalogSettings.decimal || 0,
        dec_point: arParams.CatalogSettings.decimalPoint || ".",
        thousands_sep: arParams.CatalogSettings.thousandthSeporator || " ",
    };
};

window.JSTools.prototype = {
    numberFormat: function (number,
                            decimals = this.priceFormat.decimals,
                            dec_point = this.priceFormat.dec_point,
                            thousands_sep = this.priceFormat.thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    },
    num2word: function (number = 1, words = []) {
        return words[(number % 100 > 4 && number % 100 < 20)
            ? 2 : [2, 0, 1, 1, 1, 2][(number % 10 < 5)
                ? number % 10 : 5]];
    }
};

class SEO {
    constructor() {
        this.YandexID = JSSeo.Yandex.YandexID;
        this.CALLORDER = "callBack";
        this.REVIEWS = "rightReview";
        this.CALLORDER_FOOTER = "callBackfooter";
        this.QUESTION = "callBack";
        this.SERVICE = "callBack";
        this.ADD = "addBasket";
        this.ONE_CLICK_BUY = "1click";
        this.ORDER = "orderSuccess";
    }

    goal(formName) {
        console.log("Yandex reachGoal: " + this[formName])
        ym(this.YandexID, 'reachGoal', this[formName]);
    }
}

class Recaptcha {
    constructor(form, settings) {
        this.form = form;
        this.input = form.querySelector('input[name="TOKEN"]');
        this.token = '';
        const that = this;
        this.onSubmit = settings.onSubmit;
        this.onError = settings.onError || function () {
            alert('Вы слишком похожи на робота, извините!!!');
            return;
        };

        this.init()
    }

    init() {
        let that = this;

        if (JSRecaptcha.status == "Y") {
            grecaptcha.ready(function () {
                grecaptcha.execute(JSRecaptcha.public, {action: 'homepage'}).then(
                    (token) => {
                        that.input.value = token;
                        that.token = token;

                        fetch('/ajax/recaptcha/', {
                            method: 'POST',
                            body: new FormData(that.form)
                        }).then(response => response.json()).then(function (data) {
                            // console.log(data);
                            if (data.success) {
                                that.onSubmit();
                            } else {
                                that.onError();
                            }
                        });

                    });
            });
        } else {
            that.onSubmit();
        }
    }
}
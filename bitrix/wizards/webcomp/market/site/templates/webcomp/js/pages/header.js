//header.js
$(function () {

    // VARIABLES
    let $menus = $('.menu'),
        menusObj = {},
        search = $('.hsearch'),
        searchWrap = search.find('.hsearch__html');

    // Применяем плагин адаптивного меню к менюшкам
    $menus.each(function (x, i) {
        let menu = $(i);
        menusObj['menu' + x] = new AdaptiveMenu(menu);
        menusObj['menu' + x].init();
    });

    const headerFixed = $('.fixmenu'),
        headerMain = $('.header');

    // EVENTS
    // Фиксированная шапка
    $(window).on('scroll', function (e) {
        let height = headerMain.outerHeight(),
            scrollTop = $(this).scrollTop(),
            scrollBtn = $('.scroll__btn');

        if (scrollTop > height) {
            headerFixed.addClass('active');
        } else {
            headerFixed.removeClass('active');
        }

        if (scrollTop > 500) {
            scrollBtn.addClass('active');
        } else {
            scrollBtn.removeClass('active');
        }

    });

    //Открытие формы поиска
    $('.jsSearch').on('click', function (e) {
        e.preventDefault();
        openSearch();
    });

    //TODO пока отключено, надо доделать
    //ввод в поле поиска
    /*$('.hsearch__input').on('input', $.debounce( 300, function () {


        let $form = $(this).closest('.hsearch'),
            data = $form.serialize(),
            url = $form.attr('action');

        // debugger;

        $.ajax({
            dataType: "json",
            type: "POST",
            url: url,
            data: data,
            success: function (result) {
                if (result.status) {
                    searchWrap.html(result.html);
                } else {
                    alert('Что-то пошло не так, попробуйте еще раз!!!');
                }
            },
            error: function (result) {
                alert('Что-то пошло не так, попробуйте еще раз!!!');
            }
        });
    }));*/

    //Закрытие формы поиска
    $('body').on('click', function (e) {
        let target = $(e.target);

        if ((target.closest('.hsearch').length == 0 && target.closest('.jsSearch').length == 0) || target.closest('.jsSearchClose').length > 0) {
            closeSearch();
        }
    });

    // FUNCTIONS
    function openSearch() {
        search.addClass('active');
    }

    function closeSearch() {
        search.removeClass('active');
        searchWrap.html('');
    }

    //VENDORS
    //Если планшет или мобила, то клик отрабатывает как стандартный ховер
    if (device.tablet() || device.mobile()) {
        $('.jsHoverLink').on('click', function (e) {
            e.preventDefault();
            let link = $(this),
                item = link.closest('.jsHoverItem');

            $(this).trigger('hover');
        });
    }

});
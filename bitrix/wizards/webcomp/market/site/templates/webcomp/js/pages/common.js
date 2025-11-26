// common.js
function getNumber(str) {
    return parseInt(str.replace(/\s/g, ''));
}

function number_format(number, decimals, dec_point, thousands_sep) {
    var i, j, kw, kd, km;
    if (isNaN(decimals = Math.abs(decimals))) {
        decimals = 2;
    }
    if (dec_point == undefined) {
        dec_point = ",";
    }
    if (thousands_sep == undefined) {
        thousands_sep = ".";
    }
    i = parseInt(number = (+number || 0).toFixed(decimals)) + "";
    if ((j = i.length) > 3) {
        j = j % 3;
    } else {
        j = 0;
    }
    km = (j ? i.substr(0, j) + thousands_sep : "");
    kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
    kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");
    return km + kw + kd;
}

function imageResize(src) {
    $('img').not('.logo__img').attr('src', src);
}

// imageResize('https://loremflickr.com/320/440');

const wWidth = $(window).width();

// Валидация телефона
$.validator.methods.tel = function (value, element) {
    return validateTel(value,element);
};

function validateTel(value,element) {
    let re = new RegExp(/\d/g),
        str = value.match(re);
    let lenght = element.dataset.mask.match(re).length;

    if (str.length == lenght) {
        return true;
    } else {
        return false;
    }
}

// Инициализация параметров слайдера из дата атрибутов
function initParams(item, slider) {

    let params = item.params,
        data = slider.dataset,
        speed = parseInt(data.speed) || 300,
        autoplay = (data.autoplay == 'true'),
        delay = parseInt(data.autoplaydelay) || 5000,
        pagination = (data.pagination == 'true');

    params.speed = speed;

    if (autoplay) {
        params.autoplay.enabled = autoplay;
        params.autoplay.delay = delay;
    }


    if (!pagination) {
        params.pagination = {};
    }
}

// Установить тему
function setTheme(slide, slider) {
    let theme = slide.dataset.theme;
    slider.setAttribute('data-theme', theme);
    document.querySelector('.header').setAttribute('data-theme', theme);
}

// Миник плагин адаптивного меню
function AdaptiveMenu(menu) {
    this.menu = menu;
    this.items = this.menu.find('.menu__item');
    this.list = this.menu.find('.menu__list');
    this.more = this.menu.find('.menu__more');
    this.menuWidth = parseInt(this.menu.outerWidth());
    this.listWidth = parseInt(this.list.outerWidth());
    this.moreWidth = parseInt(this.more.outerWidth());
    this.moreList = this.more.find('.mmore__list');
    this.width = this.listWidth + this.moreWidth;
    this.cutItems = [];
    this.lastItem = this.cutItems[this.cutItems.length - 1];

    let that = this;

    $(window).resize(function () {
        that.update();
        that.compareWidth();
    });

    this.init = function () {
        that.compareWidth();
    };

    this.compareWidth = function () {

        if (that.menu.hasClass('cut')) {
            that.compareCutted();
        } else {
            that.compareNonCutted();
        }

    };

    this.compareNonCutted = function () {
        if (that.listWidth > that.menuWidth) {
            that.menu.addClass('cut');
            that.removeLastItem();
            that.update();
            that.addItemToMore();
            that.compareCutted();
        }
    };

    this.compareCutted = function () {
        that.list.addClass('nogrow');

        if (that.width > that.menuWidth) {
            while (that.width > that.menuWidth) {
                that.removeLastItem();
                that.update();
                that.addItemToMore();
            }
        } else {
            while (that.lastItem.width + that.listWidth <= that.menuWidth) {
                that.addLastItem();
                that.removeItemFromMore();
                that.update();
                if (!that.lastItem) {
                    that.menu.removeClass('cut');
                    break;
                }
            }
        }

        that.list.removeClass('nogrow');
    };

    this.removeLastItem = function () {
        let item = $(that.items[that.items.length - 1]),
            link = item.find('.menu__link');

        that.cutItems.push({
            width: parseInt(item.outerWidth()),
            item: item,
            href: link.attr('href'),
            title: link.text()
        });
        item.detach();
    };

    this.addLastItem = function () {
        let item = that.lastItem.item;
        that.list.append(item);
        that.cutItems.pop();
    };

    this.addItemToMore = function () {
        let item = that.lastItem,
            link = $('<a>').addClass('mmore__item').attr('href', item.href).text(item.title);

        that.moreList.append(link);
    };

    this.removeItemFromMore = function () {
        let item = that.lastItem;
        that.moreList.find('a[href="' + item.href + '"]').remove();
    };

    this.update = function () {
        that.list.addClass('nogrow');
        that.items = that.menu.find('.menu__item');
        // that.list = that.menu.find('.menu__list');
        // that.more = that.menu.find('.menu__more');
        // that.moreList = that.more.find('.mmore__list');
        that.menuWidth = parseInt(that.menu.outerWidth());
        that.listWidth = parseInt(that.list.outerWidth());
        that.moreWidth = parseInt(that.more.outerWidth());
        that.width = that.listWidth + that.moreWidth;
        that.lastItem = that.cutItems[that.cutItems.length - 1];
        that.list.removeClass('nogrow');
    }

}

// Функция убирает прокрутку страницы
function setOverflow() {
    $('body').addClass('overflow');
}

// Функция возвращает прокрутку страницы
function removeOverflow() {
    $('body').removeClass('overflow');
}

function prettify(num) {
    var n = num.toString();
    return n.replace(/(\d{1,3}(?=(?:\d\d\d)+(?!\d)))/g, "$1" + ' ');
}

$(function () {

    // Мобильные табы
    $('.tabset__select-select').on('change', function (e) {
        e.preventDefault();
        console.log(this.value);
        let select = $(this),
            val = select.val(),
            tabset = select.closest('.tabset'),
            inputs = tabset.find('.tabset__input'),
            input = inputs.filter('[id = ' + val + ']');
        inputs.attr('checked', false);
        input.attr('checked', true);
        input.trigger('change');
    });

    // Scroll-top
    $('.scroll__btn').on('click', function (e) {
        e.preventDefault();

        $('html, body').animate({scrollTop: 0}, 400);
    });

});





//cabinet.js
$(function () {

    // VARIABLES
    const $accinput = $('.accpayments__input');


    // EVENTS
    $('.order__top').on('click', function (e) {
        let $this = $(this),
            $order = $this.closest('.order'),
            $bottom = $order.find('.order__bottom');

        $order.toggleClass('active');
        $bottom.slideToggle();

    });

    //ввод цифр в поля
    $('.accpayments__input').on('keypress', function (e) {
        if (e.key.match(/[^0-9]/g) || (this.value == 0 && e.key == 0)) {
            e.preventDefault();
        }
    });

    //Фиксированный платеж
    $('.accpayments__item').on('click', function (e) {
        e.preventDefault();
        let $this = $(this),
            val = parseInt($this.text());

        $accinput.val(val);
    });


    // FUNCTIONS


    //VENDORS

});
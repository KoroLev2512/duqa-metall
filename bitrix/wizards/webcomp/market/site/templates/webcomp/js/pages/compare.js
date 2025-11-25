//compare.js
$(function () {

    // VARIABLES

    // EVENTS

    // FUNCTIONS
    function setCcharHeight() {
        let cchars = $('.compare__item2').eq(0).find('.cchar');

        cchars.each(function (x, i) {
            let cchar = $(i),
                rowId = cchar.data('row'),
                row = $('.cchar[data-row=' + rowId + ']'),
                maxHeight = 0;

            row.each(function (indx, elem) {

                let height = $(elem).outerHeight();

                if (height > maxHeight) {
                    maxHeight = height;
                }
            });


            row.css('height', maxHeight + 'px');

        });
    }


    //VENDORS
    const compare = document.querySelector('.compare__slider');
    if (compare) {

        let compareSlider = new Swiper('.compare__container', {
            slidesPerView: 2,
            effect: 'slide',
            spaceBetween: -1,
            loop: true,
            watchOverflow: true,
            // If we need pagination
            pagination: {
                el: '.compare__pag .pag',
                clickable: true
            },
            // Navigation arrows
            navigation: {
                nextEl: '.compare__next',
                prevEl: '.compare__prev',
            },
            breakpoints: {
                // when window width is >= 769px
                768: {
                    slidesPerView: 3,
                },
                // when window width is >= 1200px
                1200: {
                    slidesPerView: 4,
                },
            },
            on: {
                init: function () {
                    initParams(this, compare);

                },
                imagesReady: function () {
                    setCcharHeight();
                }
            }
        });

        let compareSlider2 = new Swiper('.compare__container2', {
            slidesPerView: 4,
            effect: 'slide',
            spaceBetween: -1,
            loop: true,
            watchOverflow: true,
            breakpoints: {
                // when window width is >= 769px
                768: {
                    slidesPerView: 3,
                },
                // when window width is >= 1200px
                1200: {
                    slidesPerView: 4,
                },
            },
            on: {
                init: function () {
                    initParams(this, compare);
                },
            }
        });

        compareSlider.controller.control = compareSlider2;
        compareSlider2.controller.control = compareSlider;
    }

});
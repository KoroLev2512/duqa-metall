//index.js
$(function () {

    // VARIABLES

    // EVENTS

    // FUNCTIONS

    //VENDORS


    const brecom = document.querySelector('.brecom__slider');
    if (brecom) {
        let brecomSlider = new Swiper('.brecom__container', {
            slidesPerView: 2,
            effect: 'slide',
            spaceBetween: 0,
            loop: true,
            watchOverflow: true,
            // If we need pagination
            pagination: {
                el: '.brecom__pag .pag',
                clickable: true
            },
            // Navigation arrows
            navigation: {
                nextEl: '.brecom__next',
                prevEl: '.brecom__prev',
            },
            breakpoints: {
                // when window width is >= 769px
                768: {
                    spaceBetween: 20,
                    slidesPerView: 3,
                },
                // when window width is >= 1200px
                1200: {
                    spaceBetween: 20,
                    slidesPerView: 4,
                },
            },
            on: {
                init: function () {
                    initParams(this, brecom);
                },
            }
        });
    }

});
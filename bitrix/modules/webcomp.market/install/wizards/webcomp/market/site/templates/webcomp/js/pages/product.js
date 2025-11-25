//product.js
$(function () {

    // VARIABLES


    // EVENTS
    $('.rev__more').on('click', function (e) {
        e.preventDefault();
        let btn = $(this),
            rev = btn.closest('.rev'),
            hidden = rev.find('.rev__hidden');
        btn.toggleClass('open');
        rev.toggleClass('open');
        hidden.slideToggle(400);

    });

    // FUNCTIONS


    //VENDORS
    var galleryThumbs = new Swiper('.pthumbs', {
        spaceBetween: -1,
        slidesPerView: 3,
        watchSlidesVisibility: true,
        watchSlidesProgress: true,
        grabCursor: true,
        breakpoints: {
            // when window width is >= 769px
            768: {
                slidesPerView: 3,
            },
            // when window width is >= 992px
            992: {
                slidesPerView: 4,
            },
        },
    });

    var galleryTop = new Swiper('.pslider', {
        navigation: {
            nextEl: '.product__next',
            prevEl: '.product__prev',
        },
        thumbs: {
            swiper: galleryThumbs
        }
    });

    var galleryThumbs2 = new Swiper('.pthumbs2', {
        spaceBetween: 10,
        slidesPerView: 4,
        watchSlidesVisibility: true,
        watchSlidesProgress: true,
        grabCursor: true,
        breakpoints: {
            // when window width is >= 769px
            768: {
                slidesPerView: 6,
                spaceBetween: 15,

            },
            // when window width is >= 992px
            992: {
                slidesPerView: 6,
                spaceBetween: 20,
            },
        }
    });

    var galleryTop2 = new Swiper('.pgallery__container', {
        navigation: {
            nextEl: '.pgallery__next',
            prevEl: '.pgallery__prev',
        },
        thumbs: {
            swiper: galleryThumbs2
        }
    });

    const precom = document.querySelector('.precom__slider');
    if (precom) {
        let precomSlider = new Swiper('.precom__container', {
            slidesPerView: 2,
            effect: 'slide',
            spaceBetween: 0,
            // loop: true,
            watchOverflow: true,
            // If we need pagination
            pagination: {
                el: '.precom__pag .pag',
                clickable: true
            },
            // Navigation arrows
            navigation: {
                nextEl: '.precom__next',
                prevEl: '.precom__prev',
            },
            breakpoints: {
                // when window width is >= 769px
                768: {
                    spaceBetween: -1,
                    slidesPerView: 3,
                },
                // when window width is >= 1200px
                1200: {
                    spaceBetween: -1,
                    slidesPerView: 4,
                },
            },
            on: {
                init: function () {
                    initParams(this, precom);
                },
            }
        });
    }

});
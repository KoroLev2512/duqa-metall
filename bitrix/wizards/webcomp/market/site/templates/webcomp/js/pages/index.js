//index.js
$(function () {

    // VARIABLES

    // EVENTS

    // FUNCTIONS

    //VENDORS
    const ibanner = document.querySelector('.ibanner__slider');
    if (ibanner) {
        let ibannerSlider = new Swiper('.ibanner__container', {
            slidesPerView: 1,
            effect: 'slide',
            loop: true,
            watchOverflow: true,
            // Navigation arrows
            navigation: {
                nextEl: '.ibanner__next-btn',
                prevEl: '.ibanner__prev-btn',
            },
            // If we need pagination
            pagination: {
                el: '.ibanner__pag',
                clickable: true
            },

            on: {
                init: function () {
                    initParams(this, ibanner);
                    setTheme(this.slides[this.activeIndex], ibanner);
                },
                slideChange: function () {
                    setTheme(this.slides[this.activeIndex], ibanner);
                }
            },
        });
    }

    const iprojects = document.querySelector('.iprojects__slider');
    if (iprojects) {
        let iprojectsSlider = new Swiper('.iprojects__container', {
            slidesPerView: 1,
            effect: 'slide',
            spaceBetween: 20,
            loop: true,
            // If we need pagination
            pagination: {
                el: '.iprojects__pag .pag',
                clickable: true
            },
            breakpoints: {
                // when window width is >= 769px
                769: {
                    slidesPerView: 'auto',
                    spaceBetween: 0
                },
            },
            on: {
                init: function () {
                    initParams(this, iprojects);
                },

            }
        });
    }

    const irecom = $('.irecom__slider');
    if (irecom.length > 0) {
        let irecomSliders = [];

        irecom.each(function (x, i) {
            let slider = i;
            let index = slider.dataset.index;

            irecomSliders['iprojectsSlider' + index] = new Swiper('.irecom__container-' + index, {
                slidesPerView: 2,
                effect: 'slide',
                spaceBetween: -1,
                loop: true,
                watchOverflow: true,
                // Navigation arrows
                navigation: {
                    nextEl: '.irecom__next-' + index,
                    prevEl: '.irecom__prev-' + index,
                },
                // If we need pagination
                pagination: {
                    el: '.irecom__pag-' + index + ' .pag',
                    clickable: true
                },
                breakpoints: {
                    // when window width is >= 768px
                    769: {
                        slidesPerView: 3,
                    },
                    // when window width is >= 1201px
                    1200: {
                        slidesPerView: 4,
                    },
                },
                on: {
                    init: function () {
                        initParams(this, slider);
                    },
                }
            });

        });

    }

    const iactions = document.querySelector('.iactions__slider');
    if (iactions) {
        let iactionsSlider = new Swiper('.iactions__container', {
            slidesPerView: 2,
            effect: 'slide',
            spaceBetween: 10,
            loop: true,
            watchOverflow: true,
            // If we need pagination
            pagination: {
                el: '.iactions__pag .pag',
                clickable: true
            },
            // Navigation arrows
            navigation: {
                nextEl: '.iactions__next',
                prevEl: '.iactions__prev',
            },
            breakpoints: {
                // when window width is >= 769px
                769: {
                    spaceBetween: 20,
                    slidesPerView: 3,
                },
                // when window width is >= 850px
                850: {
                    spaceBetween: 70,
                    slidesPerView: 3,
                },
                // when window width is <= 1200px
                1200: {
                    slidesPerView: 4,
                },
            },
            on: {
                init: function () {
                    initParams(this, iactions);
                },
            }
        });
    }

    const irevs = document.querySelector('.irevs__slider');
    if (irevs) {
        let iprojectsSlider = new Swiper('.irevs__container', {
            slidesPerView: 1,
            effect: 'slide',
            spaceBetween: 20,
            loop: true,
            watchOverflow: true,
            // If we need pagination
            pagination: {
                el: '.irevs__pag .pag',
                clickable: true
            },
            // Navigation arrows
            navigation: {
                nextEl: '.irevs__next',
                prevEl: '.irevs__prev',
            },
            breakpoints: {
                // when window width is >= 769px
                769: {
                    slidesPerView: 2,
                    spaceBetween: 10,
                },
                // when window width is >= 850px
                850: {
                    slidesPerView: 3,
                },
            },
            on: {
                init: function () {
                    initParams(this, irevs);
                },
            }
        });
    }

    const ibrands = document.querySelector('.ibrands__slider');
    if (ibrands) {
        let iprojectsSlider = new Swiper('.ibrands__container', {
            slidesPerView: 2,
            effect: 'slide',
            spaceBetween: 10,
            loop: true,
            watchOverflow: true,
            // If we need pagination
            pagination: {
                el: '.ibrands__pag .pag',
                clickable: true
            },
            // Navigation arrows
            navigation: {
                nextEl: '.ibrands__next',
                prevEl: '.ibrands__prev',
            },
            breakpoints: {
                // when window width is >= 769px
                769: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                // when window width is >= 850px
                850: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
                // when window width is >= 1200px
                1200: {
                    slidesPerView: 5,
                    spaceBetween: 20,
                },
            },
            on: {
                init: function () {
                    initParams(this, ibrands);
                },
            }
        });
    }

    $('input[type=tel]').mask('+7 (999) 999-99-99');
});
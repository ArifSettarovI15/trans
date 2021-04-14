function initPriceSlider() {
    destroySlide($('.js-price-slider.slick-initialized'))
    $('.js-price-slider').each(function (i, elem) {
        var controls = $('.js-slider-controls[data-slider=' + $(elem).attr('data-slider') + ']')

        $(elem).slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            draggable: false,
            swipe: false,
            dots: false,
            arrows: true,
            infinite: false,
            prevArrow: controls.find('.slide-btn_prev'),
            nextArrow: controls.find('.slide-btn_next'),
            edgeFriction: 0.1,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 5,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 1023,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 478,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        })
    })
}
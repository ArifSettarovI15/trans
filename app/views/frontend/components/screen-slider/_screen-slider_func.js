function initSlider(item, baseCount) {
    item.each(function(i, elem) {
        var controls = $(elem).next('.js-slider-controls')
        slideLavaDots($(elem))()
        $(elem).slick({
            dots: true,
            arrows: false,
            appendDots: controls,
            prevArrow: '<div class="slide-btn slide-btn_prev"><div class="slide-btn__icon"><svg viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
                '<path fill-rule="evenodd" clip-rule="evenodd" d="M15.7071 5.30217C16.0976 5.69269 16.0976 6.32586 15.7071 6.71638L10.4142 12.0093L15.7071 17.3022C16.0976 17.6927 16.0976 18.3259 15.7071 18.7164C15.3166 19.1069 14.6834 19.1069 14.2929 18.7164L8.29289 12.7164C7.90237 12.3259 7.90237 11.6927 8.29289 11.3022L14.2929 5.30217C14.6834 4.91165 15.3166 4.91165 15.7071 5.30217Z" fill="#262425"/>\n' +
                '</svg></div></div>',
            nextArrow: '<div class="slide-btn slide-btn_next"><div class="slide-btn__icon"><svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
                '<path fill-rule="evenodd" clip-rule="evenodd" d="M11.293 7.29289C11.6835 6.90237 12.3167 6.90237 12.7072 7.29289L20.7072 15.2929C21.0978 15.6834 21.0978 16.3166 20.7072 16.7071L12.7072 24.7071C12.3167 25.0976 11.6835 25.0976 11.293 24.7071C10.9025 24.3166 10.9025 23.6834 11.293 23.2929L18.5859 16L11.293 8.70711C10.9025 8.31658 10.9025 7.68342 11.293 7.29289Z" fill="white"/>\n' +
                '</svg></div></div>',
            slidesToShow: baseCount,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: $(elem).attr('data-p-count') || 2,
                        appendArrows: controls,
                        arrows: true
                    }
                },
                {
                    breakpoint: 479,
                    settings: {
                        slidesToShow: 1,
                        arrows: true,
                        appendArrows: controls
                    }
                }
            ]
        })
    })
}
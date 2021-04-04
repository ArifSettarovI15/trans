function initHeroSlider() {
    var heroSlider = $('.js-hero');
    if (heroSlider.length > 0) {
        var controlsBlock = heroSlider.next('.js-slider-controls')
        slideLavaDots(heroSlider)()
        heroSlider.slick({
            dots: true,
            arrows: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            appendDots: controlsBlock,
            prevArrow: '<div class="slide-btn slide-btn_prev"><div class="slide-btn__icon"><svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
                '<path fill-rule="evenodd" clip-rule="evenodd" d="M20.7072 7.29289C21.0978 7.68342 21.0978 8.31658 20.7072 8.70711L13.4143 16L20.7072 23.2929C21.0978 23.6834 21.0978 24.3166 20.7072 24.7071C20.3167 25.0976 19.6835 25.0976 19.293 24.7071L11.293 16.7071C10.9025 16.3166 10.9025 15.6834 11.293 15.2929L19.293 7.29289C19.6835 6.90237 20.3167 6.90237 20.7072 7.29289Z" fill="white"/>\n' +
                '</svg></div></div>',
            nextArrow: '<div class="slide-btn slide-btn_next"><div class="slide-btn__icon"><svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
                '<path fill-rule="evenodd" clip-rule="evenodd" d="M11.293 7.29289C11.6835 6.90237 12.3167 6.90237 12.7072 7.29289L20.7072 15.2929C21.0978 15.6834 21.0978 16.3166 20.7072 16.7071L12.7072 24.7071C12.3167 25.0976 11.6835 25.0976 11.293 24.7071C10.9025 24.3166 10.9025 23.6834 11.293 23.2929L18.5859 16L11.293 8.70711C10.9025 8.31658 10.9025 7.68342 11.293 7.29289Z" fill="white"/>\n' +
                '</svg></div></div>',
            fade: true,
            responsive: [
                {
                    breakpoint: 767,
                    settings: {
                        appendArrows: controlsBlock
                    }
                }
            ]
        })
    }
}

initHeroSlider()



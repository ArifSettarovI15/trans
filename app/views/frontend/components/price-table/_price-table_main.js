$(document).ready(function () {
    initPriceSlider()
})

$('.price-table__item').hover(function() {
    var table = $('.price-table')
    var attr = $(this).attr('data-to-name')
    table.find('.price-table__item[data-to-name="' + attr +'"]').addClass('hover')
}, function() {
    $('.price-table__item').removeClass('hover')
})

window.initPriceSliderOnTab = function() {
    // destroySlide($('.js-price-slider.slick-initialized'))
    initPriceSlider()
}



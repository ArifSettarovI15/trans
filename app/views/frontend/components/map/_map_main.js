$(document).ready(function() {
    if ($('#map').length > 0) {
        ymaps.ready(initMap);
    }
})


$(document).on('click', '.js-slide-map', function() {
    var block = $('.js-map-block');
    var text = $(this).find('.link__text')
    var icon1 = $(this).find('.link__icon')
    var icon2 = $(this).find('.link__icon_2')
    block.toggleClass('map-slide')
    if (block.hasClass('map-slide')) {
        text.html('Свернуть карту')
        icon1.hide()
        icon2.show()
    } else {
        text.html('Показать маршрут')
        icon1.show()
        icon2.hide()
    }
    fitMapToViewport()
})
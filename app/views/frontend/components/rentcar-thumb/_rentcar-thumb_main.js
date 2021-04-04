function initThumbSlider() {
    $('.js-thumb-slider').each(function(i, elem) {
        if ($(elem).children().length > 1) {
            $(elem).slick({
                slidesToScroll: 1,
                slidesToShow: 1,
                arrows: false,
                dots: true,
                draggable: false,
                infinite: false
            })
        }
    })

}

initThumbSlider()

window.getCarId = function(instance, trigger) {
    var obj = $('#modal_driver').find('.form')
    obj.find('[name="car_id"]').val(trigger.attr('data-id'))
}
initTypeSlider()
initTypeSlider2()

window.initModalSlider = function() {
    console.log('init')
    initTypeSlider3()
}

window.destroySliderBefore = function () {
    destroySlide($('.js-type-slider.slick-initialized'))
}


$(document).on('click', '.js-show-trigger', function showComment() {
    $(this).css({
        opacity: 0,
        visibility: 'hidden'
    })
    $(this).off('click', showComment)
    $(this).next().slideDown(300, function() {
        fitMapToViewport()
    })
})

window.insertCallData = function() {
    var obj = $('#modal_call').find('.form')
    var orderObj = $('.order-form.form')
    obj.find('input[name="call_from"]').val(orderObj.find('[name="from"]').val())
    obj.find('input[name="call_to"]').val(orderObj.find('[name="to"]').val())
    obj.find('input[name="car_id"]').val(orderObj.find('[name="car_id"]').val())
    obj.find('input[name="car_price"]').val(orderObj.find('[name="price"]').val())
    obj.find('input[name="router-length"]').val(orderObj.find('[name="router-length"]').val())
    obj.find('input[name="router-time"]').val(orderObj.find('[name="router-time"]').val())
}

$('input[name="reverse"]').on('ifChecked', function(event) {
  $('.order-form__back-day').mod('active', true);
  $(this).closest('.order-form__bottom').find('.fc').hide();
})

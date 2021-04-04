function initRangeSlider() {

    $('.range').each(function(i, elem) {
        var rangeValueInput = $(elem).closest('.range-input').find('.range-value')
        noUiSlider.create(elem, {
            range: {
                'min': $(elem).data('min'),
                'max': $(elem).data('max')
            },
            connect: 'lower',
            start: $(elem).data('min'),
            step: 0,
            format: {
                to: function ( value ) {
                    return parseInt(value);
                },
                from: function ( value ) {
                    return parseInt(value);
                }
            },
            tooltips: true,
        })

        elem.noUiSlider.on('update', function(value, handle) {
            rangeValueInput.val(value)
            $(elem).find('.noUi-touch-area').html(value)
        })
    })
}

initRangeSlider()
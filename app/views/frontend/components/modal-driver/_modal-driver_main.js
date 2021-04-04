var totalSteps = $('.js-step').length
var driverStep = 1;
var prevBtn = $('.modal__button_prev')
var nextBtn = $('.modal__button_next')
var submitBtn = $('.modal__button_submit')

$(document).on('click', '.js-step-next', function () {
    var obj = $(this).closest('.form')
    var data_array = {};
    data_array['action'] = 'get_step_' + driverStep;
    $('.js-step[data-id="' + driverStep + '"]').find('.required').each(function(i, elem) {
        data_array[$(elem).attr('name')] = $(elem).val()
    })

    var options = {};
    options['current_step'] = driverStep
    options['increment'] = 1
    options['AfterDone'] = changeFormStep;
    SendAjaxRequest(
        {
            'url': obj.attr('data-url'),
            'data': data_array,
            'options': options,
        }
    );
})

$(document).on('click', '.js-step-prev', function() {
    driverStep--
    checkSteps()
})

$(document).on('click', '.form-steps__step_clicked', function() {
    driverStep = $(this).attr('data-id')
    checkSteps()
})

function changeFormStep(response, ajax_config, textStatus, jqXHR) {
    if (response.status) {
        if (driverStep < totalSteps) {
            driverStep++
        }
        var current = ajax_config.options.current_step
        checkSteps()
        nextBtn.attr('data-step', current)
        prevBtn.attr('data-step', current - 1)
    }
}

function checkSteps() {
    $('.js-step').mod('active', false)
    $('.js-step[data-id="' + driverStep + '"]').mod('active', true)

    $('.form-steps__step').each(function(i, elem) {
        $(elem).removeClass('active')
        if ($(elem).attr('data-id') < driverStep) {
            $(elem).mod('clicked', true)
        } else {
            $(elem).mod('clicked', false)
        }
    })
    $('.form-steps__step[data-id="' + driverStep + '"]').addClass('active')
    prevBtn.show()
    if (driverStep > 0) {
        if (driverStep < totalSteps) {
            submitBtn.hide()
            nextBtn.show()
        }
        if (driverStep === 1) {
            prevBtn.hide()
        }
        if (driverStep === 4) {
            nextBtn.hide()
            submitBtn.show()
        }
    }
}

// Add parent fields
function addDriverForm() {
    var formCounter = 1
    return function () {
        $(document).on('click', '.js-add-form', function addNewFields() {
            formCounter++
            var html = '<div class="form-block__block">\n' +
                '        <div class="form-block__title">Родственник ' + formCounter + '</div>\n' +
                '        <div class="form-block__form">\n' +
                '          <div class="form-block__elem form-block__elem-full">\n' +
                '                    <label class="field ">\n' +
                '                    <input type="text" name="parent' + formCounter + '_name" class="field__input element   " placeholder=" " autocomplete="off">\n' +
                '                <div class="field__bar"></div>\n' +
                '                <span class="field__label">ФИО</span>                    </label>\n' +
                '\n' +
                '          </div>\n' +
                '          <div class="form-block__elem">\n' +
                '                    <label class="field ">\n' +
                '                    <input type="text" name="parent' + formCounter + '_parants" class="field__input element   " placeholder=" " autocomplete="off">\n' +
                '                <div class="field__bar"></div>\n' +
                '                <span class="field__label">Родство</span>                    </label>\n' +
                '\n' +
                '          </div>\n' +
                '          <div class="form-block__elem">\n' +
                '                    <label class="field ">\n' +
                '                    <input type="tel" name="parent' + formCounter + '_phone" class="field__input element   " placeholder=" " autocomplete="off">\n' +
                '                <div class="field__bar"></div>\n' +
                '                <span class="field__label">Телефон</span>                    </label>\n' +
                '\n' +
                '          </div>\n' +
                '        </div>\n' +
                '      </div>'

            $('.js-block-parent').append(html)


            if (formCounter === 4) {
                $('.js-add-form').remove()
                $(document).off('click', '.js-add-form', addNewFields)
            }
        })
    }
}

addDriverForm()()


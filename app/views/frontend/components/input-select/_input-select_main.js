$(document).on('click', '.field_select', function (e) {
    if ($(e.target).hasClass('serviceSelect__item')
        || $(e.target).closest('.serviceSelect__item').length > 0
        || $(e.target).hasClass('passagersSelect__item')
        || $(e.target).closest('.passagersSelect__item').length > 0
      || $(e.target).is('.passagersSelect__close')
      || $(e.target).is('.serviceSelect__close')
    ) {

        return false
    } else {
        if ($(e.target).hasClass('field_open') || $(e.target).closest('.field_select').hasClass('field_open')) {
            $('.field_select').removeClass('field_open');
        } else {
            $('.field_select').removeClass('field_open');
            if ($(this).closest('.field_select').hasClass('open') && $('.passagersSelect_open').length === 0 && $('.serviceSelect_open').length === 0) {
                $(this).closest('.field_select').addClass('open')
            } else if ($('.passagersSelect__submit').is(e.target)) {
                $(this).closest('.field').mod('open', false);
            } else {
                showOpenLinks($(this).closest('.field_select').find('.field__input'))
            }
        }
    }
})




$(document).on('click', '.passagersSelect__item', function (e) {
    var counter = $(this).find('.counter-val')
    var counterVal = counter.val() * 1
    var counterMinus = $(this).find('.counter-minus')
    var counterPlus = $(this).find('.counter-plus')
    if (counterPlus.is(e.target) || counterPlus.has(e.target).length > 0) {
        counterMinus.removeClass('disabled')
        counterVal++
        $(this).mod('active', true)
    }

    if (counterMinus.is(e.target) || counterMinus.has(e.target).length > 0) {
        if (counterVal > 0) {{
            counterVal--
            if (counterVal === 0) {
                $(this).mod('active', false)
                counterMinus.addClass('disabled')
            }
        }}
    }

    counter.val(counterVal)
    $(this).attr('data-value', counterVal)

    passegersFormTotal($(this))
})

$(document).on('click', '.serviceSelect__item', function (e) {
    if ($(this).hasMod('active')) {
        $(this).mod('active', false);
    } else {
        $(this).mod('active', true);
    }
    orderFormTotal($(this));
});

$(document).on('click', function (e) {
    if ($('.field_open').length > 0) {
        var container = $('.field')
        if ((!container.is(e.target) && container.has(e.target).length === 0) && $('.serviceSelect').has(e.target).length === 0 && !$('.passagersSelect').is(e.target)) {
            $('.field').mod('open', false);
        }
    }
})

$(document).on('click', '.serviceSelect__close, .passagersSelect__close', function (e) {
  $('.field').mod('open', false);
  $('.serviceSelect').mod('open', false);
  $('.passagersSelect').mod('open', false);
})

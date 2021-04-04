function showOpenLinks(obj) {
    var $field = obj.closest('.field')
    if ($field.elem('hidden').length > 0) {
        if ($field.elem('hidden').val() !== '') {
            $field.mod('open', false);
        } else {
            $field.mod('open', true)
        }
    } else {
        $field.mod('open', true);
        obj.siblings('.serviceSelect').mod('open', true);
        obj.siblings('.passagersSelect').mod('open', true);
        initScroll($field.find('.serviceSelect__data'));
        initScroll($field.find('.passagersSelect__data'));
    }

}

function orderFormTotal(obj) {
    var dop = 0;
    var dops = '';
    var i = 0;
    $('.serviceSelect__item_active').each(function(index) {
        if ($(this).attr('data-x') != '') {
            dop = dop + $(this).attr('data-x');
        } else {
            dop = dop + $(this).attr('data-price') * 1;
        }
        if (i > 0) {
            dops = dops + ', ';
        }
        dops = dops + $(this).attr('data-title');
        i++;
    });
    obj.closest('.field').find('.field__input').html(dops);
    obj.closest('.field').find('.input-vv').val(dops);

    if (dops) {
        obj.closest('.field').mod('ok',true);
    }
    else {
        obj.closest('.field').mod('ok',false);
    }

  var dop_price=0;
  $('.serviceSelect__item_active .serviceSelect__price').each(function (i, elem) {
    dop_price=dop_price+$(this).attr('data-price')*1;
  })

  $('.order-type__slide ').each(function (i, elem) {
        var valElem =  $(elem).find('.type-price')
        var val = valElem.attr('data-value')*1+dop_price
        valElem.html(val)
        $(elem).find('[name="price"]').val(val);

  })
}

function passegersFormTotal(obj) {
    var dops = '';
    var i = 0;
    $('.passagersSelect__item_active').each(function(index) {
        if (i > 0) {
            dops = dops + ', ';
        }
        dops = dops + $(this).attr('data-title') + ': ' + $(this).attr('data-value');
        i++;
    });
    if (dops) {
        obj.closest('.field').mod('ok',true);
    }
    else {
        obj.closest('.field').mod('ok',false);
    }
    obj.closest('.field').find('.field__input').html(dops);
    obj.closest('.field').find('.input-vv').val(dops);
}

function initScroll(obj) {
    obj.animate({ scrollTop: 0 }, "fast");
    obj.perfectScrollbar({
        suppressScrollX: true,
    });
    setTimeout(function() {
        $('.ps-theme-default').perfectScrollbar('update');
    }, 100);
}

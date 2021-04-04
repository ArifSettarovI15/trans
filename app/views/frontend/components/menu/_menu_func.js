function menuClose() {
   $('html,body').removeClass('menu-open mobile')
}

function fixHeader() {
    var wrapper = $('.header')

    if (wrapper.hasClass('user-login') && $(window).width() < 768) {
        return
    }

    if ($(window).scrollTop() < 94 && wrapper.hasMod('fixed')) {
        wrapper.mod('fixed', false)
    } else if ($(window).scrollTop() > 94 && !wrapper.hasMod('fixed')) {
        wrapper.mod('fixed', true)
    }

    hiddenScroll(wrapper)
}

function hiddenScroll(elem, options) {
    var prev_position = $(window).scrollTop(),
      defaults = {
          'visible_position': 94,
          'className': 'hidden'
      },
      settings = $.extend(defaults, options);

    function _inner() {
        var page_position = $(window).scrollTop();

        if ((page_position > settings.visible_position) && (prev_position < page_position)) {
            elem.addClass(settings.className);
        } else {
            elem.removeClass(settings.className);
        }

        prev_position = $(window).scrollTop();
    }

    _inner();

    $(window).on('scroll', function() {
        _inner();
    });
}
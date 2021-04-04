$(window).scroll(function () {
    fixHeader()
});

$(document).ready(function () {
    fixHeader()
})

$(document).on('click', '.js-burger', function(e) {
    if ($(this).attr('data-menu') === 'cabinet') {
        $('html,body').toggleClass('menu-open mobile')
    } else {
        $('html,body').toggleClass('menu-open')
    }
})

$(document).on('click', function(e) {
    var a = $('.header')
    var b = $('.menu')
    var c = $('.js-burger')

    a.is(e.target) || a.has(e.target).length !== 0 || b.is(e.target) || b.has(e.target).length !== 0 || c.is(e.target) || c.has(e.target).length !== 0 || menuClose();
})
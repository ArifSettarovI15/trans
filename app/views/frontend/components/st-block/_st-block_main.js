$(document).on('click', '.js-slide-text', function (e) {
    e.preventDefault();
    var root = $(this).closest('.text-container')
    var el = root.find('.js-overflow'),
        curHeight = el.height(),
        autoHeight = el.css('height', 'auto').height()
    var linkText = $(this).find('.js-overflow-link-text')

    if (el.hasMod('show')) {
        el.animate({
            height: $(this).attr('data-height') || 179
        }, {
            complete: function () {
                $(this).mod('show', false);
                $('html, body').animate({scrollTop: root.offset().top})
                linkText.html('Читать целиком')
            }
        });
    } else {
        el.height(curHeight).animate({
            height: autoHeight,
        }, {
            complete: function () {
                $(this).mod('show', true);
                $('html, body').animate({scrollTop: root.offset().top})
                linkText.html('Свернуть')
            }
        });
    }
});
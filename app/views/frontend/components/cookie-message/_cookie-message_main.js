$(document).ready(function() {
    if (!Cookies.get('mes_cookie')) {
        $('[data-mess-id="cookie"]').animate({height: 'show'}, 500)

        $(document).on('click', '.js-line-mess', function setCookieMessHandler() {
            $(this).closest('[data-mess-id]').fadeOut();
            Cookies.set('mes_cookie', '1', {expires: 7})

            $(document).off('click', setCookieMessHandler)
        })
    }
})
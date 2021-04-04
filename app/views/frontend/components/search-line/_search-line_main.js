$(document).on('keyup change click', '.js-price-search', function () {
    var self = $(this)
    $('.price-table').find('.price-table__item').each(function (index, elem) {
        if ($(elem).attr('data-to-name').toLowerCase().indexOf(self.val().toLowerCase()) > -1) {
            $(this).mod('hidden', false)
        } else {
            $(this).mod('hidden', true)
        }
    })

    debounce(initPriceSlider, 1000);
})

function debounce(f, t) {
    return function (args) {
        var previousCall = this.lastCall;
        this.lastCall = Date.now();
        if (previousCall && ((this.lastCall - previousCall) <= t)) {
            clearTimeout(this.lastCallTimer);
        }
        this.lastCallTimer = setTimeout(function () {
            f(args)
        }, t);
    }
}
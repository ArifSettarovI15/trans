$(document).on('click', '.js-acc-2', function(e) {
    e.preventDefault();
    var siblings = $('.js-acc2-content')
    var siblingsParent = siblings.parent()
    var obj=$(this).parent();
    var objContent = obj.find('.js-acc2-content');
    // siblings.slideUp(300, function () {
    //     siblingsParent.mod('open', false);
    // });
    if (obj.hasMod('open')) {
        objContent.slideUp(300, function () {
            obj.mod('open', false);
        });
    } else {
        obj.mod('open', true);
        objContent.slideDown(300);
    }

});
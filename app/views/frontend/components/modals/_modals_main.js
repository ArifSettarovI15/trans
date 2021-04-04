$('input[name="rr_city"]').on('ifChecked', function(event){

});


$( document ).on( "click", ".submit-city", function(e) {
  Cookies.set('cc_city_id', $('[name="rr_city"]:checked').val(), { expires: 365 });
  $('.current-city').attr('data-id',$('[name="rr_city"]:checked').val()).html($('[name="rr_city"]:checked').attr('data-name'));
  CloseModals();
});

$( document ).on( "click", ".cancel-city", function(e) {
  $('[name="rr_city"][value="'+$('.current-city').attr('data-id')+'"]').iCheck('check');
});


$( document ).on( "click", ".close-modal", function(e) {
  CloseModals();
});

$( document ).on( "click", ".js-input-clean", function() {
  $( ".modal-search__item" ).show();
});
$( document ).on( "keyup change", ".filter-cities", function() {
  var obj=$(this);

  $( ".modal-search__item" ).each(function( index ) {
    var val=$(this).find('[name="rr_city"]').attr('data-name').toLowerCase();
    if (InlineSearch(val,obj.val().toLowerCase()) ) {
      $(this).show();
    }
    else {
      $(this).hide();
    }
  });
});


$(document).on('click', '.open-tariff', function(e) {
  e.preventDefault();
  $('.modal-tariff__id').val($(this).attr('data-id'));
  $('.modal-tariff__title').html($(this).attr('data-title'));
  $('.modal-tariff__provider').html($(this).attr('data-provider'));
  $('.modal-tariff__price').html($(this).attr('data-price'));
  $('.modal-tariff__connect').html($(this).attr('data-connect'));


  if ($(this).attr('data-channels-a')>0 || $(this).attr('data-channels-hd')>0) {
    var tv='';
    if ($(this).attr('data-channels-a')>0) {
      tv=$(this).attr('data-channels-a');
    }
    if ($(this).attr('data-channels-hd')>0) {
      tv=tv+'+'+$(this).attr('data-channels-hd')+'HD';
    }
    $('.modal-tariff__tv').html(tv);
  }
  else {
    $('.modal-tariff__tv').html('-');
  }
  $('.modal-tariff__speed').html($(this).attr('data-speed'));
  openInlineModal('#modal_tarif', $(this));
});


$(document).on('click', '.open-provider-order', function(e) {
  $('.order-pp-id').val($(this).attr('data-id'));

  openInlineModal('#modal_app', $(this));
});

$(document).on('click', '.check-provider', function(e) {
  $('.check-provider-id').val($(this).attr('data-id'));

  openInlineModal('#modal_check', $(this));
});

$(document).on('click', '.show-comment', function(e) {

  $('.full-comment').html($('[data-id="'+$(this).attr('data-id')+'"].feed-thumb__text').html());

  openInlineModal('#modal_comment', $(this));
});

if ($('.js-calendar-trigger').length > 0) {
    var datepicker = $('#event_calendar').datepicker({
      minutesStep: 5,

        minDate: new Date(),
        navTitles: {
            days: 'MM yyyy<div class="event-calendar__title-icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
                '<path fill-rule="evenodd" clip-rule="evenodd" d="M4.29289 6.79289C4.68342 6.40237 5.31658 6.40237 5.70711 6.79289L10 11.0858L14.2929 6.79289C14.6834 6.40237 15.3166 6.40237 15.7071 6.79289C16.0976 7.18342 16.0976 7.81658 15.7071 8.20711L10.7071 13.2071C10.3166 13.5976 9.68342 13.5976 9.29289 13.2071L4.29289 8.20711C3.90237 7.81658 3.90237 7.18342 4.29289 6.79289Z" fill="#9E9E9E"/>\n' +
                '</svg>\n</div>'
        },
        prevHtml: '<div class="event-calendar__arrow"><svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
            '<path fill-rule="evenodd" clip-rule="evenodd" d="M10.7071 4.87361C11.0976 4.48309 11.0976 3.84992 10.7071 3.4594C10.3165 3.06887 9.68338 3.06887 9.29285 3.4594L3.45952 9.29273C3.06899 9.68326 3.06899 10.3164 3.45952 10.7069L9.29285 16.5403C9.68338 16.9308 10.3165 16.9308 10.7071 16.5403C11.0976 16.1498 11.0976 15.5166 10.7071 15.1261L6.58084 10.9998H15.8333C16.3856 10.9998 16.8333 10.5521 16.8333 9.99984C16.8333 9.44755 16.3856 8.99984 15.8333 8.99984H6.58084L10.7071 4.87361Z" fill="#9E9E9E"/>\n' +
            '</svg></div>\n',
        nextHtml: '<div class="event-calendar__arrow"><svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
            '<path fill-rule="evenodd" clip-rule="evenodd" d="M10.7071 3.4594C10.3166 3.06887 9.68344 3.06887 9.29291 3.4594C8.90239 3.84992 8.90239 4.48309 9.29291 4.87361L13.4191 8.99984H4.16669C3.6144 8.99984 3.16669 9.44755 3.16669 9.99984C3.16669 10.5521 3.6144 10.9998 4.16669 10.9998H13.4191L9.29291 15.1261C8.90239 15.5166 8.90239 16.1498 9.29291 16.5403C9.68344 16.9308 10.3166 16.9308 10.7071 16.5403L16.5405 10.7069C16.931 10.3164 16.931 9.68326 16.5405 9.29273L10.7071 3.4594Z" fill="#9E9E9E"/>\n' +
            '</svg></div>\n',
        onSelect: function (fd, date) {
            $('#modal_calendar .cell-content').find('.place-time').attr('data-date', fd)
            $('#modal_calendar .event-calendar__side-elem').removeClass('hidden')
            var time = new Date()
            var currentDate = time.getDate() + '.' + (time.getMonth() + 1) + '.' + time.getFullYear()
            $('#modal_calendar .place-time[data-date="' + currentDate + '"]').each(function(i, elem) {
                var arr = $(elem).attr('data-time').split(':')
                if (arr[0] <= time.getHours()) {
                    $(elem).parent().addClass('hidden')
                }
            })
            initScroll($('#modal_calendar .cell-content'))
        }
    }).data('datepicker')
    datepicker.selectDate(new Date())

  var datepicker2 = $('#event_calendar2').datepicker({
    minutesStep: 5,

    minDate: new Date(),
    navTitles: {
      days: 'MM yyyy<div class="event-calendar__title-icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
      '<path fill-rule="evenodd" clip-rule="evenodd" d="M4.29289 6.79289C4.68342 6.40237 5.31658 6.40237 5.70711 6.79289L10 11.0858L14.2929 6.79289C14.6834 6.40237 15.3166 6.40237 15.7071 6.79289C16.0976 7.18342 16.0976 7.81658 15.7071 8.20711L10.7071 13.2071C10.3166 13.5976 9.68342 13.5976 9.29289 13.2071L4.29289 8.20711C3.90237 7.81658 3.90237 7.18342 4.29289 6.79289Z" fill="#9E9E9E"/>\n' +
      '</svg>\n</div>'
    },
    prevHtml: '<div class="event-calendar__arrow"><svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
    '<path fill-rule="evenodd" clip-rule="evenodd" d="M10.7071 4.87361C11.0976 4.48309 11.0976 3.84992 10.7071 3.4594C10.3165 3.06887 9.68338 3.06887 9.29285 3.4594L3.45952 9.29273C3.06899 9.68326 3.06899 10.3164 3.45952 10.7069L9.29285 16.5403C9.68338 16.9308 10.3165 16.9308 10.7071 16.5403C11.0976 16.1498 11.0976 15.5166 10.7071 15.1261L6.58084 10.9998H15.8333C16.3856 10.9998 16.8333 10.5521 16.8333 9.99984C16.8333 9.44755 16.3856 8.99984 15.8333 8.99984H6.58084L10.7071 4.87361Z" fill="#9E9E9E"/>\n' +
    '</svg></div>\n',
    nextHtml: '<div class="event-calendar__arrow"><svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
    '<path fill-rule="evenodd" clip-rule="evenodd" d="M10.7071 3.4594C10.3166 3.06887 9.68344 3.06887 9.29291 3.4594C8.90239 3.84992 8.90239 4.48309 9.29291 4.87361L13.4191 8.99984H4.16669C3.6144 8.99984 3.16669 9.44755 3.16669 9.99984C3.16669 10.5521 3.6144 10.9998 4.16669 10.9998H13.4191L9.29291 15.1261C8.90239 15.5166 8.90239 16.1498 9.29291 16.5403C9.68344 16.9308 10.3166 16.9308 10.7071 16.5403L16.5405 10.7069C16.931 10.3164 16.931 9.68326 16.5405 9.29273L10.7071 3.4594Z" fill="#9E9E9E"/>\n' +
    '</svg></div>\n',
    onSelect: function (fd, date) {
      $('#modal_calendar2 .cell-content').find('.place-time').attr('data-date', fd)
      $('#modal_calendar2 .event-calendar__side-elem').removeClass('hidden')
      var time = new Date()
      var currentDate = time.getDate() + '.' + (time.getMonth() + 1) + '.' + time.getFullYear()
      $('#modal_calendar .place-time[data-date="' + currentDate + '"]').each(function(i, elem) {
        var arr = $(elem).attr('data-time').split(':')
        if (arr[0] <= time.getHours()) {
          $(elem).parent().addClass('hidden')
        }
      })
      initScroll($('#modal_calendar2 .cell-content'))
    }
  }).data('datepicker')

}
$(document).on('click change', '.js-calendar-trigger2', function (e) {
  e.preventDefault()
  var modal = $(this).closest('.modal')
  if (modal) {
    $('#modal_calendar2').attr('data-modal', modal.attr('id'))
  }
  openInlineModal('#modal_calendar2')
  initScroll($('.cell-content'))
})

$(document).on('click change', '.js-calendar-trigger', function (e) {
    e.preventDefault()
    var modal = $(this).closest('.modal')
    if (modal) {
        $('#modal_calendar').attr('data-modal', modal.attr('id'))
    }
    openInlineModal('#modal_calendar')
    initScroll($('.cell-content'))
})

$(document).on('click', '.place-time', function () {
  var dop='';
  if ($(this).attr('data-block')=='back') {
    dop=2;
  }
    var dateArr = $(this).attr('data-date').split('.')
    var result = dateArr[2] + '-' + dateArr[1] + '-' + dateArr[0] + ' ' + $(this).attr('data-time')
    $('[data-block="'+$(this).attr('data-block')+'"].place-time.button_active').removeClass('button_active')
    $(this).addClass('button_active')
    $('[data-name="'+$(this).attr('data-block')+'"].js-calendar-trigger'+dop).val(result).addClass('focused')
    if ($(this).closest('.modal').attr('data-modal')) {
        openInlineModal('#' + $(this).closest('.modal').attr('data-modal'))
    } else {
        CloseModals()
    }
})

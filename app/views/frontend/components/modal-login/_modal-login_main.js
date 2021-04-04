$(document).on('click', '.submit-code', function() {
    var obj = $(this).closest('.form')

    var data_array = {};
    data_array['action'] = 'process_confirm';
    data_array['phone'] = obj.find('[name="phone"]').val()

    var options = {};

    SendAjaxRequest(
        {
            'url': home_url + '/login/',
            'data': data_array,
            'options': options,
        }
    );
})
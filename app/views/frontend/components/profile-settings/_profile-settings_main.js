$(document).on('click', '.reset-avatar', function() {
    var data_array = {};
    data_array['action'] = 'del_ava';

    var options = {};
    options['AfterDone'] = deleteAvatarDone;
    SendAjaxRequest(
        {
            'url': home_url + '/cabinet/profile/',
            'data': data_array,
            'options': options,
        }
    );
})

function deleteAvatarDone(response, ajax_config, textStatus, jqXHR) {
    if (response.status) {
        ShowMessage('Аватарка успешно удалена')
    }
}

$(document).on('click', '.js-input-active', function() {
    var obj = $(this).closest('.form')
    obj.find('input[disabled]').prop('disabled', false)
    $(this).removeClass('show').addClass('hide')
    obj.find('.js-input-save').removeClass('hide').addClass('show')
})
function DelFileDone(response,ajax_config,textStatus,jqXHR) {
    if (response.status) {
        $('.upload-image__result').fadeOut()
        $('.upload-image__result-inner').removeAttr('data-uid')
        $('.upload-image__result-container').html('')
    }
}

function changeImageDone(response, ajax_config, textStatus, jqXHR) {
    console.log(response)
    if (response.status) {
        console.log(ajax_config.options);
        var html = $('<img src="' + response.filepath + '" alt="">');
        $('.settings-card__ava').html(html)
    }
}

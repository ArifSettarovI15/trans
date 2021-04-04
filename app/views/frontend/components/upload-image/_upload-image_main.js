$('.js-upload').fileupload({
    timeout: 3000000,
    sequentialUploads:true,
    dataType: 'json',
    submit: function (e, data) {
        $('.upload-image').find('.loader').show()
    },
    progressall: function (e, data) {

    },
    done: function (e, data) {
        var self = $(this)
        var image = new Image()
        var preview = self.closest('.upload-image').find('.upload-image__result')
        $('.upload-image').find('.loader').hide()
        if (data.result.error) {
            alert(data.result.error);
        }
        else{
            if (preview) {
                if (data.files && data.files[0]) {
                    var reader = new FileReader()
                    reader.onload = function(e) {
                        image.src = e.target.result
                        preview.find('.upload-image__result-container').html(image)
                        preview.fadeIn()
                    }

                    reader.readAsDataURL(data.files[0])
                }
            }
        }

        $('.upload-image__result-inner').attr('data-name', data.result.name)
        console.log(data.result)

        if ($(this).attr('data-form')) {
            var data_array = {}
            data_array['action'] = 'process_userImage';
            data_array['filename'] = data.result.name
            data_array['filepath'] = data.result.image
            var options = {};
            options['AfterDone'] = changeImageDone;

            SendAjaxRequest(
                {
                    'url': home_url + '/cabinet/profile/',
                    'data': data_array,
                    'options': options
                }
            );
        }
    }
});

$( document ).on( "click", ".js-del-img", function(e) {
    var data={};
    data['action']='delete_file';
    data['order_uid']=$(this).attr('data-name');

    var options={};
    options['AfterDone'] = DelFileDone;
    SendAjaxRequest(
        {
            'url':$(this).closest('.upload-image').find('.upload-image__upload').attr('data-url'),
            'data':data,
            'options':options
        }
    );
});

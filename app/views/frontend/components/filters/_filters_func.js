function filterFeeds(e) {
    var data_array = {}
    data_array['action'] = 'get_sorted'
    data_array['id'] = e.params.data.id

    var options = {}
    options['AfterDone'] = filterDone;
    options['action'] = 'filter_review'
    options['list'] = 'feed'
    SendAjaxRequest(
        {
            'url': home_url + '/reviews/',
            'data': data_array,
            'options': options,
        }
    );
}

function filterOrders(e) {
    var data_array = {}
    data_array['action'] = 'get_sorted'
    data_array['id'] = e.params.data.id

    var options = {}
    options['AfterDone'] = filterDone;
    options['action'] = 'filter_orders'
    options['list'] = 'order'
    SendAjaxRequest(
        {
            'url': home_url + '/trips/',
            'data': data_array,
            'options': options,
        }
    );
}

function filterDone(response,ajax_config,textStatus,jqXHR) {
    if (response.status) {
        $('.table-content[data-list="' + ajax_config.options.list + '"').html(response.html)
        grunticon.embedIcons(grunticon.getIcons(grunticon.href));
    }
}




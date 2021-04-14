var myMap

function initMap() {
    // if (myMap) {
    //     myMap.destroy();
    // }
    myMap = new ymaps.Map('map', {
        center: [45.018453971093145, 34.123042216305585],
        zoom: 8,
        controls: [],
        autoFitToViewport: 'always',
    })

    myMap.behaviors.disable('multiTouch');
    myMap.behaviors.disable('drag');

    initMapRoute();
}

function initMapWithRouter(coords) {
    var c1 = coords[0].split(',')
    var c2 = coords[1].split(',')
    // myMap.destroy();
    var multiRoute = new ymaps.multiRouter.MultiRoute({
        referencePoints: [
            c1,
            c2
        ],
        params: {
            results: 1
        }
    }, {
        boundsAutoApply: true
    });

    myMap = new ymaps.Map('map', {
        center: [45.18131861327712, 34.17462399999997],
        zoom: 9,
        controls: []
    });

    myMap.behaviors.disable('multiTouch');
    myMap.behaviors.disable('drag');

    multiRoute.model.events.add("requestsuccess", function () {
        var a = multiRoute.model.getRoutes();

        fitMapToViewport();

        if ($('[name="from"]').val() && $('[name="to"]').val()) {
            var data_array = {};
            data_array['action'] = 'get_prices';
            data_array['from_id'] = $('[name="from"]').val();
            data_array['to_id'] = $('[name="to"]').val();
            data_array['distance'] = a[0].properties._data.distance.value;
            data_array['distance_km'] = a[0].properties._data.distance.text;
            data_array['duration'] = a[0].properties._data.duration.text;
            data_array['seconds']= a[0].properties._data.duration.value;

            var options = {};
            options['AfterDone'] = mapRouteDone;
            SendAjaxRequest(
                {
                    'url': home_url + '/prices/',
                    'data': data_array,
                    'options': options,
                }
            );

        }
    });
    myMap.geoObjects.add(multiRoute);
}

function mapRouteDone(response, ajax_config, textStatus, jqXHR) {
    if (response.status) {
        var dop_price=0;
      $('.serviceSelect__item_active .serviceSelect__price').each(function (i, elem) {
        dop_price=dop_price+$(this).attr('data-price')*1;
      })

        $('.order-type__slide').each(function (i, elem) {
            var priceEl = $(elem).find('.type-price')
            var id = $(elem).attr('data-type-id')
            var radioBtn =  $(elem).find('input[type="radio"]')
            radioBtn.prop('disabled', false)
            radioBtn.val(response.type_car[id])
            radioBtn.on('change', function() {
                console.log("12321")
                $('input[name="car_id"]').val(id);
                $(this).closest('.order-type__slide').find('[name="price"]').prop('checked',false);
                $(this).closest('.order-type__slide').find('[id="car_type_'+id+'"][name="price"]').prop('checked',true);
            })
            priceEl.html(response.type_car[id]*1+dop_price).attr('data-value',response.type_car[id]*1);
            priceEl.closest('.order-type__slide-price').fadeIn();
        })

        $('.router-length').html(ajax_config.data.distance_km)
        $('.router-time').html(ajax_config.data.duration)
        $('input[name="router-length"]').val(ajax_config.data.distance_km);
        $('input[name="router-time"]').val(ajax_config.data.seconds);
        $('.router-features').slideDown()
        $('.order-form .button').removeClass('disabled')
        fitMapToViewport()
    }
}

function resetClassesDone(response,ajax_config,textStatus,jqXHR) {
    if (response.status){
        $('.order-type__slide').each(function (i, elem) {
            var priceEl = $(elem).find('.type-price')
            var radioBtn =  $(elem).find('input[type="radio"]')
            radioBtn.prop('disabled', true)
            radioBtn.val('')
            radioBtn.off('change')
            priceEl.html('')
            priceEl.closest('.order-type__slide-price').fadeOut()
        })
    }
}


function initMapRoute() {
    var routerArr = [];
    if ($('[name="from"]').attr('data-coord')) {
        routerArr.push($('[name="from"]').attr('data-coord'));
    }
    if ($('[name="to"]').attr('data-coord')) {
        routerArr.push($('[name="to"]').attr('data-coord'));
    }
    if (routerArr.length === 2) {
        ymaps.ready(initMapWithRouter(routerArr))
    }

}

function fitMapToViewport() {
    myMap.container.fitToViewport();
}

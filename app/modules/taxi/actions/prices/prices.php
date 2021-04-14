<?php
$Main->user->PagePrivacy();

$from_city_url = 'aeroport-simferopol';
if ($Main->GPC["content_type"]){
    $from_city_url = $Main->GPC["content_type"];
}
$city = $Taxi->cities->GetItemByUrl($Main->GPC["content_type"]);
$has=$city;

if ($Main->GPC["content_type"] == 'hotels' OR $Main->GPC["content_type"]=="places")
{
    $has =1;
}
if (!$has ){
    $Main->error->PageNotFound();
}

$page_name = 'Тарифы';

$breadcrumbs = array();
$breadcrumbs[] = array(
    'title' => $page_name
);

$desc='Цены на такси из "'.$city['city_title'].'" по всему Крыму. Быстрая подача, автомобили с кондиционером и Wi-Fi.';


$Main->template->SetPageAttributes(
    array(
        'title' => 'Такси '.$city['city_title'].': цены 2021 года',
        'keywords' => $Main->GPC["content_type"],
        'desc' => $desc
    ),
    array(
        'breadcrumbs' => $breadcrumbs,
        'title' => $page_name
    )
);

if ($Main->GPC['action'] == 'reset_prices') {
    $array['status'] = true;
    $classes = $Main->template->global_vars['classes'];
    foreach ($classes as $i => $b) {
        $classes[$i]['price_value'] = 0;
    }
    $array['html'] = $Main->template->Render('frontend/components/typecar-block/list1.twig', array(
        'classes' => $classes,
        'form' => true
    ));
    $Main->template->DisplayJson($array);
    exit;
}
if ($Main->GPC['action'] == 'get_prices') {
    $Main->input->clean_array_gpc('r', array(
        'from_id' => TYPE_UINT,
        'to_id' => TYPE_UINT,
        'distance' => TYPE_UINT,
        'auto_id' => TYPE_UINT
    ));
        $from = $Main->GPC['from_id'];
        $to = $Main->GPC['to_id'];
        $km = (int)$Main->GPC['distance'];


    $status = true;

    $from_info = $Taxi->cities->GetItemById($Main->GPC['from_id']);
    if ($from_info and $from_info['city_status']) {

    } else {
        $status = false;
    }

    $to_info = $Taxi->cities->GetItemById($Main->GPC['to_id']);
    if ($to_info and $from_info['city_status']) {

    } else {
        $status = false;
    }

    $Taxi->routes->CreateModel();
    $Taxi->routes->model->columns_where->getStart()->setValue($from_info['city_id']);
    $Taxi->routes->model->columns_where->getEnd()->setValue($to_info['city_id']);
    $Taxi->routes->model->columns_where->getStatus()->setValue(true);
    $route_info = $Taxi->routes->GetItem(1);

    $km = $Main->GPC['distance'] / 1000;

    $Main->db->query_write('Update  taxi_routes SET route_views=route_views+1
WHERE route_id=' . $Main->db->sql_prepare($route_info['route_id']));

    $Main->db->query_write('Update  taxi_cities SET city_views=city_views+1
WHERE city_id=' . $Main->db->sql_prepare($to_info['city_id']));

    $prices = $Taxi->prices->getPricesFrom($from_info['city_id'], $to_info['city_id'], false)[$from_info['city_id']][$to_info['city_id']];

    if ($prices) {
        $classes = $prices['classes'];

        foreach ($Main->template->global_vars['classes'] as $i => $b) {
            if (isset($classes[$i]) == false) {
                $classes[$i] = $b;
                $classes[$i]['price_value'] = ceil($km * $b['class_price_km'] / 50) * 50 - 1;
                if (intval($classes[$i]['price_value']) < 0) {
                    $classes[$i]['price_value'] = 0;
                }
                if ((int)$classes[$i]['price_value']<(int)$classes[$i]['class_min_price']){
                    $classes[$i]['price_value'] = (int)$classes[$i]['class_min_price'];

                }

            }

        }
    } else {
        $classes = $Main->template->global_vars['classes'];

        foreach ($classes as $i => $b) {
            $classes[$i]['price_value'] = ceil($km * $b['class_price_km'] / 50) * 50 - 1;

            if (intval($classes[$i]['price_value']) < 0) {
                $classes[$i]['price_value'] = 0;
            }
            if ((int)$classes[$i]['price_value']<(int)$classes[$i]['class_min_price']){
                $classes[$i]['price_value'] = (int)$classes[$i]['class_min_price'];
            }
//            $array['type_car'][$i]=$classes[$i]['price_value'];
        }
    }

    $array['type_car'] = $classes;
    foreach ($classes as $k=>$v){

        if (intval($classes[$k]['price_value']) < 0) {
            $classes[$k]['price_value'] = 0;
        }
        if ((int)$classes[$k]['price_value']<(int)$classes[$k]['class_min_price']){
            $classes[$k]['price_value'] = (int)$classes[$k]['class_min_price'];
        }
        $array['type_car'][$k] = $classes[$k]['price_value'];
    }

    $array['status'] = $status;

    $Main->template->DisplayJson($array);
    exit();

}

if ($Main->GPC["content_type"] == 'hotels'){
    $from_city_url = 'aeroport_simferopol';


    $city = $Taxi->cities->GetItemByUrl("aeroport-simferopol", true);
    $cities = $Taxi->cities->getHotels(true);
    $cities[113] = $Taxi->cities->GetItemById(113,true);
    $Main->template->Display(
        array(
            'from_city_url' =>"aeroport_simferopol",
            'from_city' =>$city["city_id"],
            'classes' => $Taxi->classes->getClasses(true),
            'cities' => $cities,
            'prices' => $Taxi->prices->getPricesFrom($city["city_id"])[$city["city_id"]],
        ));

    exit;

}
if ($Main->GPC["content_type"] == 'places'){
    $from_city_url = 'aeroport_simferopol';
    $city = $Taxi->cities->GetItemByUrl("aeroport-simferopol", true);
    $cities = $Taxi->cities->getPlaces(true);
    $cities[113] = $Taxi->cities->GetItemById(113,true);

    $Main->template->Display(
        array(
            'from_city_url' =>"aeroport_simferopol",
            'from_city' =>$city["city_id"],
            'classes' => $Taxi->classes->getClasses(true),
            'cities' => $cities,
            'prices' => $Taxi->prices->getPricesFrom($city["city_id"])[$city["city_id"]],
        ));exit;

}



$filter_s=array();
$filter_s['key']='prices';
$fields=$SettingsClass->GetGroupValues($filter_s);

$Main->template->Display(
    array(
        'from_city_url' =>$from_city_url,
        'from_city' =>$city["city_id"],
        'classes' => $Taxi->classes->getClasses(true),
        'cities' => $Taxi->cities->getCities(true),
        'citiesSelect' => $Taxi->cities->getCitiesSelect(true),

        'prices' => $Taxi->prices->getPricesFrom($city["city_id"])[$city["city_id"]],
	    'fields'=>$fields
    ));

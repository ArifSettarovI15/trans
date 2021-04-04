<?php
$Main->user->PagePrivacy();


if ($Main->GPC['url_from']) {
    $from_info=$Taxi->cities->GetItemByUrl($Main->GPC['url_from'],1);
    if ($from_info and $from_info['city_status']) {

    }
    else {
        $Main->error->ObjectNotFound();
    }
}
if ($Main->GPC['url_to']) {
    $to_info=$Taxi->cities->GetItemByUrl($Main->GPC['url_to'],1);
    if ($to_info and $from_info['city_status']) {

    }
    else {
        $Main->error->ObjectNotFound();
    }
}

$Taxi->routes->CreateModel();
$Taxi->routes->model->columns_where->getStart()->setValue($from_info['city_id']);
$Taxi->routes->model->columns_where->getEnd()->setValue($to_info['city_id']);
$Taxi->routes->model->columns_where->getStatus()->setValue(true);
$route_info=$Taxi->routes->GetItem(1);

if ($route_info) {

}
else {
    $Main->error->ObjectNotFound();
}

$Main->db->query_write('Update  taxi_routes SET route_views=route_views+1
WHERE route_id='.$Main->db->sql_prepare($route_info['route_id']));

$Main->db->query_write('Update  taxi_cities SET city_views=city_views+1
WHERE city_id='.$Main->db->sql_prepare($to_info['city_id']));

$route_name=$from_info['city_title'].' - '.$to_info['city_title'];

$page_name='Такси '.$route_name;

$prices=$Taxi->prices->getPricesFrom($from_info['city_id'],$to_info['city_id'])[$from_info['city_id']][$to_info['city_id']];
if ($prices) {

}
else {
	$Main->error->ObjectNotFound();
}

$min_price=false;

foreach ($prices['classes'] as $price) {
	if ($min_price===false or $min_price>$price['price_value']) {
		$min_price=$price['price_value'];
	}
}

$breadcrumbs=array();
$breadcrumbs[]=array(
    'title'=>'Тарифы',
    'link'=>BASE_URL.'/prices/'
);
$breadcrumbs[]=array(
    'title'=>$page_name
);

$mm='';
if ($min_price) {
	$mm=' от '.$min_price.' руб.';
}

$desc='Заказать трансфер '.trim($from_info['city_title']).' - '.trim($to_info['city_title']).$mm.' на сайте онлайн или тел. 8(800) 555-11-65 (звонок бесплатный)';

$Main->template->SetPageAttributes(
    array(
        'title'=>$page_name,
        'keywords'=>'',
        'desc'=>$desc
    ),
    array(
        'breadcrumbs'=>$breadcrumbs,
        'title'=>$page_name
    )
);




$prices_pop=$Taxi->prices->getPricesFrom($from_info['city_id'],0, true);

$Main->template->Display(
    array(
        'route_name'=>$route_name,
        'from_info'=>$from_info,
        'to_info'=>$to_info,
        'prices'=>$prices,
        'prices_pop'=>$prices_pop,
        'route_info'=>$route_info
    ));

<?php

$Main->user->PagePrivacy('user,admin');

    $status = 0;
    $distance = 0;
    $phone = $Main->user_profile["profile_phone"];
    $array = array();
    $result_for_distance = array();

    $classes = $Taxi->classes->getClasses();



    $orders = $Taxi->orders->GetItemsByUser($Main->user_info['user_id']);

    $result = $Main->db->query_read("SELECT * FROM taxi_orders_statuses");


    foreach ($result as $item){
        $order_statuses[] = $item;
    }
    array_unshift($order_statuses, $order_statuses[0]);
    unset($order_statuses[0]);

    foreach ($orders as $order) {
        $orders[$order['order_id']]['order_data'] = unserialize($order['order_data']);
        $city =$Taxi->cities->GetItemById($orders[$order['order_id']]['order_data']['from']);
        $orders[$order['order_id']]['city_from'] =$city["city_title"];
        $city =$Taxi->cities->GetItemById($orders[$order['order_id']]['order_data']['to']);
        $orders[$order['order_id']]['city_to'] = $city["city_title"];
        $orders[$order['order_id']]['class_title'] = $classes[$orders[$order['order_id']]['order_data']['car_id']]['class_title'];
    }

$allOrders = $Taxi->orders->GetAllById($Main->user_info['user_id']);
foreach ($allOrders as $item){
    if ($item['order_status']==4){
    $data = unserialize($item['order_data']);
    $distance = $distance + intval($data["router-length"]);
    }

}
$page_name = 'Кабинет';

$breadcrumbs = array();
$breadcrumbs[] = array(
);

$Main->template->SetPageAttributes(
    array(
        'title' => $page_name,
        'keywords' => 'cabinet',
        'desc' => '',
        'header_image_url'=>'/cabinet/'
    ),
    array(
        'breadcrumbs' => $breadcrumbs,
        'title' => $page_name
    )
);

    $array['list']=$orders;
    $array['distance']=$distance;
    $array['last_order'] = $Taxi->orders->getLastSubmitedById($Main->user_info["user_id"]);
    $array['count'] = $Taxi->orders->GetTotalById($Main->user_info["user_id"]);
    $Main->template->Display($array);

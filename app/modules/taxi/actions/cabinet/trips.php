<?php
$Main->user->PagePrivacy('user,admin');
$array = array();


$array['block'] = 'order';
$classes = $Taxi->classes->getClasses();
$Paging = new ClassPaging($Main, 3);
$Paging->show_per_page=true;
$Paging->template='frontend/components/paging/paging.twig';
$Paging->template2='frontend/components/paging/paging.twig';
$Paging->template3='frontend/components/paging/paging.twig';
$Main->input->clean_array_gpc('r', array(
    'id'=>TYPE_UINT,
));
if (!$Main->GPC_exists['id']){
    $Main->GPC['id']=0;
}

$orders = $Taxi->orders->GetAllById($Main->user_info["user_id"], $Main->GPC['id'], $Paging->sql_start, $Paging->per_page);
$Taxi->orders->CreateModel();
$Taxi->orders->model->setSelectFields($Taxi->orders->model->getTableName().".*");
$Taxi->orders->model->columns_where->getUserId()->setValue($Main->user_profile['profile_user_id']);
if ($Main->GPC_exists['id'] and  $Main->GPC['id']!=0){
    $Taxi->orders->model->columns_where->getStatus()->setValue($Main->GPC['id']);

}


$Paging->total = $Taxi->orders->GetTotal();

foreach ($orders as $order){
    $orders[$order['order_id']]['order_data'] = unserialize($order['order_data']);
    $city =$Taxi->cities->GetItemById($orders[$order['order_id']]['order_data']['from']);
    $orders[$order['order_id']]['city_from'] =$city["city_title"];
    $city =$Taxi->cities->GetItemById($orders[$order['order_id']]['order_data']['to']);
    $orders[$order['order_id']]['city_to'] = $city["city_title"];
    $orders[$order['order_id']]['class_title'] = $classes[$orders[$order['order_id']]['order_data']['car_id']]['class_title'];
}

$Paging->data = $orders;
$array['block'] = 'order';
$array['mod'] = 3;
$array['statuses'] = $Main->db->query_read("SELECT DISTINCT status_id, status_name from taxi_orders_statuses LEFT JOIN taxi_orders 
                                                ON taxi_orders.order_status=taxi_orders_statuses.status_id 
                                                WHERE taxi_orders.order_user_id=".$Main->db->sql_prepare($Main->user_profile['profile_user_id']));
$page_name = 'Поездки';
$breadcrumbs = array();
$breadcrumbs[] = array();
$Main->template->SetPageAttributes(
    array(
        'title' => $page_name,
        'keywords' => 'trips',
        'desc' => '',
        'header_image_url'=>'/cabinet/'
    ),
    array(
        'breadcrumbs' => $breadcrumbs,
        'title' => $page_name
    )
);

$Paging->Display('frontend/components/table-list/table-list.twig',$array);

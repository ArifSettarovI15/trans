<?php
$Main->user->PagePrivacy('admin');

$array =array();

$Paging = new ClassPaging($Main,20);
$Paging->show_per_page=true;
$page_name='Заказы';
$Main->template->SetPageAttributes(
    array(
        'title'=>$page_name,
        'keywords'=>'',
        'desc'=>''
    ),
    array(
        'breadcrumbs'=>array(
            array(
                'title'=>$page_name
            )
        ),
        'title'=>$page_name
    )
);
$array['status'] = true;
$Main->input->clean_array_gpc('r',array(
    'order_id'=>TYPE_UINT,
    'order_status'=>TYPE_STR,
    'order_phone'=>TYPE_STR,
    'order_time_up'=>TYPE_STR,
    'order_time_to'=>TYPE_STR,
    "order_code"=>TYPE_STR));

$Taxi->orders->CreateModel();
$Taxi->orders->model->setSelectField($Taxi->orders->model->getTableName().'.*, taxi_orders_statuses.*');
$Taxi->orders->model->setJoin("LEFT JOIN taxi_orders_statuses ON taxi_orders.order_status=taxi_orders_statuses.status_id");


if ($Main->GPC_exists['order_status'] and $Main->GPC['order_status']!=''){
    $Taxi->orders->model->columns_where->getStatus()->setValue($Main->GPC['order_status']);
    $Taxi->orders->model->columns_where->getStatus()->setSearch(true);
}
if ($Main->GPC_exists['order_phone'] and $Main->GPC['order_phone']!=''){
    $Taxi->orders->model->columns_where->getPhone()->setValue($Main->GPC['order_phone']);
    $Taxi->orders->model->columns_where->getPhone()->setSearch(true);
}
if ($Main->GPC_exists['order_code'] and $Main->GPC['order_code']!=''){
    $Taxi->orders->model->columns_where->getCode()->setValue($Main->GPC['order_code']);
    $Taxi->orders->model->columns_where->getCode()->setSearch(true);
}
if ($Main->GPC['order_time_up']){
    $Taxi->orders->model->columns_where->getTime()->moreValue(str_replace('T', " ",$Main->GPC['order_time_up']));
    $Taxi->orders->model->columns_where->getTime()->setSearch(true);
}
if ($Main->GPC['order_time_to']){
    $Taxi->orders->model->columns_where->getTime()->lessValue(str_replace('T', " ",$Main->GPC['order_time_to']));
    $Taxi->orders->model->columns_where->getTime()->setSearch(true);
}
if ($Main->GPC['order_time_up'] and $Main->GPC['order_time_to']){
    $Taxi->orders->model->columns_where->getTime()->rangeValue(str_replace('T', " ",$Main->GPC['order_time_up']),str_replace('T', " ",$Main->GPC['order_time_to']));
    $Taxi->orders->model->columns_where->getTime()->setSearch(true);
}
$array['orders_statuses']=$Main->db->query_read("SELECT * FROM taxi_orders_statuses");
$Taxi->orders->model->setOrderBy('order_time DESC');
$Taxi->orders->model->setCount($Paging->per_page);
$Taxi->orders->model->setStart($Paging->sql_start);
$Paging->total= $Taxi->orders->GetTotal();
$orders = $Taxi->orders->GetList();
$array['cities'] = $Taxi->cities->getCities();
foreach ($orders as $order){
    $orders[$order['order_id']]['order_data'] = unserialize($order['order_data']);
}
$Paging->data=$orders;
$Paging->Display('taxi/manager/order_table.twig',$array);
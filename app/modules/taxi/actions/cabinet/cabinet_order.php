<?php
$Main->user->PagePrivacy('user,admin');


if ($Main->GPC['id']==0){
    $Main->error->PageNotFound();
}
$page_name = 'Заказ '.$Main->GPC['id'];

$breadcrumbs = array();
$breadcrumbs[] = array();

$Main->template->SetPageAttributes(
    array(
        'title' => $page_name,
        'keywords' => '',
        'desc' => '',
        'header_image_url'=>'/cabinet/'
    ),
    array(
        'breadcrumbs' => $breadcrumbs,
        'title' => $page_name
    ));

$classes = $Taxi->classes->getClasses();

$order = $Taxi->orders->GetItemById($Main->GPC['id']);
if ($order==false or $order["order_user_id"]!=$Main->user_info['user_id']){
	$Main->error->PageNotFound();
}


if ($Main->GPC['action'] == "process_deleteOrder"){
    $Main->input->clean_array_gpc('r',array('id'=>TYPE_UINT));
    if ($order and in_array($order['order_status'], ['',1])){
        $result = $Taxi->orders->DisableItemById($Main->GPC['id']);
        $Main->db->query_write('
                            INSERT INTO taxi_orders_life (life_order_id, life_order_status) 
                            VALUES(' . $Main->db->sql_prepare($Main->GPC['id']) . ', 0)');
        $result = 1;
        if($result){
            $Taxi->telegram_bot->sendDisableOrderMessage('sendMessage', $order['order_code']);
            $Main->template->DisplayJson(array('status'=>true, 'text'=>"Ваш заказ отменен! "));
            exit();
        }
        else
        {
            $Main->template->DisplayJson(array('status'=>false, 'text'=>"Не удалось отменить заказ. Свяжитесь с менеджером! "));
        }
    }
    else{
        $Main->template->DisplayJson(array('status'=>false, 'text'=>"Невозможно отменить заказ со статусом ".$order['status_name']));
    }
}


$order['order_data'] =unserialize($order['order_data']);
$city = $Taxi->cities->GetItemById($order['order_data']['from']);
$order['city_from'] =$city["city_title"];
$city =$Taxi->cities->GetItemById($order['order_data']['to']);
$order['city_to'] = $city["city_title"];
$order['class_title'] = $classes[$order['order_data']['car_id']]['class_title'];
$array['order'] = $order;
$array['order_life'] = $Main->db->query_read("SELECT life_order_time, status_name 
                                                  FROM taxi_orders_life 
                                                  LEFT JOIN taxi_orders_statuses 
                                                  ON life_order_status=taxi_orders_statuses.status_id 
                                                  WHERE life_order_id=".$Main->db->sql_prepare($Main->GPC['id'])." ORDER BY life_order_time DESC");
$Main->template->Display($array);



<?php
$Main->user->PagePrivacy('admin');
$order = $Taxi->orders->GetItemById($Main->GPC['id']);

$forCheckStatus = $order['order_status'];
$edit = 0;

if ($Main->GPC['do'] = 'edit'){
    $edit=1;
}

if ($Main->GPC['action'] == 'process_getPriceAdmin') {
    $Main->input->clean_array_gpc('r', array(
        'from' => TYPE_UINT,
        'to' => TYPE_UINT,
        'car_id' => TYPE_UINT,));
    $price = $Taxi->prices->getPriceOnClass($Main->GPC['from'], $Main->GPC['to'], $Main->GPC['car_id']);
    if ($price){
        $array['status'] = true;
    }
    else{
        $array['status'] = false;
    }
    $array['price'] = $price;
    $Main->template->DisplayJson($array);
}

if ($Main->GPC['action'] == 'process_edit'){
    $Main->input->clean_array_gpc('r', array(
        'from' => TYPE_STR,
        'router-length' => TYPE_STR,
        'to' => TYPE_STR,
        'car_id' => TYPE_STR,
        'phone' => TYPE_STR,
        'date' => TYPE_STR,
        'passengers' => TYPE_STR,
        'services' => TYPE_STR,
        'comment' => TYPE_STR,
        'transfer' => TYPE_STR,
        'code' => TYPE_STR,
        'order_id' => TYPE_UINT,
        'price' => TYPE_UINT,
        'order_status' => TYPE_UINT,
    ));


    $phone_user = preg_replace("/[^a-zA-Zа-яА-ЯЁё\d]/", "", $Main->GPC['phone']);
    $user = $Main->user->GetUserByLogin($phone_user);
    $Main->GPC["user_id"] = $user['user_id'];

    if(!$Main->GPC["user_id"]){
        $Main->GPC["user_id"]=0;
    }
    $order_id = $Main->db->query_write('
                UPDATE taxi_orders SET 
                order_user_id='.$Main->db->sql_prepare($Main->GPC["user_id"]).',
                order_data='.$Main->db->sql_prepare(serialize($Main->GPC)).',
                order_phone='.$Main->db->sql_prepare($phone_user) . ',
                order_status='.$Main->db->sql_prepare($Main->GPC['order_status']).' 
                WHERE order_id='.$Main->GPC['id']);

    if ($order_id){
        $order = $Taxi->orders->GetItemById($Main->GPC['id']);
        $order['order_data'] = unserialize($order['order_data']);

        $cities= array();
        $from= $Taxi->cities->GetItemById($order['order_data']['from']);
        $to= $Taxi->cities->GetItemById($order['order_data']['to']);

        $classes = $Taxi->classes->getClasses();

        $user = $Main->user->GetUserByLogin($phone_user);
        $Main->GPC["user_id"] = $user['user_id'];
        if ($forCheckStatus != $Main->GPC['order_status']) {
            if(intval($Main->GPC['order_status'])==4 and $Main->GPC['user_id']!=''){
                $user=$Main->user->GetUserProfile(intval($Main->GPC['user_id']));
                if ($user['profile_user_id']){
                    $order_discounts = $user["profile_discounts"];

                    if (!in_array($order["order_code"], $order_discounts )){

                        if ($user['profile_discount']+1 == 5){
                            $user['profile_discount']=0;
                            $order_discounts[]='';
                            $user['profile_bonus']+=1;
                        }
                        else{
                        $user['profile_discount'] +=1;
                            $order_discounts[]=$order["order_code"];
                        }
                        $Main->db->query_write("UPDATE users_profile SET 
                                    profile_discount=".$Main->db->sql_prepare($user['profile_discount']).", 
                                    profile_bonus=".$Main->db->sql_prepare($user['profile_bonus']).", 
                                    profile_discounts=".$Main->db->sql_prepare(serialize($order_discounts))." WHERE profile_user_id=".$user['profile_user_id']);
                    }
                }
            }
            $Main->db->query_write('
                            INSERT INTO taxi_orders_life (life_order_id, life_order_status) 
                            VALUES(' . $Main->GPC['id'] . ', ' . $Main->GPC['order_status'] . ')');
        }
        $message_data= array(
            'change'=>'<b>Данные заказа <i>'.$order['order_code'].'</i> изменены</b>',
            'price'=> $order['order_data']['price'],
            'type'=> 'Обычная',
            'from'=>$from['city_title'],
            'to'=>$to['city_title'],
            'class'=>$classes[$order['order_data']['car_id']]['class_title'],
            'date'=>$order['order_data']['date'],
            'services'=>$order['order_data']['services'],
            'passengers'=>$order['order_data']['passengers'],
            'phone'=>$order['order_data']['phone'],
            'comment'=>$order['order_data']['comment'],
        );
        if ($order['order_status'] != 3 and $order['order_status'] != 4 and $order['order_status'] != 0)
        {
            $data = $Taxi->telegram_bot->sendMessage('sendMessage', $message_data);
            $status = $Main->db->query_first("SELECT * FROM taxi_orders_statuses WHERE status_id=".$Main->db->sql_prepare($order['order_status']));
            $data = $Taxi->smsru->sendSms($message_data['phone'], 'Статус вашего заказа изменен на: '.$status['status_name]']);

        }
        if
        ($order['order_status']==4){
            $data = $Taxi->smsru->sendSms($message_data['phone'], 'Статус вашего заказа изменен на: '.$status['status_name]']);
        }
        elseif ($order['order_status'] == 0){
            $data = $Taxi->telegram_bot->sendDisableOrderMessage('sendMessage', $order['order_id']);
            $data = $Taxi->smsru->sendSms($message_data['phone'], 'Ваз заказ отменен');
        }
        $Main->template->DisplayJson(array('status'=>true, 'text'=>"Данные заказа обновлены!"));
    }
    else{
        $Main->template->DisplayJson(array('status'=>false, 'text'=>"Ошибка обновления данных!"));
    }
}

$order = $Taxi->orders->GetItemById($Main->GPC['id']);

$order['order_data'] = unserialize($order['order_data']);
$order['services'] = explode("; ", $order['order_data']['services']);

$array['info'] = $order;
$array['orders_statuses'] = $Main->db->query_read("SELECT * FROM taxi_orders_statuses");
$array['edit'] = $edit;
$array['cities'] = $Taxi->cities->getCities();
$array['classes'] = $Taxi->classes->getClasses();
$Main->template->Display($array);

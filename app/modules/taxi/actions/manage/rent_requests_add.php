<?php
$Main->user->PagePrivacy('admin');
$array= array();
$edit=1;
$array['edit'] = $edit;
if ($Main->GPC['action'] == "process_edit"){
    $Main->input->clean_array_gpc('r', array(
        'name'=> TYPE_STR,
        'place'=> TYPE_STR,
        'phone'=> TYPE_STR,
        'adress'=> TYPE_STR,
        'adress2'=> TYPE_STR,
        'parent1_name'=> TYPE_STR,
        'parent1_parants'=> TYPE_STR,
        'parent1_phone'=> TYPE_STR,
        'parent2_name'=> TYPE_STR,
        'parent2_parants'=> TYPE_STR,
        'parent2_phone'=> TYPE_STR,
        'parent3_name'=> TYPE_STR,
        'parent3_parants'=> TYPE_STR,
        'parent3_phone'=> TYPE_STR,
        'parent4_name'=> TYPE_STR,
        'parent4_parants'=> TYPE_STR,
        'parent4_phone'=> TYPE_STR,
        'parent5_name'=> TYPE_STR,
        'parent5_parants'=> TYPE_STR,
        'parent5_phone'=> TYPE_STR,
        'family'=> TYPE_STR,
        'children'=> TYPE_STR,
        'driver_lisence'=> TYPE_STR,
        'driver_number'=> TYPE_STR,
        'driver_exp'=> TYPE_STR,
        'driver_cat'=> TYPE_STR,
        'driver_time'=> TYPE_STR,
        'android_skills'=> TYPE_STR,
        'city_skills'=> TYPE_STR,
        'education'=> TYPE_STR,
        'spec'=> TYPE_STR,
        'finance'=> TYPE_STR,
        'graph'=>TYPE_STR,
        'car_id'=>TYPE_UINT,
    ));
    $Main->GPC['phone'] = preg_replace("/[^a-zA-Zа-яА-ЯЁё\d]/","",$Main->GPC['phone']);
    $data = serialize($Main->GPC);
    $sql = $Main->db->query_write("
                UPDATE taxi_rent_requests SET rent_req_data=".$Main->db->sql_prepare($data).", rent_req_name=".$Main->db->sql_prepare($Main->GPC['name']).",
                 rent_req_phone=".$Main->db->sql_prepare($Main->GPC['phone']).", rent_req_car_id=".$Main->GPC['car_id']." WHERE rent_req_id=".$Main->GPC['id']);

    if($sql){
        $array = array();
        $array['status']= true;
        $array['text'] = "Данные успешно обновлены!";
        $Main->template->DisplayJson($array);
        exit;
    }
}
$array['cars']= $Taxi->cars->getRentCars();

$item = $Main->db->query_first("SELECT * FROM taxi_rent_requests WHERE rent_req_id=".$Main->GPC['id']);
$item['rent_req_data'] = unserialize($item['rent_req_data']);
$array['info'] = $item;
$Main->template->Display($array);
<?php
$Main->user->PagePrivacy("admin");
$array = array();

$edit = 0;
if ($Main->GPC['do'] =='edit' OR $Main->GPC['action']=='process_edit'){
    $array['info'] = $Taxi->drivers->GetItemById($Main->GPC['id']);
    $edit = 1;

}
if ($Main->GPC['action'] == 'process_add' or $Main->GPC['action'] == 'process_edit'){
    $Main->input->clean_array_gpc('r', array(
        'driver_id' =>TYPE_UINT,
        'driver_name' =>TYPE_STR,
        'driver_phone'=>TYPE_STR,
        'driver_car_id'=>TYPE_UINT,
        'driver_license'=>TYPE_UINT,
        'driver_address'=>TYPE_STR,
        'driver_birthday'=>TYPE_STR,

    ));
    $Main->GPC['driver_phone'] = preg_replace("/[^a-zA-Zа-яА-ЯЁё\d]/","",$Main->GPC['driver_phone']);
    $Taxi->drivers->CreateModel();
    $Taxi->drivers->model->columns_update->getName()->setValue($Main->GPC['driver_name']);
    $Taxi->drivers->model->columns_update->getPhone()->setValue($Main->GPC['driver_phone']);
    $Taxi->drivers->model->columns_update->getCarId()->setValue($Main->GPC['driver_car_id']);
    $data = serialize($Main->GPC);
    $Taxi->drivers->model->columns_update->getData()->setValue($data);
    if ($Main->GPC['action'] == 'process_edit'){
//        $Main->template->DisplayJson($Main->GPC['id']);
        $Taxi->drivers->model->columns_where->getId()->setValue($Main->GPC['id']);
        $result = $Taxi->drivers->Update();

        $array['text'] = "Данные водителя обновлены";
    }
    elseif ($Main->GPC['action'] == 'process_add'){
        $result = $Taxi->drivers->Insert();
        $array['text'] = "Новый водитель сохранен!";
    }
    if ($result){
        $array['status'] = true;
        $Main->template->DisplayJson($array);
    }
    else{
        $array['status'] = false;
        $array['text']  ="Ошибка сохранения данных водителя!";
        $Main->template->DisplayJson($array);
    }
}
$array['cars'] = $Taxi->cars->getRentCars();

$array['edit'] = $edit;
$Main->template->Display($array);
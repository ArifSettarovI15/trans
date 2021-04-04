<?php

$Main->user->PagePrivacy('admin');
$rent_car =$Taxi->rent_cars->GetItemById($Main->GPC['id']);
$car = $Taxi->cars->GetItemById($rent_car['rent_car_id']);

$array = array();

if ($Main->GPC['action']=='process_edit' && $Main->GPC['do']!='edit') {

    $Main->input->clean_array_gpc('r', array(
        'rent_id' => TYPE_UINT
    ));
}


if ($Main->GPC['do']=='edit' OR $Main->GPC['action']=='process_edit') {

    $edit=1;
    $Main->input->clean_array_gpc('r', array(
        'rent_id' => TYPE_UINT,
        'rent_car_year' => TYPE_UINT,
        'rent_car_run' =>TYPE_UINT,
        'rent_car_engine_capacity' =>TYPE_STR,
        'rent_car_transmission' =>TYPE_STR,
        'rent_car_city' =>TYPE_STR,
        'rent_car_buy'=>TYPE_UINT
    ));

    $data_info=$Taxi->rent_cars->GetItemById($Main->GPC['rent_id']);
    if ($data_info) {
        $Taxi->rent_cars->CreateModel();
        $Taxi->rent_cars->model->columns_update->getCarYear()->setValue($Main->GPC['rent_car_year']);
        $Taxi->rent_cars->model->columns_update->getCarRun()->setValue($Main->GPC['rent_car_run']);
        $Taxi->rent_cars->model->columns_update->getCarEngineCapacity()->setValue(floatval($Main->GPC['rent_car_engine_capacity']));
        $Taxi->rent_cars->model->columns_update->getCarTransmission()->setValue($Main->GPC['rent_car_transmission']);
        $Taxi->rent_cars->model->columns_update->getCarCity()->setValue($Main->GPC['rent_car_city']);
        $Taxi->rent_cars->model->columns_update->getCarBuy()->setValue($Main->GPC['rent_car_buy']);
        $Taxi->rent_cars->model->columns_where->getId()->setValue($Main->GPC['rent_id']);
        $result = $Taxi->rent_cars->Update();
        if ($result){
            $array['status'] = true;
            $array['text'] = 'Значение успешно обновлено';
            $Main->template->DisplayJson($array);
        }
        else{
            $array['status'] = false;
            $array['text'] = 'Проблема добавления значения!';
        }


    }
    else {

        $Main->error->ObjectNotFound();
    }
}




$array['edit'] = 1;
$array['rent'] = $rent_car;
$array['car'] = $car;
$array['class'] = $Taxi->classes->GetItemById($car['car_class']);
$Main->template->Display($array);
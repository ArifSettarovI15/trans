<?php
$Main->user->PagePrivacy('admin');

$variables = array();
if ($Main->GPC['action']=='delete') {
    $Main->input->clean_array_gpc('r', array(
        'object_id' => TYPE_UINT
    ));

    $city_info=$Taxi->rent_cars->GetItemById($Main->GPC['object_id']);
    $Taxi->rent_cars->CreateModel();
    $Taxi->rent_cars->model->columns_where->getId()->setValue($Main->GPC['object_id']);
    $status=$Taxi->rent_cars->Delete();

    $array=array();
    $array['status']=$status;
    if ($status) {
        $array['text']='Объект успешно удален';
    }
    else {
        $array['text']='Ошибка удаления объекта';
    }
    $Main->template->DisplayJson($array);
}
$cars = $Taxi->rent_cars->getRentCars();
$variables['cars']= $cars;
$variables['data']= 'data';

$Paging = new ClassPaging($Main, 20);
$Paging->show_per_page = true;

$data_info=array();
$edit=0;


$Paging->total = $Taxi->rent_cars->getCount();
$Paging->data = $Taxi->rent_cars->getRentCars(false, $Paging->per_page, $Paging->sql_start);
$Paging->Display('taxi/manager/rent_cars_table.twig',$variables);
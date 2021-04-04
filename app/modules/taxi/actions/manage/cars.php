

<?php
$Main->user->PagePrivacy('admin');


if ($Main->GPC['action']=='delete') {
    $Main->input->clean_array_gpc('r', array(
        'object_id' => TYPE_UINT
    ));

    $car_info=$Taxi->cars->GetItemById($Main->GPC['object_id']);

    $Taxi->cars->CreateModel();
    $Taxi->cars->model->columns_where->getId()->setValue($Main->GPC['object_id']);
    $status=$Taxi->cars->Delete();
    $rent = $Taxi->rent_cars->GetItemByCarId($Main->GPC['object_id']);
    if ($rent){
        $Taxi->rent_cars->CreateModel();
        $Taxi->rent_cars->model->columns_where->getCarId()->setValue($Main->GPC['object_id']);
        $Taxi->rent_cars->Delete();
    }

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


if ($Main->GPC['action']=='update_status') {
    $Main->input->clean_array_gpc('r', array(
        'object_id'=>TYPE_UINT,
        'value'=>TYPE_BOOL
    ));


    $Taxi->cars->CreateModel();
    $Taxi->cars->model->columns_where->getId()->setValue($Main->GPC['object_id']);
    $Taxi->cars->model->columns_update->getStatus()->setValue($Main->GPC['value']);
    $status=$Taxi->cars->Update();

    $array=array();
    $array['status']=$status;
    if ($status) {
        $array['text']='Статус обновлен';
    }
    else {
        $array['text']='Ошибка';
    }
    $Main->template->DisplayJson($array);
}

$Main->input->clean_array_gpc('r', array(
    'car_id' => TYPE_UINT,
    'car_title' => TYPE_STR,
    'car_class'=> TYPE_UINT,
    'car_power'=> TYPE_UINT,
    'order' => TYPE_STR,
    'sort_filter'=>TYPE_BOOL
));

$variables=array();
$page_name='Автомобили';
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

$Paging = new ClassPaging($Main,100,false,false);
$Paging->show_per_page=true;

$Taxi->cars->CreateModel();
$Taxi->cars->model->setSelectField($Taxi->cars->model->getTableName().'.*, taxi_classes.*');
if ($Main->GPC_exists['car_id'] and $Main->GPC['car_id']>0){

    $Taxi->cars->model->columns_where->getId()->setValue($Main->GPC['car_id']);
}

if ($Main->GPC_exists['car_title'] and $Main->GPC['car_title']!=''){
    $Taxi->cars->model->columns_where->getTitle()->setValue($Main->GPC['car_title']);
    $Taxi->cars->model->columns_where->getTitle()->setSearch(true);
}
if ($Main->GPC_exists['car_class'] and $Main->GPC['car_class']!=''){
    $Taxi->cars->model->columns_where->getClass()->setValue($Main->GPC['car_class']);
    $Taxi->cars->model->columns_where->getClass()->setSearch(true);
}


$Paging->total=$Taxi->cars->GetTotal();

$Taxi->cars->model->SetJoinImage('icon',$Taxi->cars->model->GetTableItemName('icon'));
$Taxi->cars->model->setOrderBy('car_title');
$Taxi->cars->model->setJoin("LEFT JOIN taxi_classes ON taxi_cars.car_class=taxi_classes.class_id");
$Taxi->cars->model->setCount($Paging->per_page);
$Taxi->cars->model->setStart($Paging->sql_start);
$Paging->data = $Taxi->cars->GetList();
$variables['classes'] = $Taxi->classes->getClasses();
$Paging->Display('taxi/manager/cars_table.twig',$variables);
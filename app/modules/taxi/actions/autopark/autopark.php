<?php

$Main->user->PagePrivacy();

$classes = $Taxi->classes->getClasses();

$classHas = array();
$Taxi->cars->CreateModel();
foreach ($classes as $class){
$Taxi->cars->model->setSelectField($Taxi->cars->model->getTableName().(".*"));
$Taxi->cars->model->columns_where->getClass()->setValue($class['class_id']);
$Taxi->cars->model->columns_where->getPhotoId()->notValue(0);
$result = $Taxi->cars->GetTotal();

$classHas[$class["class_title"]] = $result;
}

$variables['classes'] = $classes;
$variables['block'] = 'park-thumb';
$variables['classHas'] = $classHas;


$Main->input->clean_array_gpc('r', array(
    'id' => TYPE_UINT,
));

$Paging = new ClassPaging($Main,8, false, false);

$Paging->show_per_page=true;

$Taxi->cars->CreateModel();


$Taxi->cars->model->setSelectField($Taxi->cars->model->getTableName().'.*, taxi_classes.*');
$Taxi->cars->model->columns_where->getPhotoId()->notValue(0);
$Taxi->cars->model->SetJoinImage('icon',$Taxi->cars->model->GetTableItemName('icon'));
$Taxi->cars->model->setJoin("LEFT JOIN taxi_classes ON taxi_cars.car_class=taxi_classes.class_id");

if ($Main->GPC['id']) {
    $Taxi->cars->model->columns_where->getClass()->setValue($Main->GPC['id']);
}

$Paging->total=$Taxi->cars->GetTotal();

$Taxi->cars->model->setOrderBy('car_title');
$Taxi->cars->model->setStart($Paging->sql_start);
$Taxi->cars->model->setCount($Paging->per_page);
$Paging->data=$Taxi->cars->GetList();

$variables['status'] = true;
$Paging->template='frontend/components/paging/paging.twig';

$page_name = 'Автопарк';

    $breadcrumbs = array();
    $breadcrumbs[] = array();


$Main->template->SetPageAttributes(
    array(
        'title' => $page_name,
        'keywords' => '',
        'desc' => '',
        'header_image_url'=>'/autopark/'
    ),
    array(
        'breadcrumbs' => $breadcrumbs,
        'title' => $page_name
    )
);


$filter_s=array();
$filter_s['key']='avtopark';
$variables['fields']=$SettingsClass->GetGroupValues($filter_s);

$Paging->Display('frontend/components/table-list/table-list.twig',$variables);

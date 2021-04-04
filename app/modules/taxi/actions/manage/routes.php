<?php
$Main->user->PagePrivacy('admin');


if ($Main->GPC['action']=='update_status') {
    $Main->input->clean_array_gpc('r', array(
        'object_id'=>TYPE_UINT,
        'value'=>TYPE_BOOL
    ));


    $Taxi->routes->CreateModel();
    $Taxi->routes->model->columns_where->getId()->setValue($Main->GPC['object_id']);
    $Taxi->routes->model->columns_update->getStatus()->setValue($Main->GPC['value']);
    $status=$Taxi->routes->Update();

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

/////////////////////////////////
$Main->input->clean_array_gpc('r', array(
    'route_start' => TYPE_UINT,
    'route_to' => TYPE_UINT,
    'route_id' => TYPE_UINT,
    'route_status' => TYPE_NUM
));

$variables=array();

$page_name='Маршруты';
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

$Paging =new ClassPaging($Main,100,false,false);
$Paging->show_per_page=true;


$Taxi->routes->CreateModel();

if ($Main->GPC_exists['route_id'] and $Main->GPC['route_id']>0){

    $Taxi->routes->model->columns_where->getId()->setValue($Main->GPC['route_id']);
}

if ($Main->GPC_exists['route_start'] and $Main->GPC['route_start']>0){

    $Taxi->routes->model->columns_where->getStart()->setValue($Main->GPC['route_start']);
}
if ($Main->GPC_exists['route_to'] and $Main->GPC['route_to']>0){

    $Taxi->routes->model->columns_where->getEnd()->setValue($Main->GPC['route_to']);
}
if ($Main->GPC_exists['route_status'] and $Main->GPC['route_status']!=-1){
    $Taxi->routes->model->columns_where->getStatus()->setValue($Main->GPC['route_status']);
    $variables['route_status']=$Main->GPC['route_status'];
}
else {
    $variables['route_status']=-1;
}
$Taxi->routes->model->setJoin('INNER JOIN taxi_cities cities_from ON taxi_routes.route_start=cities_from.city_id
        INNER JOIN taxi_cities cities_to ON taxi_routes.route_end=cities_to.city_id
        ');
$Paging->total=$Taxi->routes->GetTotal();

$Taxi->routes->model->setSelectField('taxi_routes.*,
        cities_from.city_title as from_city_title, cities_from.city_url as from_city_url,
         cities_to.city_title as to_city_title, cities_to.city_url as to_city_url');

$variables['cities']=$Taxi->cities->getCities(true);


$Taxi->routes->model->setCount($Paging->per_page);
$Taxi->routes->model->setStart($Paging->sql_start);

$Taxi->routes->model->setOrderBy('from_city_title, to_city_title');
$Paging->data=$Taxi->routes->GetList();

$Paging->Display('taxi/manager/routes_table.twig',$variables);

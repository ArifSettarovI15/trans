<?php


$Main->user->PagePrivacy();
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
    'car_id'=>TYPE_STR,
));
$fields = array();
$result=1;
if ($Main->GPC['action'] == "get_step_1"){

    if ($Main->GPC['name']==''){$result=0; $fields[]='ФИО';}
    if ($Main->GPC['place']==''){$result=0; $fields[]='Дата и место рождения';}
    if ($Main->GPC['phone']==''){$result=0; $fields[]='Телефон';}
    if (!$result){
        $Main->template->DisplayJson(array('status'=>false, 'text'=>"Заполните необходимые поля: ".implode(', ', $fields)));
    }
    else{$Main->template->DisplayJson(array('status'=>true));}
}
if ($Main->GPC['action'] == "get_step_2"){$Main->template->DisplayJson(array('status'=>true));}
if ($Main->GPC['action'] == "get_step_3"){
    if ($Main->GPC['driver_lisence']=='' or $Main->GPC['driver_number']==''){
        $Main->template->DisplayJson(array('status'=>false, 'text'=>"Введите сирию и номер водительского удостоверения"));
    }
    else{$Main->template->DisplayJson(array('status'=>true));}
}
if ($Main->GPC['action'] == "get_step_4"){

}
if ($Main->GPC['action'] == "process_newRent"){
    $Main->GPC['phone'] = preg_replace("/[^a-zA-Zа-яА-ЯЁё\d]/","",$Main->GPC['phone']);
    $data = serialize($Main->GPC);
    $sql = $Main->db->query_write("
                INSERT INTO taxi_rent_requests (rent_req_data,rent_req_name, rent_req_phone, rent_req_car_id) 
                VALUES (".$Main->db->sql_prepare($data).",".$Main->db->sql_prepare($Main->GPC['name']).",".
        $Main->db->sql_prepare($Main->GPC['phone']).",'1') ");
    if($sql){
        $array = array();
        $array['status']= true;
        $array['result'] =$Main->template->Render('frontend/components/modal-thx/modal-thx_2.twig',array('phone'=>$Main->GPC['phone']));
        $Main->template->DisplayJson($array);
        exit;
    }
}


$variables = array();
$variables['cars_icons'] = $Taxi->cars->getRentCarsIcons();
$variables['block'] = 'rentcar-thumb';


$cars_icons = $Taxi->cars->getRentCarsIcons();




$Paging = new ClassPaging($Main, 8);
$Paging->template='frontend/components/paging/paging.twig';
$Paging->show_per_page = true;


$filter=1;
$Main->input->clean_array_gpc('r', array('id'=>TYPE_UINT));
if ($Main->GPC_exists['id'] or $Main->GPC['id']==1){
    $filter = $Main->GPC['id'];
}

$Taxi->rent_cars->CreateModel();
$Taxi->rent_cars->model->setSelectField($Taxi->rent_cars->model->getTableName().".*, taxi_classes.*, taxi_cars.*");

$Taxi->rent_cars->model->setJoin("LEFT JOIN taxi_cars ON taxi_rent_cars.rent_car_id=taxi_cars.car_id");
$Taxi->rent_cars->model->setJoin("LEFT JOIN taxi_classes ON taxi_cars.car_class=taxi_classes.class_id");
$Taxi->rent_cars->model->setJoinImage('car_icon', 'car_icon');
$Taxi->rent_cars->model->columns_where->getCarBuy()->setValue($filter);
$Taxi->rent_cars->model->setStart($Paging->sql_start);
$Taxi->rent_cars->model->setCount($Paging->per_page);

$Paging->total = $Taxi->rent_cars->GetTotal();
$Paging->data = $Taxi->rent_cars->GetList();

$page_name = 'Аренда';
$breadcrumbs = array();
$breadcrumbs[] = array(
);

$filter_s=array();
$filter_s['key']='rent';

$variables['fields']=$SettingsClass->GetGroupValues($filter_s);

$Main->template->SetPageAttributes(
    array(
        'title' => $page_name,
        'keywords' => '',
        'desc' => '',
        'header_image_url'=>'/rent/'
    ),
    array(
        'breadcrumbs' => $breadcrumbs,
        'title' => $page_name
    )
);

$Paging->Display('frontend/components/table-list/table-list.twig', $variables);

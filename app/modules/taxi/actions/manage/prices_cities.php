<?php
$Main->user->PagePrivacy('admin');
$Main->input->clean_array_gpc('r', array(
    'city_title' => TYPE_STR,
    'city_type' => TYPE_STR,
    'city_id' => TYPE_UINT,
));

$searched = 0;
$array = array();

$Taxi->cities->CreateModel();
$Paging = new ClassPaging($Main);
if ($Main->GPC_exists['city_id'] and $Main->GPC['city_id']!=''){

    $Taxi->cities->model->columns_where->getId()->setValue($Main->GPC['city_id']);
    $Taxi->cities->model->columns_where->getId()->setSearch(true);
}if ($Main->GPC_exists['city_title'] and $Main->GPC['city_title']!=''){

    $Taxi->cities->model->columns_where->getTitle()->setValue($Main->GPC['city_title']);
    $Taxi->cities->model->columns_where->getTitle()->setSearch(true);
}if ($Main->GPC_exists['city_type'] and $Main->GPC['city_type']!='0'){
    $Taxi->cities->model->columns_where->getType()->setValue($Main->GPC['city_type']);
    $Taxi->cities->model->columns_where->getType()->setSearch(true);
}

$Taxi->cities->model->setOrderBy('city_title');
$Paging->data = $Taxi->cities->GetList();



$Paging->Display('taxi/manager/prices_cities_table.twig',$array);

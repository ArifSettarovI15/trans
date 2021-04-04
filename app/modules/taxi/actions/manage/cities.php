<?php
$Main->user->PagePrivacy('admin');

if ($Main->GPC['action']=='delete') {
    $Main->input->clean_array_gpc('r', array(
        'object_id' => TYPE_UINT
    ));

    $city_info=$Taxi->cities->GetItemById($Main->GPC['object_id']);

    $Taxi->cities->CreateModel();
    $Taxi->cities->model->columns_where->getId()->setValue($Main->GPC['object_id']);
    $status=$Taxi->cities->Delete();

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


    $Taxi->cities->CreateModel();
    $Taxi->cities->model->columns_where->getId()->setValue($Main->GPC['object_id']);
    $Taxi->cities->model->columns_update->getStatus()->setValue($Main->GPC['value']);
    $status=$Taxi->cities->Update();

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
    'city_title' => TYPE_STR,
    'city_url' => TYPE_STR,
    'city_id' => TYPE_UINT,
    'city_status' => TYPE_NUM,
    'order' => TYPE_STR,
    'sort_filter'=>TYPE_BOOL,
    'city_type'=>TYPE_UINT
));

$variables=array();

$page_name='Города';
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


$Taxi->cities->CreateModel();

if ($Main->GPC_exists['city_id'] and $Main->GPC['city_id']>0){

    $Taxi->cities->model->columns_where->getId()->setValue($Main->GPC['city_id']);
}

if ($Main->GPC_exists['city_status'] and $Main->GPC['city_status']!=-1){
    $Taxi->cities->model->columns_where->getStatus()->setValue($Main->GPC['city_status']);
    $variables['city_status']=$Main->GPC['city_status'];
}
else {
    $variables['city_status']=-1;
}

if ($Main->GPC_exists['city_title'] and $Main->GPC['city_title']!=''){
    $Taxi->cities->model->columns_where->getTitle()->setValue($Main->GPC['city_title']);
    $Taxi->cities->model->columns_where->getTitle()->setSearch(true);
}

if ($Main->GPC_exists['city_type'] and $Main->GPC['city_type']!=''){
    $Taxi->cities->model->columns_where->getType()->setValue($Main->GPC['city_type']);

    $Taxi->cities->model->columns_where->getType()->setSearch(true);
}


$Paging->total=$Taxi->cities->GetTotal();
$Taxi->cities->model->setSelectField($Taxi->cities->model->getTableName().'.*');
$Taxi->cities->model->SetJoinImage('icon',$Taxi->cities->model->GetTableItemName('icon'));
$Taxi->cities->model->setOrderBy('city_title');
$Taxi->cities->model->setCount($Paging->per_page);
$Taxi->cities->model->setStart($Paging->sql_start);

$Paging->data=$Taxi->cities->GetList();
$Paging->Display('taxi/manager/cities_table.twig',$variables);

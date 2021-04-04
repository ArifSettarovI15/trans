<?php
$Main->user->PagePrivacy('admin');

$array =array();

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

if ($Main->GPC['action']=='delete') {
    $Main->input->clean_array_gpc('r', array(
        'object_id' => TYPE_UINT
    ));

    $city_info=$Taxi->partners->getPartnerById($Main->GPC['object_id']);
    $Taxi->partners->CreateModel();
    $Taxi->partners->model->columns_where->getId()->setValue($Main->GPC['object_id']);
    $status=$Taxi->partners->Delete();

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


$Paging = new ClassPaging($Main,20);
$Paging->show_per_page=true;

$Main->input->clean_array_gpc('r', array(
    'partner_id'=>TYPE_UINT,
    'partner_uname'=>TYPE_STR,
    'partner_site'=>TYPE_STR,
    'partner_phone'=>TYPE_STR,
    'partner_type'=>TYPE_STR,
    'partner_time'=>TYPE_STR,
));
$Taxi->partners->CreateModel();
$Taxi->partners->model->setSelectField($Taxi->partners->model->getTableName().(".*"));

if ($Main->GPC_exists['partner_id'] and $Main->GPC['partner_id']!=''){
    $Taxi->partners->model->columns_where->getId()->setValue($Main->GPC['partner_id']);
    $Taxi->partners->model->columns_where->getId()->setSearch(true);
}
if ($Main->GPC_exists['partner_uname'] and $Main->GPC['partner_uname']!=''){
    $Taxi->partners->model->columns_where->getUname()->setValue($Main->GPC['partner_uname']);
    $Taxi->partners->model->columns_where->getUname()->setSearch(true);
}

if ($Main->GPC_exists['partner_site'] and $Main->GPC['partner_site']!=''){
    $Taxi->partners->model->columns_where->getSite()->setValue($Main->GPC['partner_site']);
    $Taxi->partners->model->columns_where->getSite()->setSearch(true);
}

if ($Main->GPC_exists['partner_phone'] and $Main->GPC['partner_phone']!=''){
    $Main->GPC['partner_phone'] = $res = preg_replace("/[^0-9]/", "", $Main->GPC["partner_phone"] );
    $Taxi->partners->model->columns_where->getPhone()->setValue($Main->GPC['partner_phone']);
    $Taxi->partners->model->columns_where->getPhone()->setSearch(true);
}
if ($Main->GPC_exists['partner_type'] and $Main->GPC['partner_type']!=''){
    $Taxi->partners->model->columns_where->getType()->setValue($Main->GPC['partner_type']);
    $Taxi->partners->model->columns_where->getType()->setSearch(true);
}
if ($Main->GPC_exists['partner_time'] and $Main->GPC['partner_time']!=''){

    $Taxi->partners->model->columns_where->getTime()->setValue($Main->GPC['partner_time']);
    $Taxi->partners->model->columns_where->getTime()->setSearch(true);

}
$Taxi->partners->model->setOrderBy('partner_time DESC');
$Taxi->partners->model->setStart($Paging->sql_start);
$Taxi->partners->model->setCount($Paging->per_page);
$Paging->total= $Taxi->partners->GetTotal();
$Paging->data = $Taxi->partners->GetList();

$page_name='Партнеры';


$array['status'] = true;
$Paging->Display('taxi/manager/partners_table.twig',$array);
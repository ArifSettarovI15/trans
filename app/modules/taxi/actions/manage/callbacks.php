<?php
$Main->user->PagePrivacy('admin');

$array=array();
if ($Main->GPC['action'] == "update_status"){
    $Main->input->clean_array_gpc('r', array('object_id'=>TYPE_UINT));
    $Main->db->query_write('UPDATE taxi_callbacks SET callback_status=1 WHERE callback_id='.intval($Main->GPC['object_id']));
    $Main->template->DisplayJson(array('status'=>true, 'text'=>'Статус заявки обновлен'));
}

$Paging = new ClassPaging($Main,10);
$Paging->show_per_page=true;
$Paging->total = $Taxi->callbacks->getCount();

$page_name='Заявки';
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
$Main->input->clean_array_gpc('r' ,array(
    'callback_status'=>TYPE_UINT,
    'callback_id'=>TYPE_STR,
    'callback_name'=>TYPE_STR,
    'callback_time'=>TYPE_STR,
    'callback_phone'=>TYPE_STR,
    'callback_time_up'=>TYPE_STR,
    'callback_time_to'=>TYPE_STR,));
$Taxi->callbacks->CreateModel();
$Taxi->callbacks->model->setSelectField($Taxi->callbacks->model->getTableName().".*");
if ($Main->GPC_exists['callback_status'] and $Main->GPC['callback_status']!=''){
    $Taxi->callbacks->model->columns_where->getStatus()->setValue($Main->GPC['callback_status']);
    $Taxi->callbacks->model->columns_where->getStatus()->setSearch(true);
}

if ($Main->GPC_exists['callback_time'] and $Main->GPC['callback_time']!=''){
    $Taxi->callbacks->model->columns_where->getTime()->setValue($Main->GPC['callback_time']);
    $Taxi->callbacks->model->columns_where->getTime()->setSearch(true);
}
if ($Main->GPC_exists['callback_name'] and $Main->GPC['callback_name']!=''){
    $Taxi->callbacks->model->columns_where->getUName()->setValue($Main->GPC['callback_name']);
    $Taxi->callbacks->model->columns_where->getUName()->setSearch(true);
}
if ($Main->GPC_exists['callback_id'] and $Main->GPC['callback_id']!=''){
    $Taxi->callbacks->model->columns_where->getId()->setValue($Main->GPC['callback_id']);
    $Taxi->callbacks->model->columns_where->getId()->setSearch(true);
}
if ($Main->GPC_exists['callback_phone'] and $Main->GPC['callback_phone']!=''){
    $Taxi->callbacks->model->columns_where->getPhone()->setValue($Main->GPC['callback_phone']);
    $Taxi->callbacks->model->columns_where->getPhone()->setSearch(true);
}
if ($Main->GPC_exists['callback_time'] and $Main->GPC['callback_time']!=''){
    $Taxi->callbacks->model->columns_where->getTime()->setValue($Main->GPC['callback_time']);
    $Taxi->callbacks->model->columns_where->getTime()->setSearch(true);
}

$Taxi->callbacks->model->setOrderBy("callback_time DESC");

$Paging->data = $Taxi->callbacks->GetList();
$Paging->total = $Taxi->callbacks->GetTotal();
$array['status'] = true;
$Paging->Display('taxi/manager/callbacks_table.twig',$array);
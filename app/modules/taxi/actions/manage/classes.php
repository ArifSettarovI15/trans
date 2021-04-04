<?php
$Main->user->PagePrivacy('admin');
if ($Main->GPC['action']=='sort_table') {
    $Main->input->clean_array_gpc('r', array(
        'data_sort' => TYPE_ARRAY_UINT
    ));

    $pos=0;
	$Taxi->classes->CreateModel();

    foreach ($Main->GPC['data_sort'] as $line_key) {
	    $Taxi->classes->model->columns_where->getId()->setValue($line_key);
	    $Taxi->classes->model->columns_update->getSort()->setValue($pos);
	    $Taxi->classes->Update();
        $pos++;
    }

    $array['status']=true;
    $array['text']='Позиции обновлены';
    $Main->template->DisplayJson($array);
}

if ($Main->GPC['action']=='delete') {
    $Main->input->clean_array_gpc('r', array(
        'object_id' => TYPE_UINT
    ));

    $class_info=$Taxi->classes->GetItemById($Main->GPC['object_id']);

    $Taxi->classes->CreateModel();
	$Taxi->classes->model->columns_where->getId()->setValue($Main->GPC['object_id']);
    $status=$Taxi->classes->Delete();

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


	$Taxi->classes->CreateModel();
	$Taxi->classes->model->columns_where->getId()->setValue($Main->GPC['object_id']);
	$Taxi->classes->model->columns_update->getStatus()->setValue($Main->GPC['value']);
	$status=$Taxi->classes->Update();

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
    'class_title' => TYPE_STR,
    'class_url' => TYPE_STR,
    'class_id' => TYPE_UINT,
    'class_status' => TYPE_NUM,
    'order' => TYPE_STR,
    'sort_filter'=>TYPE_BOOL
));

$variables=array();

$page_name='Классы';
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


$Taxi->classes->CreateModel();

if ($Main->GPC_exists['class_id'] and $Main->GPC['class_id']>0){

	$Taxi->classes->model->columns_where->getId()->setValue($Main->GPC['class_id']);
}

if ($Main->GPC_exists['class_status'] and $Main->GPC['class_status']!=-1){
	$Taxi->classes->model->columns_where->getStatus()->setValue($Main->GPC['class_status']);
	$variables['class_status']=$Main->GPC['class_status'];
}
else {
	$variables['class_status']=-1;
}

if ($Main->GPC_exists['class_title'] and $Main->GPC['class_title']!=''){
	$Taxi->classes->model->columns_where->getTitle()->setValue($Main->GPC['class_title']);
	$Taxi->classes->model->columns_where->getTitle()->setSearch(true);
}


$Paging->total=$Taxi->classes->GetTotal();

$Taxi->classes->model->setSelectField($Taxi->classes->model->getTableName().'.*');
$Taxi->classes->model->SetJoinImage('icon',$Taxi->classes->model->GetTableItemName('icon'));

$Taxi->classes->model->setOrderBy('class_sort');
$Taxi->classes->model->setCount($Paging->per_page);
$Taxi->classes->model->setStart($Paging->sql_start);


$Paging->data=$Taxi->classes->GetList();
$Paging->Display('taxi/manager/classes_table.twig',$variables);

<?php
$Main->user->PagePrivacy('admin');

$array =array();
if ($Main->GPC['action']=='delete') {
    $Main->input->clean_array_gpc('r', array(
        'object_id' => TYPE_UINT
    ));

    $city_info=$Taxi->reviews->GetItemFromId($Main->GPC['object_id']);
    $Taxi->reviews->CreateModel();
    $Taxi->reviews->model->columns_where->getId()->setValue($Main->GPC['object_id']);
    $status=$Taxi->reviews->Delete();

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
$array['classes'] = $Taxi->classes->getClasses();
$Paging = new ClassPaging($Main,20);
$Paging->show_per_page=true;

$Main->input->clean_array_gpc('r', array(
    'review_id'=>TYPE_STR,
    'review_uname'=>TYPE_STR,
    'review_uemail'=>TYPE_STR,
    'review_comment'=>TYPE_STR,
    'review_class'=>TYPE_STR,
    'review_rating'=>TYPE_STR,
    'review_time'=>TYPE_STR,
    'review_status'=>TYPE_UINT,

));

$Taxi->reviews->CreateModel();

$Taxi->reviews->model->setSelectField($Taxi->reviews->model->getTableName().".*");

if ($Main->GPC_exists['review_status'] and $Main->GPC['review_status']!=''){
    $Taxi->reviews->model->columns_where->getStatus()->setValue($Main->GPC['review_status']);
    $Taxi->reviews->model->columns_where->getStatus()->setSearch(true);
}
if ($Main->GPC_exists['review_time'] and $Main->GPC['review_time']!=''){
    $Taxi->reviews->model->columns_where->getTime()->setValue($Main->GPC['review_time']);
    $Taxi->reviews->model->columns_where->getTime()->setSearch(true);
}
if ($Main->GPC_exists['review_rating'] and $Main->GPC['review_rating']!=''){
    $Taxi->reviews->model->columns_where->getRating()->setValue($Main->GPC['review_rating']);
    $Taxi->reviews->model->columns_where->getRating()->setSearch(true);
}
if ($Main->GPC_exists['review_class'] and $Main->GPC['review_class']!=''){
    $Taxi->reviews->model->columns_where->getClass()->setValue($Main->GPC['review_class']);
    $Taxi->reviews->model->columns_where->getClass()->setSearch(true);
}
if ($Main->GPC_exists['review_uemail'] and $Main->GPC['review_uemail']!=''){
    $Taxi->reviews->model->columns_where->getUEmail()->setValue($Main->GPC['review_uemail']);
    $Taxi->reviews->model->columns_where->getUEmail()->setSearch(true);

}
if ($Main->GPC_exists['review_comment'] and $Main->GPC['review_comment']!=''){
    $Taxi->reviews->model->columns_where->getComment()->setValue($Main->GPC['review_comment']);
    $Taxi->reviews->model->columns_where->getComment()->setSearch(true);
}
if ($Main->GPC_exists['review_uname'] and $Main->GPC['review_uname']!=''){
    $Taxi->reviews->model->columns_where->getUName()->setValue($Main->GPC['review_uname']);
    $Taxi->reviews->model->columns_where->getUName()->setSearch(true);
}
if ($Main->GPC_exists['review_id'] and $Main->GPC['review_id']!=''){
    $Taxi->reviews->model->columns_where->getId()->setValue($Main->GPC['review_id']);
    $Taxi->reviews->model->columns_where->getId()->setSearch(true);
}
$Taxi->reviews->model->setOrderBy("review_id DESC");
$Taxi->reviews->model->setStart($Paging->sql_start);
$Taxi->reviews->model->setCount($Paging->per_page);
$Paging->total= $Taxi->reviews->GetTotal();

$Paging->data = $Taxi->reviews->GetList();

$page_name='Отзывы';

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
$array['status'] = true;

$Paging->Display('taxi/manager/reviews_table.twig',$array);
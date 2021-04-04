<?php

$Main->user->PagePrivacy();
$variables = array();
$variables['block'] = 'feed';
$variables['mod'] = '3';
$photo_input='review_icon';

if ($Main->GPC["action"]=='process_review'){
    $Main->input->clean_array_gpc('r', array(
        'class' => TYPE_UINT,
        'comfort' => TYPE_UINT,
        'driver' => TYPE_UINT,
        'clean' => TYPE_UINT,
        'price' => TYPE_UINT,
        'route_know' => TYPE_UINT,
        'uname' => TYPE_STR,
        'uemail' => TYPE_STR,
        'comment' => TYPE_STR,
        'review_icon' => TYPE_UINT
    ));

    if ($Main->GPC['review_icon']){
        $file = intval($Main->GPC['review_icon']);
    }
    else $file = 0;

    $rating = array();
    $error ='';

    if ($Main->GPC_exists['comfort'] and $Main->GPC_exists['driver'] and $Main->GPC_exists['clean'] and $Main->GPC_exists['price']and $Main->GPC_exists['route_know']) {

        $rating['comfort']=$Main->GPC['comfort'];
        $rating['driver']=$Main->GPC['driver'];
        $rating['clean']=$Main->GPC['clean'];
        $rating['price']=$Main->GPC['price'];
        $rating['route_know']=$Main->GPC['route_know'];
    }else{
        $error = "Вы не поставили оценку сервису!";
    }
    if ($error) {
        $Main->template->DisplayJson(array('status'=>false, 'text'=>$error));
    }
    else{
        $Taxi->reviews->CreateModel();
        if ($Main->GPC["uemail"]) {

        }
        $Taxi->reviews->model->columns_update->getUEmail()->setValue($Main->GPC["uemail"]);
        $Taxi->reviews->model->columns_update->getPhoto()->setValue($file);
        $Taxi->reviews->model->columns_update->getUName()->setValue($Main->GPC["uname"]);
        $Taxi->reviews->model->columns_update->getClass()->setValue($Main->GPC["class"]);
        $Taxi->reviews->model->columns_update->getComment()->setValue($Main->GPC["comment"]);
        $Taxi->reviews->model->columns_update->getRating()->setValue(array_sum($rating)/5);
        $id = $Taxi->reviews->Insert();
        $Taxi->reviews_rating->InsertRatings($rating, $id);

//    $array["ratings"] =$rating;
        $array['status'] = true;
        $array['result']=$Main->template->Render('frontend/components/modal-thx/modal-thx_1.twig', array());
        $Main->template->DisplayJson($array);
        exit;
    }

}

$Paging =new ClassPaging($Main,9,false,false);
$Paging->template='frontend/components/paging/paging.twig';
$Paging->show_per_page=true;

$Main->input->clean_array_gpc('r',array(
    'id'=>TYPE_UINT,
));

$Taxi->reviews->CreateModel();

$Taxi->reviews->model->setSelectField($Taxi->reviews->model->getTableName().".*, taxi_classes.*");
$Taxi->reviews->model->columns_where->getStatus()->setValue(2);
$Taxi->reviews->model->setJoin("LEFT JOIN taxi_classes ON taxi_reviews.review_class=taxi_classes.class_id");
$Taxi->reviews->model->SetJoinImage('icon', $Taxi->reviews->model->GetTableItemName('icon'));
if ($Main->GPC_exists['id'] and $Main->GPC['id'] == 1){
    $Taxi->reviews->model->setOrderBy("review_rating DESC");
}
if ($Main->GPC_exists['id'] and $Main->GPC['id'] == 2){
    $Taxi->reviews->model->setOrderBy("review_rating ASC");
}
if (!$Main->GPC_exists['id']){
    $Taxi->reviews->model->setOrderBy("review_id DESC");
}
$Taxi->reviews->model->setStart($Paging->sql_start);
$Taxi->reviews->model->setCount($Paging->per_page);

$Paging->total = $Taxi->reviews->GetTotal();
$Paging->data = $Taxi->reviews->GetList();

$page_name = 'Отзывы';

$breadcrumbs = array();
$breadcrumbs[] = array(
);
//$Taxi->cities->model->setStart($Paging->sql_start);
//$Taxi->cities->model->setCount($Paging->per_page);

$Main->template->SetPageAttributes(
    array(
        'title' => $page_name,
        'keywords' => '',
        'desc' => '',
        'header_image_url'=>'/reviews/'
    ),
    array(
        'breadcrumbs' => $breadcrumbs,
        'title' => $page_name
    )
);
$image_data1=array(
    'input_name'=>$photo_input,
    'module'=>'taxi',
    'show_select_image'=>true,
    'title'=>'Фото категорий',
    'folder'=>'reviews'
);
$variables['image_data']=$image_data1;

$Paging->Display('frontend/components/table-list/table-list.twig',$variables);

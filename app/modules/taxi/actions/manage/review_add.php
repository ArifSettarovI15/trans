<?php
$Main->user->PagePrivacy('admin');
$array = array();

$classes = $Taxi->classes->getClasses();


$array['classes'] = $classes;
$edit=0;
if ($Main->GPC['action']=='process_edit' && $Main->GPC['do']!='edit') {
    $Main->input->clean_array_gpc('r', array(
        'id' => TYPE_UINT
    ));
}


if ($Main->GPC['do']=='edit' OR $Main->GPC['action']=='process_edit') {
    $edit=1;
    $data_info=$Taxi->reviews->GetItemFromIdAdmin($Main->GPC['id']);
    if ($data_info) {
        $array['review'] = $data_info;
        $array['ratings'] = $Taxi->reviews_rating->GetRatings($Main->GPC['id']);
    }
    else {
        $Main->error->ObjectNotFound();
    }
}
if ($Main->GPC['action']=='process_review'){
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
        'file' => TYPE_UINT,
        'review_status'=>TYPE_UINT
    ));


    if ($Main->GPC['file']){
        $file =intval($Main->GPC['file']);
    }
    else $file = 0;

    $rating = array();
    $error ='';

    if ($Main->GPC_exists['comfort'] and $Main->GPC_exists['driver'] and $Main->GPC_exists['clean'] and $Main->GPC_exists['price']and $Main->GPC_exists['route_know']) {

        $rating['comfort']=(int)$Main->GPC['comfort'];
        $rating['driver']=(int)$Main->GPC['driver'];
        $rating['clean']=(int)$Main->GPC['clean'];
        $rating['price']=(int)$Main->GPC['price'];
        $rating['route_know']=(int)$Main->GPC['route_know'];
    }else{
        $error = "Заполните все поля!";
    }
    if ($error) {
        $Main->template->DisplayJson($error);
    }
    else{
        $Taxi->reviews->CreateModel();

        $Taxi->reviews->model->columns_update->getUEmail()->setValue($Main->GPC["uemail"]);
        if ($file) $Taxi->reviews->model->columns_update->getPhoto()->setValue($file);
        $Taxi->reviews->model->columns_update->getUName()->setValue($Main->GPC["uname"]);
        $Taxi->reviews->model->columns_update->getClass()->setValue($Main->GPC["class"]);
        $Taxi->reviews->model->columns_update->getComment()->setValue($Main->GPC["comment"]);
        $Taxi->reviews->model->columns_update->getRating()->setValue(array_sum($rating)/5);
        $Taxi->reviews->model->columns_update->getStatus()->setValue($Main->GPC['review_status']);
        $Taxi->reviews->model->columns_where->getId()->setValue($Main->GPC['id']);

        $result = $Taxi->reviews->Update();
        $result2 = $Taxi->reviews_rating->UpdateRatings($rating, $Main->GPC['id']);
        if ($result and $result2){
            $array['status'] = true;
            $array['text'] = 'Значение успешно обновлено';
            $Main->template->DisplayJson($array);
        }

    }
}

if ($edit==1) {
    $a_name='Редактировать';
}
else {
    $a_name='Добавить';
}

$page_name=$a_name.' отзыв';
$Main->template->SetPageAttributes(
    array(
        'title'=>$page_name,
        'keywords'=>'',
        'desc'=>''
    ),
    array(
        'breadcrumbs'=>array(
            array(
                'title'=>'Города',
                'link'=>BASE_URL.'/manager/taxi/cities/'
            ),
            array(
                'title'=>$a_name
            ),
        ),
        'title'=>$page_name
    )
);
$array['edit'] = $edit;
$Main->template->Display($array);
<?php

$Main->user->PagePrivacy('admin');
$categories = $Taxi->articles_categories->getCategories();


if ($Main->GPC['action']=='delete') {
    $Main->input->clean_array_gpc('r', array(
        'object_id' => TYPE_UINT
    ));

    $car_info=$Taxi->articles_categories->GetItemById($Main->GPC['object_id']);

    $Taxi->articles_categories->CreateModel();
    $Taxi->articles_categories->model->columns_where->getId()->setValue($Main->GPC['object_id']);
    $status=$Taxi->articles_categories->Delete();

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


$array= array();

$array['categories'] = $categories;
$Main->template->Display($array);

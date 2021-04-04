<?php

$Main->user->PagePrivacy('admin');
if ($Main->GPC['action']=='process_edit' && $Main->GPC['do']!='edit') {

    $Main->input->clean_array_gpc('r', array(
        'category_id' => TYPE_UINT
    ));
}
if ($Main->GPC['do']=='edit' OR $Main->GPC['action']=='process_edit') {

    $edit=1;

    $data_info=$Taxi->articles_categories->GetItemById($Main->GPC['id']);

    if ($data_info) {

    }
    else {

        $Main->error->ObjectNotFound();
    }
}
if ($Main->GPC['action']=='process_add'  OR $Main->GPC['action']=='process_edit') {

    $Main->input->clean_array_gpc('r', array(
        'category_id' => TYPE_UINT,
        'category_title' => TYPE_STR,
        'category_alias' => TYPE_STR,
    ));
    $error = '';
    $array = array();
    $Taxi->articles_categories->CreateModel();
    $Taxi->articles_categories->model->columns_where->getTitle()->setValue($Main->GPC['category_title']);
    $Taxi->articles_categories->model->columns_where->getId()->notValue($Main->GPC['category_id']);
    $check=$Taxi->articles_categories->GetItem();


    if ($check) {
        $error = 'Такая категория уже существует!';
    }
    else{
        $Taxi->articles_categories->CreateModel();
        $Taxi->articles_categories->model->columns_update->getTitle()->setValue($Main->GPC['category_title']);
        if ($Main->GPC['action'] == 'process_edit') {

            $Taxi->articles_categories->model->columns_update->getTitle()->setValue($Main->GPC['category_title']);
            $Taxi->articles_categories->model->columns_where->getId()->setValue($Main->GPC['category_id']);
            $result=$Taxi->articles_categories->Update();

            if ($result ) {
                $array['status'] = true;
                $array['text'] = 'Значение успешно обновлено';
            } else {
                $array['text'] = 'Ошибка обновления';
            }

        } else {
            $alias = $Taxi->articles_categories->model->columns_where->getAlias()->setValue($Main->GPC['category_alias']);
            if($alias){
                $array['status'] = true;
                $array['text'] = 'Ошибка обновления, ссылка не уникальна';
                $Main->template->DisplayJson($array);
            }
            else{
            $Taxi->articles_categories->model->columns_update->getAlias()->setValue($Main->GPC['category_alias']);
            $Taxi->articles_categories->Insert();

            $array['text'] = 'Значение успешно добавлено';

            $array['status'] = true;
            }
        }

    }
    $Main->template->DisplayJson($array);
}

if ($edit==1) {
    $a_name='Редактировать';
}
else {
    $a_name='Добавить';
}

$page_name=$a_name.' категорию';
$Main->template->SetPageAttributes(
    array(
        'title'=>$page_name,
        'keywords'=>'',
        'desc'=>''
    ),
    array(
        'breadcrumbs'=>array(
            array(
                'title'=>'Автопарк',
                'link'=>BASE_URL.'/manager/taxi/categories/'
            ),
            array(
                'title'=>$a_name
            ),
        ),
        'title'=>$page_name
    )
);
$array['info'] = $data_info;
$array['edit'] = $edit;
$Main->template->Display($array);

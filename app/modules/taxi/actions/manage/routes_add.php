<?php
$Main->user->PagePrivacy('admin');

$data_info=array();
$edit=0;
if ($Main->GPC['action']=='process_edit' && $Main->GPC['do']!='edit') {
    $Main->input->clean_array_gpc('r', array(
        'id' => TYPE_UINT
    ));
}


if ($Main->GPC['do']=='edit' OR $Main->GPC['action']=='process_edit') {
    $edit=1;
    $data_info=$Taxi->routes->GetItemById($Main->GPC['id'],1);
    if ($data_info) {

    }
    else {
        $Main->error->ObjectNotFound();
    }
}



if ($Main->GPC['action']=='process_add'  OR $Main->GPC['action']=='process_edit') {
    $Main->input->clean_array_gpc('r', array(
        'route_id' => TYPE_UINT,
        'route_start' => TYPE_UINT,
        'route_end' => TYPE_UINT,
        'text1' => TYPE_STR,
        'text2' => TYPE_STR
    ));

    $error='';
    $array=array();

    $Taxi->routes->CreateModel();
    $Taxi->routes->model->columns_where->getStart()->setValue($Main->GPC['route_start']);
    $Taxi->routes->model->columns_where->getEnd()->setValue($Main->GPC['route_end']);
    $Taxi->routes->model->columns_where->getId()->notValue($Main->GPC['route_id']);
    $check=$Taxi->routes->GetItem();
    
    if ($check) {
        $error = 'Такой маршрут уже есть';
    }
    else {
        $text_id1=$Main->text->SaveText($data_info['route_text_before'], $Main->GPC['text1']);
        $text_id2=$Main->text->SaveText($data_info['route_text_after'], $Main->GPC['text2']);

        $Taxi->routes->CreateModel();
        $Taxi->routes->model->columns_update->getStart()->setValue($Main->GPC['route_start']);
        $Taxi->routes->model->columns_update->getEnd()->setValue($Main->GPC['route_end']);
        $Taxi->routes->model->columns_update->getTextBefore()->setValue($text_id1);
        $Taxi->routes->model->columns_update->getTextAfter()->setValue($text_id2);

        if ($Main->GPC['action'] == 'process_edit') {
            $id=$Main->GPC['route_id'];

            $Taxi->routes->model->columns_where->getId()->setValue($Main->GPC['route_id']);
            $result=$Taxi->routes->Update();

            if ($result ) {
                $array['status'] = true;
                $array['text'] = 'Значение успешно обновлено';
            } else {
                $array['text'] = 'Ошибка обновления';
            }

        } else {
            $id=$Taxi->routes->Insert();
            $array['text'] = 'Значение успешно добавлено';
            //  $array['redirect'] = BASE_URL.'/manager/taxi/cities/edit/'.$id.'/';
            $array['status'] = true;
        }

    }



    if ($error!='') {
        $array['status']=false;
        $array['text']=$error;
    }
    else {
        $array['status']=true;
    }
    $Main->template->DisplayJson($array);
}

if ($edit==1) {
    $a_name='Редактировать';
}
else {
    $a_name='Добавить';
}

$page_name=$a_name.' маршрут';
$Main->template->SetPageAttributes(
    array(
        'title'=>$page_name,
        'keywords'=>'',
        'desc'=>''
    ),
    array(
        'breadcrumbs'=>array(
            array(
                'title'=>'Маршруты',
                'link'=>BASE_URL.'/manager/taxi/routes/'
            ),
            array(
                'title'=>$a_name
            ),
        ),
        'title'=>$page_name
    )
);

$Main->template->Display(array(
        'info'=>$data_info,
        'edit'=>$edit,
        'cities'=>$Taxi->cities->getCities(true)
    )
);

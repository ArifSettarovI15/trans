<?php
$Main->user->PagePrivacy('admin');

$photo_input='class_icon';
$data_info=array();
$edit=0;
if ($Main->GPC['action']=='process_edit' && $Main->GPC['do']!='edit') {
    $Main->input->clean_array_gpc('r', array(
        'id' => TYPE_UINT
    ));
}


if ($Main->GPC['do']=='edit' OR $Main->GPC['action']=='process_edit') {
    $edit=1;
    $data_info=$Taxi->classes->GetItemById($Main->GPC['id'],1);
    if ($data_info) {

    }
    else {
        $Main->error->ObjectNotFound();
    }
}



if ($Main->GPC['action']=='process_add'  OR $Main->GPC['action']=='process_edit') {
    $Main->input->clean_array_gpc('r', array(
	    'class_id' => TYPE_UINT,
        'class_places' => TYPE_UINT,
        'class_title' => TYPE_STR,
        'class_desc' => TYPE_STR,
	    'class_min_price'=>TYPE_UINT,
        'class_price_km'=>TYPE_UINT,
        $photo_input => TYPE_STR
    ));
    $photo_id=$Main->GPC[$photo_input];
    $error='';
    $array=array();


    $Taxi->classes->CreateModel();
    $Taxi->classes->model->columns_update->getTitle()->setValue($Main->GPC['class_title']);
    $Taxi->classes->model->columns_update->getDesc()->setValue($Main->GPC['class_desc']);
    $Taxi->classes->model->columns_update->getPlaces()->setValue($Main->GPC['class_places']);
    $Taxi->classes->model->columns_update->getMinPrice()->setValue($Main->GPC['class_min_price']);
    $Taxi->classes->model->columns_update->getPriceKm()->setValue($Main->GPC['class_price_km']);
    $Taxi->classes->model->columns_update->getPhotoId()->setValue($photo_id);

    if ($Main->GPC['action'] == 'process_edit') {
        $id=$Main->GPC['class_id'];

        $Taxi->classes->model->columns_where->getId()->setValue($Main->GPC['class_id']);
        $result=$Taxi->classes->Update();

        if ($result ) {
            $array['status'] = true;
            $array['text'] = 'Значение успешно обновлено';
        } else {
            $array['text'] = 'Ошибка обновления';
        }

    } else {
        $id=$Taxi->classes->Insert();
        $array['text'] = 'Значение успешно добавлено';
     //   $array['redirect'] = BASE_URL.'/manager/taxi/classes/edit/'.$id.'/';
        $array['status'] = true;
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

$page_name=$a_name.' класс';
$Main->template->SetPageAttributes(
    array(
        'title'=>$page_name,
        'keywords'=>'',
        'desc'=>''
    ),
    array(
        'breadcrumbs'=>array(
            array(
                'title'=>'Классы',
                'link'=>BASE_URL.'/manager/taxi/classes/'
            ),
            array(
                'title'=>$a_name
            ),
        ),
        'title'=>$page_name
    )
);

$image_data1=array(
    'input_name'=>$photo_input,
    'files'=>array(
        array(
            'file_id'=>$data_info['class_icon'],
            'icon_url'=>$data_info['class_icon_url']
        )
    ),
    'module'=>'taxi',
    'show_select_image'=>true,
    'title'=>'Фото категорий',
    'folder'=>'classes'
);



$Main->template->Display(array(
        'info'=>$data_info,
        'edit'=>$edit,
        'image_data1'=>$image_data1
    )
);

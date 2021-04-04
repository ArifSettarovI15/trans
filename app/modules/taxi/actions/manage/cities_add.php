<?php
$Main->user->PagePrivacy('admin');

$photo_input='city_icon';
$data_info=array();
$edit=0;
if ($Main->GPC['action']=='process_edit' && $Main->GPC['do']!='edit') {
    $Main->input->clean_array_gpc('r', array(
        'id' => TYPE_UINT
    ));
}


if ($Main->GPC['do']=='edit' OR $Main->GPC['action']=='process_edit') {
    $edit=1;
    $data_info=$Taxi->cities->GetItemById($Main->GPC['id'],1);
    if ($data_info) {

    }
    else {
        $Main->error->ObjectNotFound();
    }
}



if ($Main->GPC['action']=='process_add'  OR $Main->GPC['action']=='process_edit') {
    $Main->input->clean_array_gpc('r', array(
        'city_id' => TYPE_UINT,
        'city_title' => TYPE_STR,
        'city_url' => TYPE_STR,
        'city_coor' => TYPE_STR,
        'city_type' => TYPE_STR,
        'city_aliases'=>TYPE_STR,
        $photo_input => TYPE_UINT
    ));
    $photo_id=intval($Main->GPC[$photo_input]);

    $error='';
    $array=array();

    $Taxi->cities->CreateModel();
    $Taxi->cities->model->columns_where->getTitle()->setValue($Main->GPC['city_title']);
    $Taxi->cities->model->columns_where->getId()->notValue($Main->GPC['city_id']);
    $check=$Taxi->cities->GetItem();

    $Taxi->cities->CreateModel();
    $Taxi->cities->model->columns_where->getUrl()->setValue($Main->GPC['city_url']);
    $Taxi->cities->model->columns_where->getId()->notValue($Main->GPC['city_id']);
    $check2=$Taxi->cities->GetItem();

    if ($check) {
        $error = 'Такой город уже есть';
    }
    elseif ($check2) {
        $error = 'Такой url уже есть';
    }
    else {

        $Taxi->cities->CreateModel();
        $Taxi->cities->model->columns_update->getTitle()->setValue($Main->GPC['city_title']);
        $Taxi->cities->model->columns_update->getUrl()->setValue($Main->GPC['city_url']);
        $Taxi->cities->model->columns_update->getCoor()->setValue($Main->GPC['city_coor']);
	    $Taxi->cities->model->columns_update->getAliases()->setValue($Main->GPC['city_aliases']);
        $Taxi->cities->model->columns_update->getPhotoId()->setValue($photo_id);
        $Taxi->cities->model->columns_update->getType()->setValue($Main->GPC['city_type']);

        if ($Main->GPC['action'] == 'process_edit') {
            $id=$Main->GPC['city_id'];

            $Taxi->cities->model->columns_where->getId()->setValue($Main->GPC['city_id']);
            $result=$Taxi->cities->Update();

            if ($result ) {
                $array['status'] = true;
                $array['text'] = 'Значение успешно обновлено';
            } else {
                $array['text'] = 'Ошибка обновления';
            }

        } else {
            $id=$Taxi->cities->Insert();
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

$page_name=$a_name.' город';
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

$image_data1=array(
    'input_name'=>$photo_input,
    'files'=>array(
        array(
            'file_id'=>$data_info['city_icon'],
            'icon_url'=>$data_info['city_icon_url']
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

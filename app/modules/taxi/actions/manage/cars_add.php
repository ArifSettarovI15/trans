<?php

$Main->user->PagePrivacy('admin');

$photo_input='car_icon';
$data_info=array();
$edit=0;
if ($Main->GPC['action']=='process_edit' && $Main->GPC['do']!='edit') {

    $Main->input->clean_array_gpc('r', array(
        'id' => TYPE_UINT
    ));
}


if ($Main->GPC['do']=='edit' OR $Main->GPC['action']=='process_edit') {

    $edit=1;

    $data_info=$Taxi->cars->GetItemById($Main->GPC['id'],1);

    if ($data_info) {

    }
    else {

        $Main->error->ObjectNotFound();
    }
}



if ($Main->GPC['action']=='process_add'  OR $Main->GPC['action']=='process_edit') {

    $Main->input->clean_array_gpc('r', array(
        'car_id' => TYPE_UINT,
        'car_title' => TYPE_STR,
        'car_class' => TYPE_UINT,
        'car_power' => TYPE_UINT,
        'car_rent' =>TYPE_UINT,
        $photo_input => TYPE_UINT
    ));
    $photo_id=intval($Main->GPC[$photo_input]);

    $error='';
    $array=array();

    $Taxi->cars->CreateModel();
    $Taxi->cars->model->columns_where->getTitle()->setValue($Main->GPC['car_title']);
    $Taxi->cars->model->columns_where->getId()->notValue($Main->GPC['car_id']);
    $check=$Taxi->cars->GetItem();




    if ($check) {
        $error = 'Такой автомобиль уже есть';
    }
    else {
        $Taxi->cars->CreateModel();
        $Taxi->cars->model->columns_update->getTitle()->setValue($Main->GPC['car_title']);
        $Taxi->cars->model->columns_update->getClass()->setValue($Main->GPC['car_class']);
        $Taxi->cars->model->columns_update->getPower()->setValue($Main->GPC['car_power']);
        $Taxi->cars->model->columns_update->getPhotoId()->setValue($photo_id);
        $Taxi->cars->model->columns_update->getRent()->setValue($Main->GPC['car_rent']);




        if ($Main->GPC['action'] == 'process_edit') {
            $id=$Main->GPC['car_id'];
            $Taxi->cars_classes->newCarClass($id,$Main->GPC['car_class'],1);
            $Taxi->cars->model->columns_where->getId()->setValue($Main->GPC['car_id']);
            $result=$Taxi->cars->Update();
            $rent = $Taxi->rent_cars->GetItemByCarId($id);
            if ($Main->GPC['car_rent']){
                if (!$rent){
                    $Taxi->rent_cars->addNewRentCar($id);
                }
            }
            elseif($Main->GPC['car_rent']== ''){
                if ($rent){
                $Taxi->rent_cars->CreateModel();
                $Taxi->rent_cars->model->columns_where->getCarId()->setValue($Main->GPC['object_id']);
                $Taxi->rent_cars->Delete();
            }
            }

            if ($result ) {
                $array['status'] = true;
                $array['text'] = 'Значение успешно обновлено';
            } else {
                $array['text'] = 'Ошибка обновления';
            }

        } else {
            $id=$Taxi->cars->Insert();
            if ($Main->GPC['car_rent']){
                $Taxi->rent_cars->addNewRentCar($id);

            }
            $Taxi->cars_classes->newCarClass($id,$Main->GPC['car_class']);
            $array['text'] = 'Значение успешно добавлено';
            //  $array['redirect'] = BASE_URL.'/manager/taxi/cars/edit/'.$id.'/';
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

$page_name=$a_name.' автомобиль';
$classes = $Taxi->classes->getClassesSimple();
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
                'link'=>BASE_URL.'/manager/taxi/cars/'
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
            'file_id'=>$data_info['car_icon'],
            'icon_url'=>$data_info['car_icon_url']
        )
    ),
    'module'=>'taxi',
    'show_select_image'=>true,
    'title'=>'Фото категорий',
    'folder'=>'cars'
);



$Main->template->Display(array(
        'info'=>$data_info,
        'edit'=>$edit,
        'classes'=>$classes,
        'image_data1'=>$image_data1
    )
);

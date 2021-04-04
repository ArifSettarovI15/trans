<?php

$Main->user->PagePrivacy('admin');



$edit =0;
$array = array();


if ($Main->GPC['do']=='edit') {
    $edit=1;
}
if($Main->GPC['action']=='process_partner_edit')
{
    $Main->input->clean_array_gpc('r',array(
        'name' =>TYPE_STR,
        'addr'=>TYPE_STR,
        'phone'=>TYPE_STR,
        'user_type'=>TYPE_STR,
        'message'=>TYPE_STR
        ));
    $Main->GPC['phone'] = $res = preg_replace("/[^0-9]/", "", $Main->GPC["phone"] );
    if ($Main->GPC['id']){$data['id']=$Main->GPC['id'];}
    if ($Main->GPC['message']){$data['coment']=$Main->GPC['message'];}
    $data['uname'] = $Main->GPC['name'];
    $data['site'] = $Main->GPC['addr'];
    $data['phone'] = $Main->GPC['phone'];
    $data['type'] = $Main->GPC['user_type'];
    $result = $Taxi->partners->addPartner($data,$edit);
    if ($result){
        $array['status'] = true;
        $array['text'] = 'Значение успешно обновлено!';
        $Main->template->DisplayJson($array);
    }
    $Main->template->DisplayJson($data);
}

if ($edit==1) {
    $a_name='Редактировать';
}
else {
    $a_name='Добавить';
}

$page_name=$a_name.' партнера';
$Main->template->SetPageAttributes(
    array(
        'title'=>$page_name,
        'keywords'=>'',
        'desc'=>''
    ),
    array(
        'breadcrumbs'=>array(
            array(
                'title'=>'Партенеры',
                'link'=>BASE_URL.'/manager/taxi/partners/'
            ),
            array(
                'title'=>$a_name
            ),
        ),
        'title'=>$page_name
    )
);
$array['partner'] = $Taxi->partners->getPartnerById($Main->GPC['id']);

$array['edit'] = $edit;
$Main->template->Display($array);
<?php


$Main->user->PagePrivacy();

if ($Main->GPC['action'] == 'process_partner_add'){

    $Main->input->clean_array_gpc('r', array(
        'id' =>TYPE_UINT,
        'user_type' => TYPE_STR,
        'name' => TYPE_STR,
        'addr'=>TYPE_STR,
        'phone'=>TYPE_STR,
        'message'=>TYPE_STR,
    ));

    $data = array();
    $Main->GPC['phone'] = $res = preg_replace("/[^0-9]/", "", $Main->GPC["phone"] );
    if ($Main->GPC['id']){$data['id']=$Main->GPC['id'];}
    if ($Main->GPC['message']){$data['coment']=$Main->GPC['message'];}
    $data['uname'] = $Main->GPC['name'];
    $data['site'] = $Main->GPC['addr'];
    $data['phone'] = $Main->GPC['phone'];
    $data['type'] = $Main->GPC['user_type'];
    $result = $Taxi->partners->addPartner($data);
    if (!$result['text']){
        $array['status'] = $result['status'];
        $array['result'] = $Main->template->Render('frontend/components/modal-thx/modal-thx_2.twig',
                            array('phone'=>$data['phone']));
    }
    else{
        $array=$result;
    }
    $Main->template->DisplayJson($array);

}


$page_name = 'Сотрудничество';

$breadcrumbs = array();
$breadcrumbs[] = array(
    'title'=>$page_name
);

$Main->template->SetPageAttributes(
    array(
        'title' => $page_name,
        'keywords' => '',
        'desc' => '',
        'header_image_url'=>'/partners/'
    ),
    array(
        'breadcrumbs' => $breadcrumbs,
        'title' => $page_name
    )
);


$filter_s=array();
$filter_s['key']='partnership';
$fields=$SettingsClass->GetGroupValues($filter_s);

$array['partnership'] = $fields['partnership_add'];

$Main->template->Display($array);

<?php


$Main->user->PagePrivacy('user,admin');
//$Main->template->DisplayJson(array(
//    'status' =>true,
//    'text'=>'Данные профиля успешно обновлены'));




if ($Main->GPC['action'] == 'del_ava') {
    $sql = $Main->db->query_write("UPDATE users_profile SET profile_icon='', profile_icon_path='' WHERE profile_user_id=".$Main->db->sql_prepare($Main->user_info['user_id']));
    if ($sql){
        $Main->template->DisplayJson(array(
            'status' =>true,
            'text'=>'Данные профиля успешно обновлены'
        ));
    }
}
if ($Main->GPC['action'] == 'process_userImage') {

    $Main->input->clean_array_gpc('r', array(
        'filename' => TYPE_STR,
        'filepath' => TYPE_STR,
    ));
    $sql = $Main->db->query_write("UPDATE users_profile 
	SET profile_icon=".$Main->db->sql_prepare($Main->GPC['filename']).", 
    profile_icon_path=".$Main->db->sql_prepare($Main->GPC['filepath'])."
     WHERE profile_user_id=".$Main->db->sql_prepare($Main->user_info['user_id']));
    if ($sql){
        $Main->template->DisplayJson(array(
            'status' =>true,
            'text'=>'Данные профиля успешно обновлены',
            'filepath'=>$Main->GPC['filepath']
        ));
    }
    else{
        $Main->template->DisplayJson(array(
            'status' =>false,
            'text'=>'Ошибка обновления данных, попробуйте позже',));
    }
}
if ($Main->GPC['action'] == 'process_preferences') {
    $Main->input->clean_array_gpc('r', array(
        'auto_class' => TYPE_UINT,
        'user_services' => TYPE_STR,
    ));

    $sql = $Main->db->query_write("UPDATE users_profile 
	SET profile_car_class=".$Main->db->sql_prepare($Main->GPC['auto_class']).", 
    profile_car_services=".$Main->db->sql_prepare($Main->GPC['user_services'])."
    WHERE profile_user_id=".$Main->db->sql_prepare($Main->user_info['user_id']));

    if ($sql){
        $Main->template->DisplayJson(array(
            'status' =>true,
            'text'=>'Данные профиля успешно обновлены'
        ));
    }
    else{
        $Main->template->DisplayJson(array(
            'status' =>false,
            'text'=>'Ошибка обновления данных, попробуйте позже',));
    }

}


if ($Main->GPC['action'] == 'process_subscribes') {
    $Main->input->clean_array_gpc('r', array(
        'news_mes' => TYPE_BOOL,
        'tele_mess' => TYPE_BOOL,
        'sms_mess' => TYPE_BOOL,
    ));

    $sql = $Main->db->query_write("UPDATE users_profile 
SET profile_subscribed_akcii=".$Main->db->sql_prepare($Main->GPC['news_mes']).", 
profile_subscribed_telegram=".$Main->db->sql_prepare($Main->GPC['tele_mess']).", 
profile_subscribed_sms=".$Main->db->sql_prepare($Main->GPC['sms_mess'])." 
WHERE profile_user_id=".$Main->db->sql_prepare($Main->user_info['user_id']));

    if ($sql){
        $Main->template->DisplayJson(array(
            'status' =>true,
            'text'=>'Данные профиля успешно обновлены'
        ));
    }
    else{
        $Main->template->DisplayJson(array(
            'status' =>false,
            'text'=>'Ошибка обновления данных, попробуйте позже',));
    }

}
if ($Main->GPC['action'] == 'process_editName'){

    $Main->input->clean_array_gpc('r', array(
        'user_name' => TYPE_STR,
        'user_lastname' => TYPE_STR,
    ));
    $sql = $Main->db->query_write("UPDATE users_profile 
SET profile_name=".$Main->db->sql_prepare($Main->GPC['user_name']).", 
    profile_lastname=".$Main->db->sql_prepare($Main->GPC['user_lastname'])."
    WHERE profile_user_id=".$Main->db->sql_prepare($Main->user_info['user_id']));

    if ($sql){
        $Main->template->DisplayJson(array(
            'status' =>true,
            'text'=>'Данные профиля успешно обновлены'
        ));
    }
    else{
        $Main->template->DisplayJson(array(
            'status' =>false,
            'text'=>'Ошибка обновления данных, попробуйте позже',));
    }

}
if ($Main->GPC['action'] == 'process_editPhone'){
    $Main->input->clean_array_gpc('r', array(
        'phone' => TYPE_STR,
    ));
    $phone_user = preg_replace("/[^a-zA-Zа-яА-ЯЁё\d]/", "", $Main->GPC['phone']);
    $sql = $Main->db->query_write("UPDATE users_profile 
SET profile_phone=".$Main->db->sql_prepare($phone_user)."
WHERE profile_user_id=".$Main->db->sql_prepare($Main->user_info['user_id']));
    $Main->db->query_write("UPDATE `users`
        SET `user_login`=".$Main->db->sql_prepare($phone_user)."
        WHERE `user_login`=".$Main->db->sql_prepare($Main->user_info['user_login']));
    if ($sql){
    $Main->template->DisplayJson(array(
        'status' =>true,
        'text'=>'Данные профиля успешно обновлены'
    ));
    }
    else{
        $Main->template->DisplayJson(array(
            'status' =>false,
            'text'=>'Ошибка обновления данных, попробуйте позже',));
    }

}

$page_name = 'Профиль';

$breadcrumbs = array();
$breadcrumbs[] = array(
);

$Main->template->SetPageAttributes(
    array(
        'title' => $page_name,
        'keywords' => 'profile',
        'desc' => '',
        'header_image_url'=>'/cabinet/'
    ),
    array(
        'breadcrumbs' => $breadcrumbs,
        'title' => $page_name
    )
);


$Main->template->Display();

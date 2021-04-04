<?php
$Main->user->PagePrivacy('admin');
$array=array();
$array['edit'] =0;

if ($Main->GPC['do'] == 'edit')
{
    $array['edit']=1;
}
//$array['user'] = $Main->user->GetUserById($Main->GPC['id']);
if ($Main->GPC['action']=="process_edit"){
    $Main->input->clean_array_gpc('r', array(
        'profile_name' =>TYPE_STR,
        'profile_lastname'=>TYPE_STR,
        'profile_phone'=>TYPE_STR,
        'profile_user_email'=>TYPE_STR,
        'profile_car_class'=>TYPE_STR,
        'profile_car_services'=>TYPE_STR,
    ));
    $sql = $Main->db->query_write('UPDATE users_profile SET 
                    profile_name='.$Main->db->sql_prepare($Main->GPC['profile_name']).', 
                    profile_lastname='.$Main->db->sql_prepare($Main->GPC['profile_lastname']).', 
                    profile_phone='.$Main->db->sql_prepare($Main->GPC['profile_phone']).', 
                    profile_user_email='.$Main->db->sql_prepare($Main->GPC['profile_user_email']).', 
                    profile_car_class='.$Main->db->sql_prepare($Main->GPC['profile_car_class']).', 
                    profile_car_services='.$Main->db->sql_prepare($Main->GPC['profile_car_services']).'
                    WHERE profile_user_id='.$Main->GPC['id']);
    if ($sql){
        $Main->template->DisplayJson(array('status'=>true, "text"=>"Данные профиля успешно обновлены!"));
    }else{
        $Main->template->DisplayJson(array('status'=>false, "text"=>"Не удалось обновить данные профиля!"));
    }
}


$array['user_profile'] = $Main->user->GetUserProfile($Main->GPC['id']);
$array['classes'] = $Taxi->classes->getClasses();
$Main->template->Display($array);
<?php

if ($Main->GPC["action"]=='process_checkCode'){
    $Main->input->clean_array_gpc('r', array(
        'phone' => TYPE_STR,
        'policies'=>TYPE_BOOL
    ));
	if ($Main->GPC['policies']==false){
		$Main->template->DisplayJson(array('status'=>false, 'text'=>"Вы не поставили галочку согласия с \"Правилами сайта\""));
		exit;
	}

	$check_spam = $Main->db->query_first('SELECT COUNT(*) as count FROM taxi_spam
            WHERE spam_ip=' . $Main->db->sql_prepare($Main->input->fetch_alt_ip()) . ' AND
            spam_time>=' . $Main->db->sql_prepare(time() - 7200));
	if ($check_spam['count'] >= 15) {
		$Main->template->DisplayJson(array('status'=>false, 'text'=>'Ошибка заказа. Для заказа позвоните оператору'));
	}

    $Main->GPC['phone'] = $res = preg_replace("/[^0-9]/", "", $Main->GPC["phone"] );
    $code = rand(1000, 9999);
    $Main->db->query_write('UPDATE users_codes SET code_confirmed=1 WHERE code_user_phone='.$Main->db->sql_prepare($Main->GPC['phone']));
    $Main->db->query_write("INSERT INTO users_codes (code_user_phone, code_user_code) VALUES (" . $Main->db->sql_prepare($Main->GPC['phone']) . ", " . $Main->db->sql_prepare($code). ")");

	$Main->db->query_write('INSERT INTO taxi_spam (spam_time, spam_ip)
VALUES(
 ' . $Main->db->sql_prepare(TIMENOW) . ',
  ' . $Main->db->sql_prepare($Main->input->fetch_alt_ip()) . '
)');

    $sended = $Taxi->smsru->sendSms($Main->GPC['phone'], $code);

    if ($sended){
        $Main->template->DisplayJson(array('status'=>true));
    }
    else{
        $Main->template->DisplayJson(array('status'=>false, 'text'=>"Нам не удалось отправить сообщение на ваш номер, попробуйте позже!"));
    }

}

if ($Main->GPC["action"]=='process_callback'){
    $Main->input->clean_array_gpc('r', array(
        'name' => TYPE_STR,
        'phone' => TYPE_STR,
    ));
    $array = array();
    $Taxi->callbacks->CreateModel();
    $Taxi->callbacks->model->columns_update->getUName()->setValue($Main->GPC['name']);
    $Taxi->callbacks->model->columns_update->getPhone()->setValue($Main->GPC['phone']);
    $Taxi->callbacks->Insert();
    $array['status'] = true;
    $array['result']=$Main->template->Render('frontend/components/modal-thx/modal-thx_2.twig', array(
        'phone' => $Main->GPC['phone'],
        'form'=>true
    ));
    $Taxi->telegram_bot->sendMessageCallback("sendMessage",$Main->GPC['name'], $Main->GPC['phone']);
    $Main->template->DisplayJson($array);

}

if ($Main->GPC['action'] == "process_mailing"){

    $Main->input->clean_array_gpc('r', array(
        'email'=>TYPE_STR,
    ));
    if (!filter_var($Main->GPC['email'], FILTER_VALIDATE_EMAIL)) {
        $Main->template->DisplayJson(array('status'=>false, 'text'=> "Некорректный формат электронной почты"));
    }
    $array = array();
    if ($Main->GPC['email']) {

        $Taxi->mailing->CreateModel();
        $Taxi->mailing->model->columns_where->getEmail()->setValue($Main->GPC['email']);
        $check= $Taxi->mailing->GetItem();

        if (!filter_var($Main->GPC['email'], FILTER_VALIDATE_EMAIL)) {
            $Main->template->DisplayJson(array('status'=>false, 'text'=> "Некорректный формат электронной почты"));
        }

        if (!$check){
            $Taxi->mailing->model->columns_update->getEmail()->setValue($Main->GPC['email']);
            $Taxi->mailing->Insert();
            $array['status'] = true;
        }
        else{
            $array['status'] = true;
            $array['error'] = 'Вы уже подписаны на рассылку!';
        }
    }
    else{
        $array['status'] = false;
        $array['error'] = "Ошибка обработки данных!";
    }
//    $Main->template->DisplayJson($check);

    $array['result'] = $Main->template->Render('frontend/components/modal-thx/modal-thx.twig',array('email'=>$Main->GPC['email']));
    $Main->template->DisplayJson($array);
}

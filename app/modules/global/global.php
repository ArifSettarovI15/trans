<?php
$filter_s=array();
$filter_s['key']='about';
$filter_options['show_order']=true;
$Main->template->global_vars['fields']['about']=$SettingsClass->GetGroupValues($filter_s);

$url=$_SERVER['REQUEST_URI'];
$parts=explode('page/', $url);
$bb=explode('?', $parts[0]);
$Main->template->global_vars['page_link_main']=BASE_URL.$bb[0];
$header_images=array();
foreach($Main->template->global_vars['fields']['about']['breadcrumbs_images'] as $item){
    $header_images[$item['breadcrumb_page_url']] = $item['breadcrumb_image'];
}

$Main->template->global_vars['cities']=$Taxi->cities->getCities();
$Main->template->global_vars['classes']=$Taxi->classes->getClasses();
$Main->template->global_vars['cars']=$Taxi->cars->getCars();
$Main->template->global_vars['header_images']=$header_images;



$Main->input->clean_array_gpc('c', array(
	'cc_city_id' => TYPE_UINT,
));

if ($Main->GPC['cc_city_id']){
    $Main->template->global_vars['default_city'] = $Taxi->cities->GetItemById($Main->GPC['cc_city_id']);
}
else{
    $Main->template->global_vars['default_city'] = $Taxi->cities->GetItemByUrl('simferopol');
}
if ($Main->GPC['action']=='process_comment'){
    $error='';
    $Main->input->clean_array_gpc('r', array(
        'name'=>TYPE_STR,
        'text'=>TYPE_STR,
        'rules'=>TYPE_BOOL
    ));

    if ($Main->GPC['rules']==false) {
        $error='Нажмите согласие с правилами сайта';
        $array['error_field']='rules';
    }
    elseif ($Main->GPC['text']=='') {
        $error='Заполните отзыв';
        $array['error_field']='to';
    }
    elseif ($Main->GPC['name']=='') {
        $error='Введите имя';
        $array['error_field']='name';
    }
    else {
        $title='Отзыв';
        $mail_to=$Main->template->global_vars['fields']['about']['email_notify'];


        if ($mail_to!='') {

            $body = $Main->template->Render('static/email_write.twig',
                array(
                    'name'=> $Main->GPC['name'],
                    'text'=> $Main->GPC['text'],
                )
            );


            $aa=array($Main->config['system']['email_addr'] => $Main->template->global_vars['fields']['about']['about_title']);

            $message = (new Swift_Message($title))
                ->setFrom($aa)
                ->setTo([$mail_to])
                ->setBody($body, 'text/html')
            ;

            try{
                $result = $Main->mailer->send($message);
            }catch(\Swift_TransportException $e){
                $response = $e->getMessage() ;
            }
            //Если ваш отзыв не нарушает морально-этические нормы и служба модерации сочла его полезным для пользователей, он будет опубликован в течение 72 часов с момента получения.
            //$array['text'] = 'Наш менеджер свяжется с Вами в ближайшее время';
            $array['result']=$Main->template->Render('frontend/components/modal-done/modal-done-feed.twig',array(
                'name'=>$Main->GPC['name'].', спасибо за отзыв!',
                'mes'=>'После проверки менеджером на корректность, отзыв появится на сайте'
            ));
        }
        else {
            $error = 'Ошибка';
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
if ($Main->GPC['action']=='process_order') {

    $error = '';
    $Main->input->clean_array_gpc('r', array(
        'from' => TYPE_UINT,
        'router-length' => TYPE_STR,
        'to' => TYPE_UINT,
        'car_id' => TYPE_UINT,
        'phone' => TYPE_STR,
        'date' => TYPE_STR,
        'date2' => TYPE_STR,
        'time' => TYPE_STR,
        'passengers' => TYPE_STR,
        'services' => TYPE_STR,
        'reverse' => TYPE_BOOL,
        'comment' => TYPE_STR,
        'transfer' => TYPE_STR,
        'code' => TYPE_STR,
        'order_id' => TYPE_UINT,
        'price' => TYPE_UINT,
        'order_status' => TYPE_UINT,
        'number'=>TYPE_STR
    ));

	if ($Main->GPC['number']){
		$check_spam = $Main->db->query_first('SELECT COUNT(*) as count FROM taxi_spam
        WHERE spam_ip=' . $Main->db->sql_prepare($Main->input->fetch_alt_ip()) . ' AND
        spam_time>=' . $Main->db->sql_prepare(time() - 3600));
		if ($check_spam['count'] >= 15) {
			$error = 'Ошибка заказа. Для заказа позвоните оператору';
		}

		$Main->GPC['phone'] = $res = preg_replace("/[^0-9]/", "", $Main->GPC["phone"] );

		if (strlen($Main->GPC['phone'])>=10) {
			$error = 'Укажите полный номер';
		}
		else {
			$user_code = $Main->db->query_first('SELECT code_user_code FROM users_codes 
		WHERE code_confirmed=0 AND code_user_phone='.$Main->db->sql_prepare($Main->GPC['phone']));
			$user_code = $user_code['code_user_code'];
			if ($user_code != $Main->GPC['number']){
				$Main->template->DisplayJson(array('status'=>false,"text"=>"Вы ввели неверный код подтверждения!"));
				exit;
			}
		}

    }
    else{
        $Main->template->DisplayJson(array('status'=>false,"text"=>"Вы не ввели код подтверждения!"));
        exit;
    }


    $Main->db->query_write('UPDATE users_codes SET code_confirmed=1
	WHERE code_user_phone='.$Main->db->sql_prepare($Main->GPC['phone']));


    if ($error == '') {
	    if ( $Main->GPC['car_id'] == 0 ) {
		    $Main->GPC['car_id'] = 3;
	    }

	    if ( $Main->user_info ) {
		    $user = $Main->user_info;
	    } else {
		    $user = $Main->user->GetUserByLogin( $Main->GPC['phone'] );
	    }


	    $code = generateCode( 4 );
	    $Main->db->query_write( 'INSERT INTO taxi_orders
        (order_user_id,order_data, order_time, order_code, order_phone)
        VALUES(
        ' . $Main->db->sql_prepare( ( $Main->GPC["user_id"] ) ) . ',
        ' . $Main->db->sql_prepare( serialize( $Main->GPC ) ) . ',
        ' . $Main->db->sql_prepare( date( 'Y-m-d H:i:s' ) ) . ',
          ' . $Main->db->sql_prepare( $code ) . ',
            ' . $Main->db->sql_prepare( $Main->GPC['phone'] ) . '
        )' );

	    $order_id = $Main->db->insert_id();

	    $Main->db->query_write( "INSERT INTO taxi_orders_life (life_order_id, life_order_status) VALUES (" . $Main->db->sql_prepare( $order_id ) . ", 1)" );


	    $order               = $Taxi->orders->GetItemById( $order_id );
	    $order['order_data'] = unserialize( $order['order_data'] );

	    $cities = array();
	    $from   = $Taxi->cities->GetItemById( $order['order_data']['from'] );
	    $to     = $Taxi->cities->GetItemById( $order['order_data']['to'] );

	    $classes         = $Taxi->classes->getClasses();
	    $message_data    = array(
		    'change'     => '<b>Новый заказ <i>' . $order['order_id'] . '</i></b>',
		    'price'      => $order['order_data']['price'],
		    'type'       => 'Обычная',
		    'from'       => $from['city_title'],
		    'to'         => $to['city_title'],
		    'class'      => $classes[ $order['order_data']['car_id'] ]['class_title'],
		    'date'       => $order['order_data']['date'],
		    'date2'      => $order['order_data']['date2'],
		    'services'   => $order['order_data']['services'],
		    'passengers' => $order['order_data']['passengers'],
		    'phone'      => $order['order_data']['phone'],
		    'comment'    => $order['order_data']['comment'],
	    );
	    $status          = $Taxi->orders->GetSubmitedByUser( $Main->user_info['user_id'] );
	    $array['status'] = $status % 5;
	    $array['boned']  = floor( $status / 5 );
	    if ( $status % 5 == 4 ) {
		    $message_data['discount'] = "Это " . ( $status + 1 ) . " поездка клиента!";
	    }
	    $data = $Taxi->telegram_bot->sendMessage( "sendMessage", $message_data );


	    $title   = 'Заказ №' . $order_id.' - '.$Main->GPC['price'].' рублей';
	    $mail_to = $Main->template->global_vars['fields']['about']['email_notify'];


	    $body = $Main->template->Render( 'static/email_order.twig',
		    array(
			    'order_id'  => $order_id,
			    'to'        => $to['city_title'],
			    'from'      => $from['city_title'],
			    'phone'     => '+'.$Main->GPC['phone'],
			    'name'      => $Main->GPC['name'],
			    'class'      => $classes[ $order['order_data']['car_id'] ]['class_title'],
			    'date'      => date( 'd.m H:i', strtotime( $Main->GPC['date'] ) ),
			    'passengers' => $Main->GPC['passengers'],
			    'services'  => $Main->GPC['services'],
			    'date2'     => $Main->GPC['date2'],
			    'transfer'  => $Main->GPC['transfer'],
			    'comment'   => $Main->GPC['comment'],
			    'price'     => $Main->GPC['price'].' рублей',
			    'router_length'     => $Main->GPC['router-length'],
		    )
	    );


	    $aa = array( $Main->config['system']['email_addr'] => $Main->template->global_vars['fields']['about']['about_title'] );

	    $message = ( new Swift_Message( $title ) )
		    ->setFrom( $aa )
		    ->setTo( [ $mail_to ] )
		    ->setBody( $body, 'text/html' );

	    try {
		    $result = $Main->mailer->send( $message );
	    } catch ( \Swift_TransportException $e ) {
		    $response = $e->getMessage();
	    }


	    $gg = 'Спасибо за заявку!';

	    $array['result'] = $Main->template->Render( 'frontend/components/modal-thx/modal-thx_2.twig' );

    }

	if ( $error != '' ) {
		$array['status'] = false;
		$array['text']   = $error;
	} else {
		$array['status'] = true;
	}
	$Main->template->DisplayJson( $array );
}

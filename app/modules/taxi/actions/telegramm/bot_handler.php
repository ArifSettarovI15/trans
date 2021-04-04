<?php
/**
 * @var $Taxi
 */

$update_json = file_get_contents("php://input");
$command = '';
$update = json_decode($update_json, true);
$message = $update['message']['text'];
$chat_id = $update['chat']['id'];
if ($update){
    $message = $update['message']['text'];
    $chat_id = $update['message']['chat']['id'];
    $user_id = $update['message']['from']['id'];

    $last_action = $Taxi->telegram_history->getLastActionById($user_id);

    switch ($message){
        case '/start':
            $Taxi->telegram_bot->sendCustomMessage("Привет! Пока что я в разработке! Но скоро я смогу помочь тебе",$chat_id);
            $command = '/start';
            break;
        case 'Меню':
            $Taxi->telegram_bot->sendCustomMessage("Переключение на основное меню",$chat_id);
            $command = '/get_main_menu';
            break;
        case 'Отменить':
            $Taxi->telegram_bot->sendCustomMessage('Тут будет отмена заказа!',$chat_id);
            $command = '/cancel_order';
            break;
        case ($message==='Редактировать')?true:false:
            $Taxi->telegram_bot->sendMessageKeyboardOff('Пришлите мне ID заказа!',$chat_id);
            $command = '/edit_order';
            break;
        case ($message==='Последние заказы')?true:false:
            $orders = $Taxi->orders->GetLastOrdersTelegram();
            $Taxi->telegram_bot->sendLastOrders($orders,$chat_id);
            $command = '/last_orders';
            break;
        case (preg_match("/^[0-9]+$/", $message and $last_action=='/edit_order')?true:false):

            $order_check = $Taxi->orders->GetItemById($message);
            if ($order_check)
            { $message ='Редактирование заказа '.$message.'! \n';
                $order_data = unserialize($order_check['order_data']);
                $from= $Taxi->cities->GetItemById($order_data['from']);
                $to= $Taxi->cities->GetItemById($order_data['to']);
                $order_data['from'] = $from['city_title'];
                $order_data['to'] = $to['city_title'];
                $command = "order_".$message;
                $message.=$Taxi->telegram_bot->createMessage($order_data);
                $Taxi->telegram_bot->sendEditOrderMessage('Редактирование заказа '.$message.'!',$chat_id);
            }
            else{
                $Taxi->telegram_bot->sendEditOrderMessage('Не существует заказа с таким ID!',$chat_id);
            }
            break;
        case ($message==="Редактировать цену" and preg_match('/^order_/', $last_action)):
            $Taxi->telegram_bot->sendMessageKeyboardOff("Введите цену которую нужно установить",$chat_id);
            break;
        case (preg_match("/^[0-9]+$/", $message) and preg_match('/^order_/', $last_action))?true:false:
            $command = 'set_price';
            preg_match('/\d+$/', $last_action, $match);
            $updated = $Taxi->orders->ChangePriceTelegram($match[0], $message);
            if ($updated){
                $Taxi->telegram_bot->sendCustomMessage('Для заказа '.$match[0].' установлена цена '.$message.'р.',$chat_id);
            }
            else{
                $Taxi->telegram_bot->sendCustomMessage('Не удалось изменить цену!',$chat_id);
            }
            break;
        case ($message==='Пометить как выполненный' and preg_match('/^order_/', $last_action))?true:false:
            $command = 'set_completed';
            preg_match('/\d+$/', $last_action, $match);
            $updated = $Taxi->orders->ChangeStatusTelegram($match[0], 4);
            $updated =1;
            if ($updated){
                $Main->db->query_write('
                            INSERT INTO taxi_orders_life (life_order_id, life_order_status) 
                            VALUES(' . $match[0] . ', ' . 4 . ')');
                $Taxi->telegram_bot->sendCustomMessage('Для заказа '.$match[0].' установлен статус ВЫПОЛНЕН',$chat_id);
            }
            else{
                $Taxi->telegram_bot->sendCustomMessage('Не удалось изменить статус!',$chat_id);
            }
            break;
        case ($message==='Отменить заказ' and preg_match('/^order_/', $last_action))?true:false:
            $command = 'set_disabled';
            preg_match('/\d+$/', $last_action, $match);
            $updated = $Taxi->orders->ChangeStatusTelegram($match[0], 0);
            if ($updated){
                $Taxi->telegram_bot->sendCustomMessage('Заказ '.$match[0].' отменен',$chat_id);
                $Main->db->query_write('
                            INSERT INTO taxi_orders_life (life_order_id, life_order_status) 
                            VALUES(' . $match[0] . ', ' . 0 . ')');
            }
            else{
                $Taxi->telegram_bot->sendCustomMessage('Не удалось отменить заказ!',$chat_id);
            }
            break;
        default:
            $Taxi->telegram_bot->sendCustomMessage($last_action,$chat_id);

    }
    $Taxi->telegram_bot->deleteWebhook();
    $Taxi->telegram_bot->setWebhook();
}

//$orders = $Taxi->orders->GetLastOrdersTelegram();
//var_dump($Taxi->telegram_bot->sendLastOrders($orders,$chat_id));

if ($command and $command!=''){
    $Taxi->telegram_history->CreateModel();
    $Taxi->telegram_history->model->columns_update->getUserId()->setValue($user_id);
    $Taxi->telegram_history->model->columns_update->getCommand()->setValue($command);
    $Taxi->telegram_history->model->columns_update->getMessageData()->setValue($update_json);
    $Taxi->telegram_history->Insert();
}

$Main->error->PageNotFound();

<?php


class TelegramBot
{
    private $keyboard = ['keyboard' => [
                                        ['Редактировать'],
                                        ['Последние заказы'],
                                       ],
                         'resize_keyboard' => true,
                         'one_time_keyboard' => true,];

    private $keyboard_edit_order = ['keyboard' => [  ['Редактировать цену'],
                                                     ['Пометить как выполненный'],
                                                     ['Отменить заказ'],
                                                     ['Меню'],],
                                                    'resize_keyboard' => true,
                                                    'one_time_keyboard' => true,];

    public $token = "bot1340242412:AAHK-IixU5_EgmN3Y6UIeEojUkxeW67eNbE";
    public $chat_id = "247055233";
    public $parsed_string = '';////СДЕЛАТЬ ПРИВАТНЫМИ НА ПРОДАКШН
    /**
     * @var MainClass;
     */
    private $registry;

    /**
     * TelegramBot constructor.
     *
     * @param $registry MainClass
     */
    public function __construct($registry)
    {
        $this->registry = $registry;

        $filter_s = [];
        $filter_s['key'] = 'bot_email_data';
        $fields = $registry->settings->GetGroupValues($filter_s);

    $this->chat_id = $fields['telegram_chat_id'];
	$this->token = $fields['telegram_bot_api'];

    }

    public function sendMessageCallback($method, $name, $phone)
    {
        $message = "<b>Заказ на обратный звонок</b>
<b>Имя: </b>" . $name . "
<b>Телефон: </b>" . $phone;
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL            => 'https://api.telegram.org/' . $this->token . '/' . $method,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: multipart/form-data',
                ],
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => [
                    'chat_id'    => $this->chat_id,
                    "text"       => $message,
                    "parse_mode" => "html",

                ],
            ]
        );
        $data = curl_exec($curl);
        curl_close($curl);

        return $data;
    }

    public function sendStatusChange($method, $message_data)
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL            => 'https://api.telegram.org/' . $this->token . '/' . $method,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: multipart/form-data',
                ],
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => [
                    'chat_id'    => $this->chat_id,
                    "text"       => $message_data,
                    "parse_mode" => "html",

                ],
            ]
        );
        $data = curl_exec($curl);
        curl_close($curl);

        return $data;
    }

    public function sendDisableOrderMessage($method, $order_code)
    {
        $message = "Заказ " . $order_code . " отменен!";
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL            => 'https://api.telegram.org/' . $this->token . '/' . $method,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: multipart/form-data',
                ],
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => [
                    'chat_id'    => $this->chat_id,
                    "text"       => $message,
                    "parse_mode" => "html",
                ],
            ]
        );
        $data = curl_exec($curl);
        curl_close($curl);

        return $data;
    }

    public function sendCustomMessage($message, $chat_id)
    {
        $fields = [
            'chat_id'      => $chat_id,
            "parse_mode"   => "html",
            'text'         => $message,
            'reply_markup' => json_encode($this->keyboard),
        ];

    return $this->curlRequest($fields);
}
    public function sendEditOrderMessage($message, $chat_id)
        {
            $fields = [
                'chat_id'      => $chat_id,
                "parse_mode"   => "html",
                'text'         => $message,
                'reply_markup' => json_encode($this->keyboard_edit_order),
            ];

        $data = $this->curlRequest($fields);
        return $data ;
    }

    protected function curlRequest($fields = [])
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL            => 'https://api.telegram.org/' . $this->token . '/sendMessage',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: multipart/form-data',
                ],
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     =>$fields,
            ]
        );
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }

    public function sendMessageKeyboardOff($message, $chat_id)
    {
        $fields = [
            'chat_id'      => $chat_id,
            "parse_mode"   => "html",
            'text'         => $message,
            'remove_keyboard' => true,
        ];
        $data = $this->curlRequest($fields);

        return $data;
    }

    public function sendLastOrders($orders, $chat_id)
    {
        $message = '';
        foreach ($orders as $order) {
            $order['order_data'] = unserialize($order['order_data']);
            $message.="Заказ № <b>".$order['order_id']."</b>\n".
                      "Цена заказа: <b>". $order['order_data']['price']."р.</b>\n\n";
        }
        $fields = [
            'chat_id'      => $chat_id,
            "parse_mode"   => "html",
            'text'         => $message,
            'reply_markup' => json_encode($this->keyboard),
        ];
        $data = $this->curlRequest($fields);

        return $data;
    }


    public function sendMessage($method, $message_data)
    {
        $message = $this->createMessage($message_data);
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL            => 'https://api.telegram.org/' . $this->token . '/' . $method,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: multipart/form-data',
                ],
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => [
                    'chat_id'      => $this->chat_id,
                    "text"         => $message,
                    "parse_mode"   => "html",
                    "reply_markup" => $this->parsed_string,

                ],
            ]
        );
        $data = curl_exec($curl);
        curl_close($curl);

        return $data;
    }

    public function createMessage($messageData = [])
    {
        $message = $messageData['change'] . "
Стоимость: <b>" . $messageData['price'] . " рублей</b>
Тип поездки: <b>" . $messageData['type'] . "</b>
Маршрут: <b>" . $messageData['from'] . " - " . $messageData['to'] . "</b>
Класс машины: <b>" . $messageData['class'] . "</b>
Дата поездки: <b>" . $messageData['date'] . "</b>
Обратная дата: <b>" . $messageData['date2'] . "</b>
Требования: <b>" . $messageData['services'] . "</b>
Пассажиры: <b>" . $messageData['passengers'] . "</b>
Телефон: <b>+" . $messageData['phone'] . "</b>
Комментарий к заказу: <b>" . $messageData['comment'] . "</b>
";
        if ($messageData['discount'] != '') {
            $message .= "Скидка: <b>" . $messageData['discount'] . "</b>";
        }

        return $message;
    }

    public function setWebhook()
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL            => 'https://api.telegram.org/' . $this->token . '/setWebhook',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: multipart/form-data',
                ],
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => [
                    'url' => 'https://taxel82.ru/bot_handler/',
                ],
            ]
        );
        $data = curl_exec($curl);
        curl_close($curl);

        return $data;
    }

    public function deleteWebhook()
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL            => 'https://api.telegram.org/' . $this->token . '/deleteWebhook',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: multipart/form-data',
                ],
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => [
                    'drop_pending_updates' => 'true',
                ],
            ]
        );
        $data = curl_exec($curl);
        curl_close($curl);

        return $data;
    }
}

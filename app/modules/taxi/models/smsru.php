<?php
class TaxiSMSRu{
    public function sendSms($phone, $msg){
        $ch = curl_init("https://sms.ru/sms/send");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            "api_id" => "0877FB60-123D-911D-462C-F8B2B54F1343",

            "to" => $phone, // До 100 штук до раз
            "msg" => $msg, // Если приходят крякозябры, то уберите iconv и оставьте только "Привет!",
            /*
            // Если вы хотите отправлять разные тексты на разные номера, воспользуйтесь этим кодом. В этом случае to и msg нужно убрать.
            "multi" => array( // до 100 штук за раз
                "79781297353"=> iconv("windows-1251", "utf-8", "Привет 1"), // Если приходят крякозябры, то уберите iconv и оставьте только "Привет!",
                "74993221627"=> iconv("windows-1251", "utf-8", "Привет 2")
            ),
            */
            "json" => 1, // Для получения более развернутого ответа от сервера
            "from"=>"Taxel82",
        )));
        $body = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($body);
        if ($json) { // Получен ответ от сервера
            if ($json->status == "OK") { // Запрос выполнился
                return 1;
            } else { // Запрос не выполнился (возможно ошибка авторизации, параметрах, итд...)
                return 0;
            }
        } else {
            return 0;

        }
    }
}
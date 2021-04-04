<?php
$Main->user->PagePrivacy("admin");

if ($Main->GPC['action']=='delete') {
    $Main->input->clean_array_gpc('r', array(
        'object_id' => TYPE_UINT
    ));

    $status=$Main->db->query_write("DELETE FROM taxi_rent_requests WHERE rent_req_id=".$Main->GPC['object_id']);

    $array=array();

    if ($status) {
        $array['status']=true;
        $array['text']='Объект успешно удален';
    }
    else {
        $array['status']=false;
        $array['text']='Ошибка удаления объекта';
    }
    $Main->template->DisplayJson($array);
}


$variables = array();
$Paging= new ClassPaging($Main, 50);
$Paging->show_per_page= true;

$Taxi->rent_requests->CreateModel();
$Taxi->rent_requests->model->setSelectField($Taxi->rent_requests->model->getTableName().".*, taxi_cars.*");


$Taxi->rent_requests->model->setJoin('LEFT JOIN taxi_cars ON taxi_rent_requests.rent_req_car_id = taxi_cars.car_id');
$Taxi->rent_requests->model->setOrderBy("rent_req_time DESC");
$Taxi->rent_requests->model->setStart($Paging->sql_start);
$Taxi->rent_requests->model->setCount($Paging->per_page);



$Paging->total = $Taxi->rent_requests->GetTotal();
$Paging->data = $Taxi->rent_requests->GetList();
//$Main->template->DisplayJson($Paging->data);

$Paging->Display('taxi/manager/rent_requests_table.twig', $variables);
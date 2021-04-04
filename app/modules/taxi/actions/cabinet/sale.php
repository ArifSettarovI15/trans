<?php
$Main->user->PagePrivacy('user,admin');

$status = $Taxi->orders->GetSubmitedByUser($Main->user_info['user_id']);

$array=array();
$page_name = 'Скидка';

$breadcrumbs = array();
$breadcrumbs[] = array(
);

$Main->template->SetPageAttributes(
    array(
        'title' => $page_name,
        'keywords' => 'sale',
        'desc' => '',
        'header_image_url'=>'/cabinet/'
    ),
    array(
        'breadcrumbs' => $breadcrumbs,
        'title' => $page_name
    )
);

$Main->template->Display($array);

<?php

$page_name='Вопрос-Ответ';




$filter_s=array();
$filter_s['key']='faq';
$filter_s['order']='sort';
$fields= $SettingsClass->GetGroupValues($filter_s);

//

$breadcrumbs=array();
$breadcrumbs[]=array(
);
$array = array();
$array['fields'] = $fields;
//$array['articles'] = $articles;

$Main->template->SetPageAttributes(
    array(
        'title'=>$page_name,
        'keywords'=>'',
        'desc'=>'',
        'header_image_url'=>'/faq/',
    ),
    array(
        'breadcrumbs'=>$breadcrumbs,
        'title'=>$page_name,
    )
);

$Main->template->Display($array);

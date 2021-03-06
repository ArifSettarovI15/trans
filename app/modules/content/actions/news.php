<?php

$Main->user->PagePrivacy();
$Main->input->clean_array_gpc('r', array(
	'filters'=>TYPE_ARRAY
));
$Main->GPC['id'] =$Main->GPC['content_type'];

$page_name='Статьи';
if ($Main->GPC['content_type']=='news') {
	$page_name='Новости';
}
elseif ($Main->GPC['content_type']=='routes') {
	$page_name='Маршруты';
}
elseif ($Main->GPC['content_type']=='hotels') {
	$page_name='Гостиницы';
}
elseif ($Main->GPC['content_type']=='places') {
	$page_name='Места';
}
$meta_title=$page_name;
$keywords='';
$desc='';
$breadcrumbs = array();
$breadcrumbs[]=[
    'title'=>'Блог'
];



$variables['content_type']=$Main->GPC['content_type'];
$variables['adv']=$SettingsClass->getAdv();
$variables['block'] = 'article';
$variables['categories'] =  $Taxi->articles_categories->getCategoriesWichHasArticles();
$variables['isArticles'] = true;
$variables['where_go'] = $Taxi->cities->getPlacesPublicSortByViews();

$Paging =new ClassPaging($Main,16,false,true);
$Paging->template='frontend/components/paging/paging.twig';
$Paging->template2='frontend/components/paging/paging.twig';
$Paging->template3='frontend/components/paging/paging.twig';


if ($Main->GPC['content_type']!='blog'){
    $Main->GPC['id']=$Main->GPC['content_type'];
}

if (!$Main->GPC['id']) {
	$Main->input->clean_array_gpc('r',array('id'=>TYPE_STR));
}

if ($Main->GPC['id']==''){
    $Main->GPC['id']='all';
}
if ($Main->GPC['id']){
    $variables['content_type']=$Main->GPC['id'];
//    $Main->template->DisplayJson($Main->GPC['id']);
    $Paging->data = $ContentClass->getArticles($Main->GPC['id'],$Paging->sql_start, $Paging->per_page);
}
else{
    $Paging->data = $ContentClass->getArticles('all',$Paging->sql_start, $Paging->per_page);
}
$Paging->total=$ContentClass->GetPublicationsTotal($Main->GPC['id']);


$breadcrumbs = array();
$breadcrumbs[] = array(
);
$variables["active_tab"] = $Main->GPC['id'];
$Main->template->SetPageAttributes(
    array(
        'breadcrumbs' => $breadcrumbs,
        'title' => $page_name,
        'keywords' => $Main->GPC["id"],
        'desc' => '',
        'header_image_url'=>'/blog/'
    ),
    array(
        'breadcrumbs' => $breadcrumbs,
        'title' => $page_name
    ));

if (!$Paging->total){
    $Main->template->Display(array('no_items'=>true));exit;
}


$Paging->Display('frontend/components/table-list/table-list.twig',$variables);

<?php

$Main->user->PagePrivacy();


$page_name='О компании';

$breadcrumbs=array();
$breadcrumbs[] = [
    'title'=>$page_name
];


$Main->template->SetPageAttributes(
    array(
        'title'=>$page_name,
        'keywords'=>'',
        'desc'=>'',
        'header_image_url'=>'/about/'
    ),
    array(
        'breadcrumbs'=>$breadcrumbs,
        'title'=>$page_name
    )
);

$filter_s=array();
$filter_s['key']='mainpage';
$fields=$SettingsClass->GetGroupValues($filter_s);
$advantages = $fields["mainpage_advantages"];
$steps = $fields["mainpage_steps"];


$filter_s=array();
$filter_s['key']='company';
$fields=$SettingsClass->GetGroupValues($filter_s);

$classes = $Taxi->classes->getClasses();
$reviews = $Taxi->reviews->getReviewsPublicForSlider(6);


$array['steps'] = $steps;
$array['advantages'] = $advantages;
$array['reviewsForSlider'] = $reviews;
$array['classes'] = $classes;
$array['about'] = $fields;

$Main->template->Display($array);

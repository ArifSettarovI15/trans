<?php

$Main->user->PagePrivacy();

$Main->template->SetPageAttributes(
    array(
        'title' => 'Taxel'
    )
);


//Возвращает авто + класс (только те что с фотографией)
$carsForSlider = $Taxi->cars->getCarsPublicWithPhotosOnly( 0,6);


//Возвращает отзывы сортированные по рейтингу вниз
$reviews = $Taxi->reviews->getReviewsPublicForSlider(6);

$filter_s=array();
$filter_s['key']='mainpage';
$fields=$SettingsClass->GetGroupValues($filter_s);
$main_slider = $fields["mainpage_slider"];
$advantages = $fields["mainpage_advantages"];
$steps = $fields["mainpage_steps"];

//Возвращает локации (city_type=3) сортированные по просмотрам
$where_go = $Taxi->cities->getPlacesPublicSortByViews();
//Возвращает публикации сортированные по просмотрам
$articles = $ContentClass->getTaxiLastArticles();

$Main->template->Display(
    array(
    	'home'=>true,
        'carsForSlider'=>$carsForSlider,
        'main_slider'=>$main_slider,
        'advantages'=>$advantages,
        'steps'=>$steps,
        'reviewsForSlider'=>$reviews,
        'where_go'=>$where_go,
        'articles'=>$articles
    )
);

<?php
$Main->user->PagePrivacy();


$array = array();
$photo_input='review_icon';

if ($Main->GPC["id"]){
	$review = $Taxi->reviews->GetItemFromId($Main->GPC['id']);
	if ($review==false or $review['review_status']!=2) {
		$Main->error->PageNotFound();
	}

	$array['review'] =$review;
	$array['classes'] = $Taxi->classes->getClasses();
	$array['date']['DM'] = date("d ".RuMonth(date('n',strtotime($array['review']['review_time'])),2),strtotime($array['review']['review_time']));
	$array['date']['Y'] = date("Y",strtotime($array['review']['review_time']));
	$array['reviewsForSlider'] = $Taxi->reviews->getReviewsPublicForSlider(6);
	$array['ratings'] = $Taxi->reviews_rating->GetRatings($Main->GPC["id"]);
}
else{
   $Main->error->PageNotFound();
}


$page_name = $array['review']['review_uname'].' о такси Taxel | Отзыв №'.$review['review_id'];

//$breadcrumbs = array();
$breadcrumbs[] = array(
	'title'=>'Отзывы',
	'link'=>BASE_URL.'/reviews/'

);

$Main->template->SetPageAttributes(
    array(
        'title' => $page_name,
        'keywords' => '',
        'desc' => '',
        'header_image_url'=>'/reviews/'
    ),
    array(
        'breadcrumbs' => $breadcrumbs,
        'title' => $page_name
    )
);
$image_data1=array(
    'input_name'=>$photo_input,
    'files'=>array(
        array(
            'file_id'=>$review['review_icon'],
            'icon_url'=>$review['review_icon_url']
        )
    ),
    'module'=>'taxi',
    'show_select_image'=>true,
    'title'=>'Фото отзывов',
    'folder'=>'cars'
);
$array['image_data1'] = $image_data1;

$Main->template->Display($array);

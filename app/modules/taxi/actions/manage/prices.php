<?php
$Main->user->PagePrivacy('admin');

if ($Main->GPC['id']) {
    $from_info=$Taxi->cities->GetItemById($Main->GPC['id'],1);
    if ($from_info) {

    }
    else {
        $Main->error->ObjectNotFound();
    }
}

if ($Main->GPC['action']=='mass-price') {
	$Main->input->clean_array_gpc('r', array(
		'price_change'=>TYPE_INT,
		'price_type'=>TYPE_STR,
		'price_class_id'=>TYPE_UINT,
	));

	$prices=$Taxi->prices->getPricesFrom($Main->GPC['id']);


	foreach ($prices as $from_city_id=>$d1) {
		foreach ($d1 as $to_city_id=>$d2) {
			foreach ($d2['classes'] as $class_id=>$price) {
				if ($Main->GPC['price_class_id']==0 or $Main->GPC['price_class_id']==$class_id) {
						if ($price['price_value']!=0) {
							$new_price=$price['price_value'];

							if ($Main->GPC['price_type']=='value') {
								$new_price=$price['price_value']+$Main->GPC['price_change'];
							}
							elseif ($Main->GPC['percent']=='value') {
								if ($Main->GPC['price_change']<0) {
									$new_price=$price['price_value']*((100+$Main->GPC['price_change'])/100);
								}
								else {
									$new_price=$price['price_value']/(((100-$Main->GPC['price_change'])/100));
								}

							}

							$Taxi->prices->CreateModel();
							$Taxi->prices->model->columns_update->getValue()->setValue($new_price);
							$Taxi->prices->model->columns_where->getId()->setValue($price['price_id']);
							$Taxi->prices->Update();
						}






				}
			}
		}
	}

	$array=array();
	$array['status']=true;
	$array['reload']=true;
	$array['text']='Цены обновлены';
	$Main->template->DisplayJson($array);
}


if ($Main->GPC['action']=='set_price_value') {
    $Main->input->clean_array_gpc('r', array(
        'start'=>TYPE_UINT,
        'end'=>TYPE_UINT,
        'class'=>TYPE_UINT,
        'value'=>TYPE_UINT
    ));


    $Taxi->routes->CreateModel();
    $Taxi->routes->model->columns_where->getStart()->setValue($Main->GPC['start']);
    $Taxi->routes->model->columns_where->getEnd()->setValue($Main->GPC['end']);
    $check=$Taxi->routes->GetItem();
    if ($check==false) {
        $Taxi->routes->CreateModel();
        $Taxi->routes->model->columns_update->getStart()->setValue($Main->GPC['start']);
        $Taxi->routes->model->columns_update->getEnd()->setValue($Main->GPC['end']);
        $route_id=$Taxi->routes->Insert();
    }
    else {
        $route_id=$check['route_id'];
    }


    $Taxi->prices->CreateModel();
    $Taxi->prices->model->columns_where->getRouteId()->setValue($route_id);
    $Taxi->prices->model->columns_where->getClassId()->setValue($Main->GPC['class']);
    $check=$Taxi->prices->GetItem();
    if ($check==false) {
        $Taxi->prices->CreateModel();
        $Taxi->prices->model->columns_update->getRouteId()->setValue($route_id);
        $Taxi->prices->model->columns_update->getClassId()->setValue($Main->GPC['class']);
        $price_id=$Taxi->prices->Insert();
    }
    else {
        $price_id=$check['price_id'];
    }

    $Taxi->prices->CreateModel();
    $Taxi->prices->model->columns_update->getValue()->setValue($Main->GPC['value']);
    $Taxi->prices->model->columns_where->getId()->setValue($price_id);
    $Taxi->prices->Update();

    $array=array();
    $array['status']=true;
    $array['text']='Статус обновлен';
    $Main->template->DisplayJson($array);
}

/////////////////////////////////

$variables=array();

$page_name='Тарифы';
if ($from_info) {
    $page_name.=' из '.$from_info['city_title'];
}
$Main->template->SetPageAttributes(
    array(
        'title'=>$page_name,
        'keywords'=>'',
        'desc'=>''
    ),
    array(
        'breadcrumbs'=>array(
            array(
                'title'=>$page_name
            )
        ),
        'title'=>$page_name
    )
);

$prices=array();
if ($Main->GPC['id']) {
   $prices=$Taxi->prices->getPricesFrom($Main->GPC['id']);
}

$Main->template->Display(
    array(
        'classes'=>$Taxi->classes->getClasses(),
        'cities'=>$Taxi->cities->getCities(),
        'from_info'=>$from_info,
        'prices'=>$prices
    ));

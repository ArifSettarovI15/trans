<?php
set_time_limit(0);

//up content
$items=$ContentClass->GetContentList(array(),'all');
foreach ($items as $item) {
	$views=$ContentClass->GetContentViews($item['content_id']);
	$ContentClass->UpdateContentItemViews($item['content_id'],$views);
}


$filter_options=array();
$filter_options['order']='date';
$filter_options['show_order']=true;
$filter_options['skip_date']=true;


$items=$ContentClass->GetContentList($filter_options,0,50000);

$sitemap='<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="gss.xsl"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

foreach ($items as $item) {
	$sitemap.='<url>
<loc>'.$item['content_full_url'].'</loc>
</url>';
}
$sitemap.='</urlset>';

$sitemap_turbo='<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" xmlns:turbo="http://turbo.yandex.ru" version="2.0">
    <channel>
        <title>Блог Taxel</title>
        <link>https://taxel82.ru/</link>
        <language>ru</language>
        <description>Интересные материалы о Крыме</description>';

$filter_options=array();
$filter_options['order']='date';
$filter_options['show_order']=true;
$filter_options['skip_date']=true;

function cleanD($d) {
	// $d=str_replace('«','&laquo;',$d);
	// $d=str_replace('»','&raquo;',$d);
	$d=str_replace('"','&quot;',$d);
	$d=str_replace("'",'&apos;',$d);
	return $d;
}

$items=$ContentClass->GetContentList($filter_options,1000);

$sitemap='<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="gss.xsl"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

foreach ($items as $item) {
	if ($item['content_type']!='pages') {
		$content=$ContentClass->GetContentById($item['content_id'],1);

		$rss_date = gmdate(DATE_RFC822, $item['content_time']);

		$desc=$content['content_text'];
		$desc=str_replace('<br /></p>','</p>',$desc);
		$desc=str_replace('<br /></p>','</p>',$desc);
		$desc = preg_replace("/<\/?div[^>]*\>/i", "", $desc);
		preg_match_all("#<video(.*)</video>#Us", $desc, $res);
		foreach ($res[0] as $line) {
			$desc=str_replace($line,'<figure>'.$line.'</figure>',$desc);
		}
		$desc=str_replace('href="/','href="'.BASE_URL.'/',$desc);
		preg_match_all("#<img(.*)>#Us", $desc, $res);
		foreach ($res[0] as $line) {
			$desc=str_replace($line,'<figure>'.$line.'</figure>',$desc);
		}

		$desc=cleanD($desc);
		$item['content_title']=cleanD($item['content_title']);

		$desc=html_entity_decode(htmlspecialchars_decode($desc));
		$desc = preg_replace('/[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]'.
		                     '|[\x00-\x7F][\x80-\xBF]+'.
		                     '|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*'.
		                     '|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})'.
		                     '|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|(?![\x80-\xBF]{2})|[\x80-\xBF]{3,})/S',
			'', $desc );

		$sitemap.='<url>
<loc>'.$item['content_full_url'].'</loc>
</url>';


		$sitemap_turbo.='<item turbo="true">
            <link>'.$item['content_full_url'].'</link>
            <author>Ефчуд</author>
            <turbo:source>'.$item['content_full_url'].'</turbo:source>
		    <turbo:topic>'.$item['content_title'].'</turbo:topic>
		    <pubDate>'.$rss_date.'</pubDate>
            <category>'.$item['cat_title'].'</category>
            <turbo:content>
                <![CDATA[
                    <header>
                        <h1>'.$item['content_title'].'</h1>
                        <figure>
                            <img src="'.$item['thumb'].'">
                        </figure>
                    </header>
                       <button
                          formaction="https://taxel82.ru"
                          data-background-color="#674fc1"
                          data-color="white"
                          data-turbo="false"
                          data-primary="true"
                        >
                        taxel82.ru
                        </button>
                    '.$desc.'
                    <button
                          formaction="tel:+7(978)702-55-11"
                          data-background-color="#3dc9ba"
                          data-color="white"
                          data-turbo="false"
                          data-primary="true"
                        >
                           +7(978)702-55-11

                        </button>
                ]]>
            </turbo:content>
        </item>';
	}

}
$sitemap_turbo.='</channel>
</rss>';


file_put_contents(ROOT_DIR.'/data/rss_turbo.rss',$sitemap_turbo);
$sitemap.='</urlset>';
file_put_contents(ROOT_DIR.'/sitemap_articles.xml',$sitemap);



$al_array=array();

$Taxi->cities->CreateModel();
$data=$Taxi->cities->GetList();

foreach ($data as $item) {
	if ($item['city_aliases']) {
		$bb=explode(',',$item['city_aliases']);

		foreach ($bb as $b) {
			$key=mb_strtolower(trim($b));
			$al_array[$key]=mb_strtolower($item['city_title']);

			/*$Taxi->cities->CreateModel();
			$Taxi->cities->model->columns_where->getTitle()->setValue($b);
			$check=$Taxi->cities->GetItem();
			if ($check) {
				$Taxi->routes->CreateModel();
				$Taxi->routes->model->columns_where->getStart()->setValue($check['city_id']);
				$Taxi->routes->Delete();

				$Taxi->routes->CreateModel();
				$Taxi->routes->model->columns_where->getEnd()->setValue($check['city_id']);
				$Taxi->routes->Delete();

				$Taxi->cities->CreateModel();
				$Taxi->cities->model->columns_update->getStatus()->setValue(false);
				$Taxi->cities->model->columns_where->getId()->setValue($check['city_id']);
				$Taxi->cities->Update();
			}
			*/
		}
	}
}

$content=load_url('https://taxi-alex24.ru/price');


preg_match_all("#bem='{\"menu__item\":{\"val\":\"(.*)\"}}' role=\"option\" id=\"(.*)\" aria-checked=\"(.*)\">(.*)</div>#Us", $content, $res2);

foreach ($res2[4] as $title) {
	$title=trim($title);
	if (isset($al_array[mb_strtolower($title)])) {
		$title=$al_array[mb_strtolower($title)];
	}

	$Taxi->cities->CreateModel();
	$Taxi->cities->model->columns_where->getTitle()->setValue($title);
	$check=$Taxi->cities->GetItem();

	$Taxi->cities->CreateModel();
	$Taxi->cities->model->columns_where->getUrl()->setValue(translit_url_safe($title));
	$check2=$Taxi->cities->GetItem();

	if ($check) {
		$city_id=$check['city_id'];
	}
	elseif ($check2) {
		$city_id=$check2['city_id'];
	}
	else {
		$coor='';
		$uu='https://search-maps.yandex.ru/v1/?type=geo&apikey=9bd63b22-4f68-406c-87d2-c6eaa1fedfee&text='.urlencode($title).'&bbox=24.978231949822558,43.92460248885024~39.98555616857254,48.8100142259997&rspn=1&results=10&lang=ru_RU';

		$content=file_get_contents($uu);
		$data=json_decode($content);

		foreach ($data->features as $line) {
			$coor = $line->geometry->coordinates[1] . ',' . $line->geometry->coordinates[0];
		}

		$Taxi->cities->CreateModel();
		$Taxi->cities->model->columns_update->getTitle()->setValue($title);
		$Taxi->cities->model->columns_update->getUrl()->setValue(translit_url_safe($title));
		$Taxi->cities->model->columns_update->getCoor()->setValue($coor);
		$Taxi->cities->model->columns_update->getType()->setValue(1);
		$city_id=$Taxi->cities->Insert();
	}

}



$from=array(
	'Аэропорт Симферополь'=>'59d0a7a1817d8b5f8a8dd81c',
	'Ж/Д Вокзал Симферополь'=>'59d0a7a1817d8b5f8a8dd871'
);
foreach ($res2[4] as $key=>$title) {
	$title=trim($title);
	if (isset($al_array[mb_strtolower($title)])) {
		$title=$al_array[mb_strtolower($title)];
	}
	if (isset($from[$title])==false) {
		$from[$title]=$res2[1][$key];
	}
}


foreach ($from as $from_title=>$from_uid) {
	$content=load_url('https://taxi-alex24.ru/price?from='.$from_uid);

	$Taxi->cities->CreateModel();
	$Taxi->cities->model->columns_where->getTitle()->setValue($from_title);
	$from_data=$Taxi->cities->GetItem();

	if ($from_data) {
		preg_match_all("#<div class=\"main-price__item\"><div class=\"main-price__item-route\">(.*)</div><div class=\"main-price__item-price\">(.*)</div><div class=\"main-price__item-price\">(.*)</div><div class=\"main-price__item-price\">(.*)</div><div class=\"main-price__item-price\">(.*)</div><div class=\"main-price__item-price\">(.*)</div><div class=\"main-price__item-button\">#Us", $content, $res2);

		foreach ($res2[1] as $kkkkk=>$to_title) {
			$to_title=trim($to_title);
			if (isset($al_array[mb_strtolower($to_title)])) {
				$to_title=$al_array[mb_strtolower($to_title)];
			}

			$Taxi->cities->CreateModel();
			$Taxi->cities->model->columns_where->getTitle()->setValue($to_title);
			$check=$Taxi->cities->GetItem();

			$Taxi->cities->CreateModel();
			$Taxi->cities->model->columns_where->getUrl()->setValue(translit_url_safe($to_title));
			$check2=$Taxi->cities->GetItem();

			if ($check) {
				$to_city_id=$check['city_id'];
			}
			elseif ($check2) {
				$to_city_id=$check2['city_id'];
			}
			else {
				$coor='';
				$uu='https://search-maps.yandex.ru/v1/?type=geo&apikey=9bd63b22-4f68-406c-87d2-c6eaa1fedfee&text='.urlencode($to_title).'&bbox=24.978231949822558,43.92460248885024~39.98555616857254,48.8100142259997&rspn=1&results=10&lang=ru_RU';

				$content=file_get_contents($uu);
				$data=json_decode($content);

				foreach ($data->features as $line) {
					$coor = $line->geometry->coordinates[1] . ',' . $line->geometry->coordinates[0];
				}

				$Taxi->cities->CreateModel();
				$Taxi->cities->model->columns_update->getTitle()->setValue($to_title);
				$Taxi->cities->model->columns_update->getUrl()->setValue(translit_url_safe($to_title));
				$Taxi->cities->model->columns_update->getCoor()->setValue($coor);
				$Taxi->cities->model->columns_update->getType()->setValue(1);
				$to_city_id=$Taxi->cities->Insert();
			}

			$Taxi->routes->CreateModel();
			$Taxi->routes->model->columns_where->getStart()->setValue($from_data['city_id']);
			$Taxi->routes->model->columns_where->getEnd()->setValue($to_city_id);
			$check=$Taxi->routes->GetItem();
			if ($check==false) {
				$Taxi->routes->CreateModel();
				$Taxi->routes->model->columns_update->getStart()->setValue($from_data['city_id']);
				$Taxi->routes->model->columns_update->getEnd()->setValue($to_city_id);
				$route_id=$Taxi->routes->Insert();
			}
			else {
				$route_id=$check['route_id'];
			}





			preg_match_all('!\d+!', $res2[2][$kkkkk], $matches);
			preg_match_all('!\d+!', $res2[3][$kkkkk], $matches2);
			preg_match_all('!\d+!', $res2[4][$kkkkk], $matches3);
			preg_match_all('!\d+!', $res2[5][$kkkkk], $matches4);
			preg_match_all('!\d+!', $res2[6][$kkkkk], $matches5);

			$prices=array(
				2=>intval($matches[0][0]),
				3=>intval($matches2[0][0]),
				4=>intval($matches3[0][0]),
				7=>intval($matches4[0][0]),
				6=>intval($matches5[0][0]),
				5=>(intval($matches3[0][0])+intval($matches5[0][0]))/2
			);


			foreach ($prices as $p_class_id=>$p_value) {
				$Taxi->prices->CreateModel();
				$Taxi->prices->model->columns_where->getRouteId()->setValue($route_id);
				$Taxi->prices->model->columns_where->getClassId()->setValue($p_class_id);
				$check=$Taxi->prices->GetItem();

				if ($p_value) {
					$Taxi->prices->CreateModel();
					$Taxi->prices->model->columns_update->getRouteId()->setValue($route_id);
					$Taxi->prices->model->columns_update->getClassId()->setValue($p_class_id);
					$Taxi->prices->model->columns_update->getValue()->setValue($p_value);
					if ($check==false) {
						$Taxi->prices->Insert();
					}
					else {
						$Taxi->prices->model->columns_where->getId()->setValue($check['price_id']);
						$Taxi->prices->Update();
					}
				}

			}


		}
	}


}



exit;

$content=load_url('https://taxiaero24.ru/tarif');

$prices=array();
$bb=array(
	'Алупка',
	'Андреевка',
	'Армянск',
	'Батальное'
);


foreach ($bb as $b){
	preg_match_all("#<div class=t431__data-part2 data-auto-correct-mobile-width=false style=\"display: none;\">".$b.";(.*)</div>#Us", $content, $res2);
	$gg=nl2br($b.';'.$res2[1][0]);
	$gg=explode('<br />',$gg);


	foreach ($gg as $g) {
		$h=explode(';',$g);
		$prices['Аэропорт Симферополь'][trim($h[0])][2]=$h[1];
		$prices['Аэропорт Симферополь'][str_replace('ё','е',trim($h[0]))][1]=$h[1];

		$prices['Аэропорт Симферополь'][trim($h[0])][3]=$h[2];
		$prices['Аэропорт Симферополь'][str_replace('ё','е',trim($h[0]))][3]=$h[2];

		$prices['Аэропорт Симферополь'][trim($h[0])][4]=$h[3];
		$prices['Аэропорт Симферополь'][str_replace('ё','е',trim($h[0]))][4]=$h[3];

		$prices['Аэропорт Симферополь'][trim($h[0])][5]=$h[4];
		$prices['Аэропорт Симферополь'][str_replace('ё','е',trim($h[0]))][5]=$h[4];

		$prices['Аэропорт Симферополь'][trim($h[0])][6]=$h[5];
		$prices['Аэропорт Симферополь'][str_replace('ё','е',trim($h[0]))][6]=$h[5];

		$prices['Аэропорт Симферополь'][trim($h[0])][7]=$h[7];
		$prices['Аэропорт Симферополь'][str_replace('ё','е',trim($h[0]))][7]=$h[7];

		$prices['Аэропорт Симферополь'][trim($h[0])][8]=$h[8];
		$prices['Аэропорт Симферополь'][str_replace('ё','е',trim($h[0]))][8]=$h[8];
	}

}


$to_titles=array(
	1=>'Сочи',
	2=>'Адлер',
	3=>'Туапсе',
	4=>'Геленджик',
	5=>'Новороссийск',
	6=>'Анапа',
	7=>'Краснодар'
);

$classes_ids=array(
	0=>2,
	1=>3,
	2=>4,
	3=>5,
	4=>6,
	5=>7,
	7=>8

);


$loop_index=0;

preg_match_all("#Направление;Сочи;Адлер;Туапсе;Геленджик;Новороссийск;Анапа;Краснодар</div> <div class=t431__data-part2 data-auto-correct-mobile-width=false style=\"display: none;\">Ялта;(.*)</div>#Us", $content, $res2);

foreach ($res2[1] as $line) {
	$gg=nl2br('Ялта;'.$line);
	$from_cities=explode('<br />',$gg);


	foreach ($from_cities as $from_d) {
		$from_dd=explode(';',$from_d);

		if (isset($classes_ids[$loop_index])) {
			$kk=0;
			foreach ($from_dd as $pp) {
				if ($kk!=0) {
					$to_title=trim($to_titles[$kk]);
					if ($to_title) {
						$prices[trim($from_dd[0])][$to_title][$classes_ids[$loop_index]]=$pp;
					}

				}
				$kk++;
			}


		}

	}
	$loop_index++;
}



foreach ($prices as $from_title=>$dd1) {
	foreach ($dd1 as $p_title=>$dd2) {
		foreach ($dd2 as $class_id=>$p_value){

			if (isset($al_array[mb_strtolower($p_title)])) {
				$p_title=$al_array[mb_strtolower($p_title)];
			}


			$Taxi->cities->CreateModel();
			$Taxi->cities->model->columns_where->getTitle()->setValue($p_title);
			$check=$Taxi->cities->GetItem();

			$Taxi->cities->CreateModel();
			$Taxi->cities->model->columns_where->getUrl()->setValue(translit_url_safe($p_title));
			$check2=$Taxi->cities->GetItem();


			if ($check) {
				$to_id=$check['city_id'];
			}
			elseif ($check2) {
				$to_id=$check['city_id'];
			}
			else {

				$coor='';
				$uu='https://search-maps.yandex.ru/v1/?type=geo&apikey=9bd63b22-4f68-406c-87d2-c6eaa1fedfee&text='.urlencode($p_title).'&bbox=24.978231949822558,43.92460248885024~39.98555616857254,48.8100142259997&rspn=1&results=10&lang=ru_RU';

				$content=file_get_contents($uu);
				$data=json_decode($content);

				foreach ($data->features as $line) {
					$coor = $line->geometry->coordinates[1] . ',' . $line->geometry->coordinates[0];
				}

				$Taxi->cities->CreateModel();
				$Taxi->cities->model->columns_update->getTitle()->setValue($p_title);
				$Taxi->cities->model->columns_update->getUrl()->setValue(translit_url_safe($p_title));
				$Taxi->cities->model->columns_update->getCoor()->setValue($coor);
				$Taxi->cities->model->columns_update->getType()->setValue(1);
				$to_id=$Taxi->cities->Insert();
			}

			if (isset($al_array[mb_strtolower($from_title)])) {
				$from_title=$al_array[mb_strtolower($from_title)];
			}


			$Taxi->cities->CreateModel();
			$Taxi->cities->model->columns_where->getTitle()->setValue($from_title);
			$check=$Taxi->cities->GetItem();

			$Taxi->cities->CreateModel();
			$Taxi->cities->model->columns_where->getUrl()->setValue(translit_url_safe($from_title));
			$check2=$Taxi->cities->GetItem();


			if ($check) {
				$from_id=$check['city_id'];
			}
			elseif ($check2) {
				$from_id=$check['city_id'];
			}
			else {

				$coor='';
				$uu='https://search-maps.yandex.ru/v1/?type=geo&apikey=9bd63b22-4f68-406c-87d2-c6eaa1fedfee&text='.urlencode($from_title).'&bbox=24.978231949822558,43.92460248885024~39.98555616857254,48.8100142259997&rspn=1&results=10&lang=ru_RU';

				$content=file_get_contents($uu);
				$data=json_decode($content);

				foreach ($data->features as $line) {
					$coor = $line->geometry->coordinates[1] . ',' . $line->geometry->coordinates[0];
				}

				$Taxi->cities->CreateModel();
				$Taxi->cities->model->columns_update->getTitle()->setValue($from_title);
				$Taxi->cities->model->columns_update->getUrl()->setValue(translit_url_safe($from_title));
				$Taxi->cities->model->columns_update->getCoor()->setValue($coor);
				$Taxi->cities->model->columns_update->getType()->setValue(1);
				$from_id=$Taxi->cities->Insert();
			}




			$Taxi->routes->CreateModel();
			$Taxi->routes->model->columns_where->getStart()->setValue($from_id);
			$Taxi->routes->model->columns_where->getEnd()->setValue($to_id);
			$check=$Taxi->routes->GetItem();


			if ($check==false) {
				$Taxi->routes->CreateModel();
				$Taxi->routes->model->columns_update->getStart()->setValue($from_id);
				$Taxi->routes->model->columns_update->getEnd()->setValue($to_id);
				$route_id=$Taxi->routes->Insert();
			}
			else {
				$route_id=$check['route_id'];
			}

			$Taxi->prices->CreateModel();
			$Taxi->prices->model->columns_where->getRouteId()->setValue($route_id);
			$Taxi->prices->model->columns_where->getClassId()->setValue($class_id);
			$check2=$Taxi->prices->GetItem();

			$Taxi->prices->CreateModel();
			$Taxi->prices->model->columns_update->getRouteId()->setValue($route_id);
			$Taxi->prices->model->columns_update->getClassId()->setValue($class_id);
			$Taxi->prices->model->columns_update->getValue()->setValue($p_value);
			if ($check2==false) {
				$Taxi->prices->Insert();
			}
			else {
				$Taxi->prices->model->columns_where->getId()->setValue($check2['price_id']);
				$Taxi->prices->Update();
			}

		}
	}
}





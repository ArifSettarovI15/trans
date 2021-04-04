<?php
namespace  Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('rus_date', [$this, 'formatPrice']),
        ];
    }

    public function formatPrice($unix_time)
    {
        $day = date("d", $unix_time);
        $month = date("m", $unix_time);
        $array = [
            1=>"ЯНВ",
            2=>"ФЕВ",
            3=>"МАР",
            4=>"АПР",
            5=>"МАЙ",
            6=>"ИЮН",
            7=>"ИЮЛ",
            8=>"АВГ",
            9=>"СЕН",
            10=>"ОКТ",
            11=>"НОЯ",
            12=>"ДЕК",
        ];

       $month =  $array[(int)$month];
       $year = date("Y", $unix_time);
        $result['d'] = $day;
        $result['m'] = $month;
        $result['y'] = $year;
            $result['dm'] = $day.' '.$month;
            $result['dmy'] = $day.' '.$month.' '.$year;



        return $result;
    }
}
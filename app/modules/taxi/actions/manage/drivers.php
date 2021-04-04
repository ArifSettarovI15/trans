<?php
$Main->user->PagePrivacy('admin');


$variables = array();
$variables['cars'] = $Taxi->cars->getRentCars();
$Paging= new ClassPaging($Main, 50);
$Paging->show_per_page=true;
$Paging->data = $Taxi->drivers->getDrivers($Paging->sql_start, $Paging->per_page);
$Paging->total = $Taxi->drivers->getCount();


$Paging->Display('taxi/manager/drivers_table.twig', $variables);
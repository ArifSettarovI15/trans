<?php
$Main->user->PagePrivacy('admin');

$variables = array();
$Paging = new ClassPaging($Main, 50);
$Paging->show_per_page = true;



$Paging->total=$Main->user->GetUsersTotalFromDb();
$filter_options['user_role_id'] = 1;
$Paging->data = $Main->user->GetUsersFromDb($Paging->per_page, $Paging->sql_start);


$Paging->Display('taxi/manager/clients_table.twig', $variables);
<?php 

require 'core/initializer.php';

$new_name = $_POST['txt-copy-name'];
$id_report = $_POST['id-report'];

$dao = new ReportDAO;
$report = $dao->getReportById($id_report);

// $id_creator, $id_view, $name, $query, $cols, $grouping, $sum, $hour_pos, $fields, $timespan, $persistent_grouping, $json_filters, $date_field

$id_creator = $_SESSION['user']->getIdUser();
$name = $new_name;
$query = $report[0]->query_report;
$cols = $report[0]->columns_report;
$grouping = $report[0]->col_to_group_report;
$sum = $report[0]->sum_report;
$hour_pos = $report[0]->hour_pos_report;
$fields = $report[0]->edit_fields_report;
$timespan = $report[0]->timespan_report;
$persistent_grouping = $report[0]->persistent_group_report;
$json_filters = $report[0]->json_filters_report;
$date_field = $report[0]->date_field_report;

$lastId = $dao->storeReport($id_creator, $name, $query, $cols, $grouping, $sum, $hour_pos, $fields, $timespan, $persistent_grouping, $json_filters, $date_field);

$dao->setViewUsers(array($id_creator), $lastId);


Redirect::to("gerenciar-relatorios.php");
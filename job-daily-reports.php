<?php 

require 'plugins/php-excel/Classes/PHPExcel.php';
require 'core/initializer.php';

$dao = new ScheduledReportDAO;
$objPHPExcel = new PHPExcel();

var_dump($dao);
exit();

$daily_repots = $dao->getDailyReports();

var_dump($daily_reports);
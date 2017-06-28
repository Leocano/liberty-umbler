<?php
require_once 'plugins/php-excel/Classes/PHPExcel.php';
require_once 'plugins/php-excel/Classes/PHPExcel/IOFactory.php';
require 'core/initializer.php';

$billing_factory = new BillingFactory();
$billing_reports = $billing_factory->getBillingReports();

$results = array();
foreach($billing_reports as $report){
	$query = $report->query_report;
	$db->query($query);
	$report_result = $db->getResults();
	array_push($results, $report_result);
}

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Create a first sheet, representing sales data
$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->setTitle('Planilha 1');
foreach ($results as $data) {
	foreach ($data as $line){
		$line = (array) $line;
        // Rename sheet
        $objPHPExcel->getActiveSheet()->fromArray($line);
    }
}

// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="name_of_file.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
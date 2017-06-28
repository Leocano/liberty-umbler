<?php

require 'core/initializer.php';

$billing_factory = new BillingFactory();
$billing_reports = $billing_factory->getBillingReports();

// Dowloading the xls reports
$results = array();
foreach($billing_reports as $report){
	$query = $report->query_report;
	$db->query($query);
	$report_result = $db->getResults();
	array_push($results, $report_result);
}

// header("Content-Disposition: attachment; filename=\"demo.xls\"");
// header("Content-Type: application/vnd.ms-excel;");
// header("Pragma: no-cache");
// header("Expires: 0");

$out = fopen("zip_reports/report.csv", 'w');	
foreach ($results as $data) {
	foreach ($data as $line){
		$line = (array) $line;
    	fputcsv($out, $line);
	}
}
fclose($out);

//Downloading the zipped folder
// $zip = new ZipArchive;
// $download = 'relatorios.zip';
// $zip->open($download, ZipArchive::CREATE);
// foreach (glob("zip_reports/*.txt") as $file) { 
// 	$zip->addFile($file);
// }
// $zip->close();
// header('Content-Type: application/zip');
// header("Content-Disposition: attachment; filename = $download");
// header('Content-Length: ' . filesize($download));
// header("Location: $download");

// require 'footers/main-footer.php';
?>
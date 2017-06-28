<?php 
require 'headers/main-header.php';
if(!$user->checkProfile(array(3, 2))){
	Redirect::to("index.php");
	exit();
}

$billing_factory = new BillingFactory();
$billing_reports = $billing_factory->getBillingReports();

?>

<a class="btn btn-main" href="download-relatorios.php">download</a>

<?php 

foreach($billing_reports as $report){
	Debug::kill($report);
}

require 'footers/main-footer.php';
?>

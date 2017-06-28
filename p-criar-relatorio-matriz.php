<?php 

// Arquivo de processamento

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);
Token::generateToken();

$fields = $_POST['slt-fields'];
$date_from = $_POST['slt-date-from'];
$date_to = $_POST['slt-date-to'];

if (isset($_POST['txt-parameter'])){
	$condition = $_POST['slt-condition'];
	$criteria = $_POST['slt-criteria'];
	$parameter = $_POST['txt-parameter'];
	$connector = $_POST['slt-connector'];
} else {
	$condition = null;
	$criteria = null;
	$parameter = null;
	$connector = null;
}

if (Validator::isEmpty(array($fields))){
	Redirect::to("criar-relatorio-matriz.php");
	exit();
}

$matrix_report = new MatrixReport();

$matrix_report = MatrixReportFactory::generateReport($report);
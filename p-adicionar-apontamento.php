<?php 

// Arquivo de processamento


require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

if ($_POST['status'] == 2){
	exit();
}

if (isset($_POST['consultant-select'])){
	$id_user = $_POST['consultant-select'];
} else {
	$id_user = $_POST['id-user'];
}

$id_ticket = $_POST['id-ticket'];
$type = $_POST['slt-type'];
$date = $_POST['txt-date'];
$hours = $_POST['txt-hours'];
$minutes = $_POST['txt-minutes'];
$description = $_POST['txt-desc'];

$cost = $_POST['txt-cost'];

if (Validator::isEmpty(array($id_ticket, $date, $description, $type))){
	echo "Preencha todos os campos";
	exit;
}

if (($hours == 0 && $minutes == 0) || $hours > 24 || $minutes > 59 || $hours < 0 || $minutes < 0){
	echo "Insira um horário válido";
	exit();
}

$description = str_replace('"', "&quot;", $description);
$description = str_replace("'", "&quot;", $description);

$time_executed = $date . " " . Date("H:i");

$date = str_replace("/","-",$date);

//
$date = strtotime($date);
setlocale(LC_ALL, 'pt_BR');
date_default_timezone_set('America/Sao_Paulo');
$month_upper = ucfirst(strftime('%B', $date));
$month = htmlentities(strftime('%m/%Y - ' . $month_upper, $date));

$date = date('Y-m-d', $date);
//

$today = Date('Y-m-d');
    
$diff = abs(strtotime($date) - strtotime($today));
$years = floor($diff / (365*60*60*24));
$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24) / (60*60*24));

if ($_SESSION['user']->checkProfile(array(1))){
	if (Date('N') == 1){
		if ($days > 3){
			echo "Insira um horário válido";
			exit();
		}
	} else {
		if ($days > 1){
			echo "Insira um horário válido";
			exit();
		}
	}
}

//
$time = $hours . ":" . $minutes;
//



$timekeeping = new Timekeeping(null, $id_ticket, $id_user, $type, $date, $time, $description, $cost);
$timekeeping->setMonth($month);
$timekeeping->setTimeExecuted($time_executed);

$dao = new TimekeepingDAO;
$dao->insertTimekeeping($timekeeping);

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistory($id_ticket, $id_user, "Apontamento de horas realizado por");

echo "success";
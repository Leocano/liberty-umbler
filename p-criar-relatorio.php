<?php 

// Arquivo de processamento

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);
// Token::generateToken();

$fields = $_POST['slt-columns'];
$date_field = $_POST['slt-date'];
$date_from = $_POST['slt-date-from'];
$date_to = $_POST['slt-date-to'];
$name_report = $_POST['txt-name-report'];
$id_user = $_POST['id-user'];
$id_view = $_POST['slt-view'];
$id_companies = $_POST['slt-companies'];

$user = $_SESSION['user'];

if (isset($_POST['txt-parameter'])){
	$condition = $_POST['slt-condition'];
	$criteria = $_POST['slt-criteria'];
	$parameter = $_POST['txt-parameter'];
	$connector = $_POST['slt-connector'];

	$filter_attr = array();

	if ($user->checkProfile(array(1)) && $parameter[0] != $user->getName()) {
		echo "block";
		exit();
	}

	for ($i = 0; $i < count($condition); $i++) {
		$filter_attr[$i] = $condition[$i] . "&" . $criteria[$i] . "&" . $parameter[$i] . "&" . $connector[$i];
	}

	$json_filters = json_encode($filter_attr);

} else {
	$condition = null;
	$criteria = null;
	$parameter = null;
	$connector = null;
}

if (isset($_POST['check-group'])){
	$grouping = $_POST['slt-group'];

	if (!in_array($grouping, $fields)){
		echo "group";
		exit();
	}
} else {
	$grouping = null;
}

$sum = $_POST['check-sum'];

if (Validator::isEmpty(array($fields, $name_report))){
	echo "empty";
	exit();
}

if ($date_field != "tb_timekeeping.current_month" && $date_field != "tb_timekeeping.last_month"){
	if (Validator::isEmpty(array($date_from, $date_to))) {
		echo "empty";
		exit();
	}
}

if ($sum == "on" && !in_array("tb_timekeeping.hours", $fields)){
	echo "hours";
	exit();
}

$timespan = "De " . $date_from . " até " . $date_to;

$date_from = str_replace("/","-",$date_from);
$date_from = strtotime($date_from);
$date_from = date('Y-m-d' , $date_from);

$date_to = str_replace("/","-",$date_to);
$date_to = strtotime($date_to);
$date_to = date('Y-m-d' , $date_to);



$hidden = $_POST['multiple_value']; //get the values from the hidden field
$hidden_in_array = explode(",", $hidden); //convert the values into array
$filter_array = array_filter($hidden_in_array); //remove empty index 
$reset_key = array_values($filter_array); //reset the array key 

$report = new Report($reset_key, $condition, $criteria, $parameter, $connector, $grouping, $date_field, $date_from, $date_to, $fields);

$factory = new ReportFactory();
$newReport = $factory->generateReport($report , $sum);

$_SESSION['report'] = $newReport;


$query = $newReport->getQuery();
$columns = "'" . $newReport->getColumns() . "'";
$columns = explode(" , ", $columns);
$columns = implode(".", $columns);
$columns = explode(".", $columns);
$columns = implode("','", $columns);



$grouping = $newReport->getGrouping();
$group_cols = $newReport->getColumns();
$group_cols = explode(" , ", $group_cols);
$index = 0;

foreach ($group_cols as $col) {
	if ($grouping == $col){
		$col_to_group = $index;
		break;
	}
	$index++;
}

$hour_pos = $newReport->getHourPos();

$fields = implode(",", $fields);
$last_id = ReportDAO::storeReport($id_user, $name_report, $query, $columns, $col_to_group, $sum, $hour_pos, implode(",", $reset_key), $timespan, $grouping, $json_filters, $date_field);

ReportDAO::deleteCompanyView($last_id);
if ($id_companies != null){
	foreach ($id_companies as $id_company) {
		ReportDAO::addCompany($id_company, $last_id);
	}
}

ReportDAO::setViewUsers($id_view, $last_id);

echo json_encode(array("token" => $_SESSION['token'],
					   "id" => $last_id
	));
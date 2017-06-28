<?php

// Arquivo de processamento

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$id_main = $_POST['slt-main'];
$id_user = $_POST['slt-atribute'];
$id_ticket = $_POST['id-ticket'];
$priority = $_POST['priority'];
$created_by = $_POST['created-by'];
$category = $_POST['category'];
$subject = $_POST['subject'];
$description = $_POST['description'];

$dao = new AssignDAO;

if ($id_user == null){
	$id_user = array();
}

$locked_users = $dao->getLockedParticipants($id_ticket);

if (!empty($locked_users)){
	foreach ($locked_users as $locked_user) {
		if(!in_array($locked_user->id_user, $id_user) && $locked_user->id_user != $id_main){
			echo "locked";
			exit();
		}
	}
}

$locked_user = $dao->getLockedMain($id_ticket);

if (!empty($locked_user)){
	if ($locked_user[0]->id_user != $id_main && !in_array($locked_user[0]->id_user, $id_user)) {
		echo "locked";
		exit();
	}
}

$dao->deleteMainConsultant($id_ticket, $id_main);

if (empty($id_user)){
	$id_user = null;
}

if (isset($id_user)){
	$dao->deleteOldConsultants($id_ticket, $id_user);
	$dao->assignConsultant($id_ticket, $id_user);
} else {
	$dao->deleteAllParticipants($id_ticket);
}

$dao->assignMainConsultant($id_ticket, $id_main);

if (isset($id_user)){
	array_push($id_user, $id_main);
	$user_emails = $dao->getEmailsByUserId($id_user);
	$dao->updateSent($id_user);
} else {
	$main_array = array();
	array_push($main_array, $id_main);
	$user_emails = $dao->getEmailsByUserId($main_array);
	$dao->updateSent($main_array);
}

// include 'mail/mail-atribuir-chamado.php';

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistory($id_ticket, $id_user, "Novos consultores atribuídos ao chamado por");
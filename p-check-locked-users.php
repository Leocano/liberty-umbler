<?php

require 'core/initializer.php';

$id_ticket = $_POST['id-ticket'];
$id_main = $_POST['slt-main'];
$participants = $_POST['slt-users'];

// var_dump($participants);

// exit();

$dao = new AssignDAO;

if ($participants == null){
	$participants = array();
}

$locked_users = $dao->getLockedParticipants($id_ticket);

if (!empty($locked_users)){
	foreach ($locked_users as $locked_user) {
		if(!in_array($locked_user->id_user, $participants) && $locked_user->id_user != $id_main){
			echo "Consultores com apontamento no chamado não podem ser removidos!";
			exit();
		}
	}
}

$locked_user = $dao->getLockedMain($id_ticket);

if (!empty($locked_user)){
	if ($locked_user[0]->id_user != $id_main && !in_array($locked_user[0]->id_user, $participants)) {
		echo "Consultores com apontamento no chamado não podem ser removidos!";
		exit();
	}
}

echo "success";
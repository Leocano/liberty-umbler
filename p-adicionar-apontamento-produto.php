<?php 

// Arquivo de processamento


require 'core/initializer.php';

if ($_POST['status'] == 2){
	exit();
}

$id_user = $_POST['id-user'];
$id_ticket = $_POST['id-ticket'];
$description = $_POST['txt-desc'];
$date = $_POST['txt-date'];
$id_creator = $_POST['id_creator'];
$subject = $_POST['subject'];
$name_email = $user->getName();

$date = str_replace("/","-",$date);
$date = strtotime($date);
$date = date('Y-m-d', $date);

$date = $date . " 00:00:00";

if (Validator::isEmpty(array($id_ticket, $description))){
	echo "Preencha todos os campos";
	exit;
}

$description = str_replace('"', "&quot;", $description);
$description = str_replace("'", "&quot;", $description);

$db->query(
    "
    INSERT INTO
        tb_product_timekeeping
    VALUES
    (
        null
    ,   ?
    ,   ?
    ,   ?
    ,   ?
    )
    ",
    array(
        $id_ticket
    ,   $id_user
    ,   $description
    ,   $date
    )
);

$dao = new AssignDAO;

if ($id_user == $id_creator) {
    $assigned = $dao->getAssignedByTicketProduct($id_ticket);
    $main_consultant = $dao->getMainConsultantProduct($id_ticket);
    $users_to_send = array();
    foreach($assigned as $assi_user) {
        array_push($users_to_send, $assi_user->id_user);
    }
    array_push($users_to_send, $main_consultant[0]->id_user);
    
    $user_emails = $dao->getEmailsByUserIdProductTimekeeping($users_to_send, $id_ticket);
} else {
    $user_emails = $dao->getCreatorId($id_creator);
}

// var_dump($user_emails);
// exit();
include 'mail/mail-apontamento-produtos.php';

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistoryProduct($id_ticket, $id_user, "Apontamento de horas realizado por");

echo "success";
<?php 

// Arquivo de processamento

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$json_ticket = json_decode($_POST['j_ticket']);
$json_solution = json_decode($_POST['j_solution']);

$id = $json_ticket[0]->id_ticket;
$id_creator = $json_ticket[0]->id_creator;
$status = $_POST['status'];

if($_POST['disabled'] == "disabled"){
	Redirect::to("visualizar-chamado.php?id=" . $id);
}

$dao = new TicketDAO;

$dao->changeStatus($id, $status);
$sent_mail = $dao->getSentMail($id, $id_creator);

if ($sent_mail[0]->sent_mail == 0 && $sent_mail[0]->active == 1){
	include 'mail/mail-fechar-chamado.php';
}

$dao->emailSent($id);

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistory($id, $id_user, "Chamado fechado por");

Redirect::to("visualizar-chamado.php?id=" . $id);
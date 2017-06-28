<?php 

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$status = $_POST['slt-status'];
$id = $_POST['id-ticket'];

$dao = new TicketDAO;

$dao->changeStatus($id, $status);

// include 'mail/mail-fechar-chamado.php';

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistory($id, $id_user, "Status do chamado alterado por");

// Redirect::to("visualizar-chamado.php?id=" . $id);
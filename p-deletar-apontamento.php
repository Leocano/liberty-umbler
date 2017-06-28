<?php 

// Arquivo de processamento

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$id = $_POST['delete-timekeeping-id'];
$ticket = $_POST['ticket-id'];

$dao = new TimekeepingDAO;

$dao->deleteTimekeeping($id);

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistory($ticket, $id_user, "Apontamento deletado por");
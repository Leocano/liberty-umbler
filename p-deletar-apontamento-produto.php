<?php 

// Arquivo de processamento

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$id = $_POST['delete-timekeeping-id'];
$ticket = $_POST['ticket-id'];

$dao = new TimekeepingDAO;

$dao->deleteTimekeepingProduct($id);

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistoryProduct($ticket, $id_user, "Apontamento deletado por");
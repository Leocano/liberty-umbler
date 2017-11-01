<?php 

// Arquivo de processamento

require 'core/initializer.php';

$token = $_GET['token'];
Token::validateToken($token);

$id = $_GET['id'];
$status = $_GET['status'];

$dao = new TicketDAO;

$dao->changeStatusProduct($id, $status);

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistoryProduct($id, $id_user, "Chamado reaberto por");

Redirect::to("visualizar-chamado-produto.php?id=" . $id);
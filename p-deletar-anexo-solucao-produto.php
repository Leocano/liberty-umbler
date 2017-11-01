<?php 

// Arquivo de processamento

require 'core/initializer.php';

// $token = $_GET['token'];
// Token::validateToken($token);

$id = $_GET['id'];
$ticket = $_GET['ticket'];

$dao = new SolutionAttachmentDAO;

$dao->deleteSolutionAttachmentProduct($id);

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistoryProduct($ticket, $id_user, "Anexo da solução deletado por");

Redirect::to("visualizar-chamado-produto.php?id=" . $ticket);
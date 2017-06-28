<?php 

// Arquivo de processamento

require 'core/initializer.php';

$token = $_GET['token'];
Token::validateToken($token);

$id = $_GET['id'];
$ticket = $_GET['ticket'];

$dao = new SolutionAttachmentDAO;

$dao->deleteSolutionAttachment($id);

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistory($ticket, $id_user, "Anexo da solução deletado por");

Redirect::to("visualizar-chamado.php?id=" . $ticket);
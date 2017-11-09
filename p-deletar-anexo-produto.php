<?php 

// Arquivo de processamento

require 'core/initializer.php';

// $token = $_GET['token'];
// Token::validateToken($token);

$id = $_GET['id'];
$ticket = $_GET['ticket'];

$dao = new AttachmentDAO;

$dao->deleteProductAttachment($id);

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistoryProduct($ticket, $id_user, "Anexo deletado por");

Redirect::to("editar-chamado-produto.php?id=" . $ticket);
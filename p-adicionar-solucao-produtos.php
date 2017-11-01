<?php 

// Arquivo de processamento

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$id_ticket = $_POST['id-ticket'];
$id_user = $_POST['id-user'];
$desc_solution = $_POST['txt-desc-solution'];
$attachments = $_POST['attachments'];

$solution = new Solution(null, $id_ticket, $id_user, $desc_solution, null);

$dao = new SolutionDAO();
$dao->deleteSolutionProduct($id_ticket);
$dao->createSolutionProduct($solution);

$lastInsertId = $db->getLastInsertId();

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistoryProduct($id_ticket, $id_user, "Solução adicionada por");

echo "success";
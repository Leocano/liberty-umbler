<?php 

// Arquivo de processamento

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$id = $_POST['txt-delete-id'];

$dao = new ReportDAO;

$dao->deleteReport($id);

echo 'success';
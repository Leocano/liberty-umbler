<?php 

// Arquivo de processamento

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$id = $_POST['id'];

if (isset($_POST['status'])){
	$status = 1;
} else {
	$status = 0;
}

$dao = new ConsultantDAO;

$dao->updateStatus($id, $status);

echo $status;
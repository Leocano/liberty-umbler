<?php 

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$current_password = $_POST['txt-senha-atual'];
$new_password = $_POST['txt-nova-senha'];
$confirm_new_password = $_POST['txt-confirmar-senha'];
$id_user = $_POST['id-user'];

if (empty($new_password) || empty($new_password)){
	echo "Não deixe nenhum campo em branco!";
	exit();
}

if (strlen($new_password) < 8){
	echo "A nova senha deve ter no mínimo 8 caracteres!";
	exit();
}

$dao = new CustomerDAO;
$customer_password = $dao->getCustomerLogin($id_user);

if ($current_password == $customer_password[0]->password){
	if ($new_password == $confirm_new_password) {
		$dao->updatePassword($id_user, $new_password);
		echo "success";
	} else {
		echo "As senhas não coincidem!";
	}
} else {
	echo "A senha atual está incorreta!";
}
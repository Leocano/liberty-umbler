<?php 
// Arquivo de processamento que insere novos clientes

require "core/initializer.php";

$token = $_POST["token"];
Token::validateToken($token);


$name = $_POST["txt-nome"];
$email = $_POST["txt-email"];
$alternative_email = $_POST["txt-email-alt"];
$phone = $_POST["txt-telefone"];
$cellphone = $_POST["txt-celular"];
$role = $_POST["txt-cargo"];
$company = $_POST["slt-empresa"];
$view = $_POST["opt-visualizar"];
$login_name = $_POST["txt-nome-login"];
$password = $_POST["txt-senha"];
$password_repeat = $_POST["txt-confirmar-senha"];

if (Validator::isEmpty(array($name, $role, $email))){
	echo "empty";
	exit;
}

$dao = new UserDAO;
$duplicate = $dao->searchEmail($email);

if($duplicate[0]->email == $email){
	echo "email";
	exit();
}

if ($password != "" && strlen($password) < 8){
	echo "size";
	exit();
}

if ($password != $password_repeat){
	echo "password";
	exit;
}

$replace = array("(", ")", "-", " ");

$phone = str_replace($replace, "", $phone);
$cellphone = str_replace($replace, "", $cellphone);

$customer = new UserCustomer($login_name, $password);
$customer->setCompany($company);
$customer->setProfile(5);
$customer->setView($view);
$customer->setName($name);
$customer->setEmail($email);
$customer->setAlternativeEmail($alternative_email);
$customer->setCellphone($cellphone);
$customer->setRole($role);
$customer->setPhone($phone);

$dao = new CustomerDAO;
$dao->createNewCustomer($customer);

echo "success";
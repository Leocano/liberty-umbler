<?php 
// Arquivo de processamento que edita os dados do cliente

require "core/initializer.php";

$token = $_POST["token"];
Token::validateToken($token);

$id = $_POST["id"];
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

if ($password != $password_repeat){
	echo "password";
	exit;
}

$dao = new CustomerDAO;

if(Validator::isEmpty(array($login_name, $password, $password_repeat))){
	$dao->deleteLoginInformation($id);
} else {
	$login_information = $dao->getCustomerLogin($id);

	if ($login_information == null){
		$dao->createNewLogin($id, $login_name, $password);
	} else {
		$dao->updateLogin($id, $login_name, $password);
	}
}

$replace = array("(", ")", "-", " ");

$phone = str_replace($replace, "", $phone);
$cellphone = str_replace($replace, "", $cellphone);

$customer = new UserCustomer($login_name, $password);
$customer->setIdUser($id);
$customer->setCompany($company);
$customer->setProfile(5);
$customer->setView($view);
$customer->setName($name);
$customer->setEmail($email);
$customer->setAlternativeEmail($alternative_email);
$customer->setCellphone($cellphone);
$customer->setRole($role);
$customer->setPhone($phone);

$dao->updateCustomer($customer);

echo "success";
<?php 
// Arquivo de processamento que insere novos consultores

require "core/initializer.php";

$token = $_POST["token"];
Token::validateToken($token);

$name = $_POST["txt-nome"];
$code = $_POST["txt-codigo"];
$role = $_POST["txt-cargo"];
$profile = $_POST["slt-perfil"];
// $view = $_POST["opt-visualizar"];
$email = $_POST["txt-email"];
$alternative_email = $_POST["txt-email-alt"];
$phone = $_POST["txt-telefone"];
$cellphone = $_POST["txt-celular"];
$cost = $_POST["txt-custo"];
$area = $_POST['slt-area'];
$contract = $_POST['opt-contract'];

if (Validator::isEmpty(array($name, $role, $email, $contract))){
	echo "empty";
	exit;
}

$dao = new UserDAO;
$duplicate = $dao->searchEmail($email);

if($duplicate[0]->email == $email){
	echo "email";
	exit();
}

$replace = array("(", ")", "-", " ");

$phone = str_replace($replace, "", $phone);
$cellphone = str_replace($replace, "", $cellphone);
$cost = str_replace(",", ".", $cost);

$consultant = new UserConsultant($email, $password);

$consultant->setCompany(1);
$consultant->setProfile($profile);
// $consultant->setView($view);
$consultant->setName($name);
$consultant->setEmail($email);
$consultant->setAlternativeEmail($alternative_email);
$consultant->setCellphone($cellphone);
$consultant->setRole($role);
$consultant->setPhone($phone);
$consultant->setCode($code);
$consultant->setCost($cost);
$consultant->setContract($contract);

$dao = new ConsultantDAO;
$dao->createNewConsultant($consultant, $area);

echo "success";
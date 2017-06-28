<?php 
// Arquivo de processamento que insere novas empresas

require "core/initializer.php";

$token = $_POST["token"];
Token::validateToken($token);


$name = $_POST["txt-nome"];
$email = $_POST["txt-email"];
$cellphone = $_POST['txt-celular'];
$phone = $_POST['txt-telefone'];
$city = $_POST['txt-cidade'];
$bairro = $_POST['txt-bairro'];
$address = $_POST['txt-endereco'];
$cep = $_POST['txt-cep'];
$contato_principal = $_POST['txt-contato-principal'];

if (Validator::isEmpty(array($name, $email))){
	echo "empty";
	exit;
}

$replace = array("(", ")", "-", " ");
$phone = str_replace($replace, "", $phone);
$cellphone = str_replace($replace, "", $cellphone);

$company = new Company(null, $name, 1, $email, $address, 1);
$company->setPhone($phone);
$company->setCellphone($cellphone);
$company->setCity($city);
$company->setBairro($bairro);
$company->setCep($cep);
$company->setContatoPrincipal($contato_principal);

// var_dump($company);
// exit();

$dao = new CompanyDAO;
$dao->createNewCompany($company);

echo "success";
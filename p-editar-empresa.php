<?php 
// Arquivo de processamento que edita os dados da empresa

require "core/initializer.php";

$token = $_POST["token"];
Token::validateToken($token);

$id = $_POST["id"];
$name = $_POST["txt-nome"];
$email = $_POST["txt-email"];
$cellphone = $_POST['txt-celular'];
$phone = $_POST["txt-telefone"];
$city = $_POST['txt-cidade'];
$bairro = $_POST['txt-bairro'];
$address = $_POST["txt-endereco"];
$cep = $_POST['txt-cep'];
$contato_principal = $_POST['txt-contato-principal'];

if (Validator::isEmpty(array($name))){
	echo "empty";
	exit;
}

$replace = array("(", ")", "-", " ");
$phone = str_replace($replace, "", $phone);

$company = new Company($id, $name, 1, $email, $address, null);
$company->setPhone($phone);
$company->setCellphone($cellphone);
$company->setCity($city);
$company->setBairro($bairro);
$company->setCep($cep);
$company->setContatoPrincipal($contato_principal);

$dao = new CompanyDAO;
$dao->updateCompany($company);

echo "success";
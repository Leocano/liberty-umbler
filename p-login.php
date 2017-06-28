<?php 

// Arquivo de processamento

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$email = $_POST['txt-email'];
$password = $_POST['txt-password'];

if (strpos($email, '@letnis') !== false) {
    $userType = "Consultant";
} else {
	$userType = "Customer";
}


$user = UserFactory::factory($userType, $email, $password);

$auth = UserAuthenticatorFactory::factory($user);

if ($auth->authenticate($user) == true){
	$user->login();
} else {
	Redirect::to("index.php?error=1");
	exit();
}
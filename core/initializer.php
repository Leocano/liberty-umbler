<?php

//Vetor com as configurações basicas do sistema
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => 'mysql762.umbler.com',
		'username' => 'letnis',
		'password' => '_UL44daw8r,_W',
		'db' => 'liberty_qas'
	)
);

//Função para incluir as classes dinamicamente
function customLoader($class){
	require 'classes/' . $class . '.class.php';
}

spl_autoload_register('customLoader');

session_save_path('session');
session_start();

//Conexão com o banco
$db = Database::getInstance();
<?php

//init.php
//  arquivo que deve ser incluido em todos os outros scripts
// 	@Leonardo Cano

//Vetor com as configurações basicas do sistema
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => 'letnis_qas.mysql.dbaas.com.br',
		'username' => 'letnis_qas',
		'password' => 'Letnis@*qas_',
		'db' => 'letnis_qas'
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
<?php 

// home.php
//  Página principal do SC

// header("Refresh:300");

require 'headers/main-header.php';

if ($user->checkProfile(array(5))){
	require "gerenciar-chamados-cliente.php";
} else {
	require "gerenciar-chamados-consultor.php";
}

?>
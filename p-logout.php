<?php 

// Arquivo de processamento

require 'core/initializer.php';

$user = $_SESSION['user'];
$user->logout();
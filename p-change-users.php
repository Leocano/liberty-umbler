<?php 

require 'core/initializer.php';

$id_company = $_POST['id-change-users'];

$dao = new UserDAO;
$users = $dao->getUsersByCompany($id_company);

echo json_encode($users);
<?php 


require 'core/initializer.php';

$id_category = $_POST['id_category'];

$dao = new SubcategoryDAO;
$subcategories = $dao->getSubcategoryByCategoryId($id_category);

echo json_encode($subcategories);
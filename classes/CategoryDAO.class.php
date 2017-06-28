<?php 

class CategoryDAO{
	public function getAllCategories(){
		$db = Database::getInstance();

		$db->query("SELECT * FROM tb_category");

		return $db->getResults();
	}
}
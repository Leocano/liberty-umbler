<?php 

class AreaDAO{
	public function getAllAreas(){
		$db = Database::getInstance();

		$db->query("SELECT * FROM tb_areas");

		return $db->getResults();
	}
}
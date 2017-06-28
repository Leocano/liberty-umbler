<?php 

class StatusDAO{
	public function getAllStatus(){
		$db = Database::getInstance();

		$db->query("SELECT * FROM tb_status WHERE id_status != 2");

		return $db->getResults();
	}
}
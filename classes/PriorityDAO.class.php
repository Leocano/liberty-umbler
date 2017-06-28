<?php 

class PriorityDAO{
	public function getAllPriorities(){
		$db = Database::getInstance();

		$db->query("SELECT * FROM tb_priority");

		return $db->getResults();
	}
}
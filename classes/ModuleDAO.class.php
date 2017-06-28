<?php 

class ModuleDAO{
	public function getAllModules(){
		$db = Database::getInstance();

		$db->query("SELECT * FROM tb_modules");

		return $db->getResults();
	}
}
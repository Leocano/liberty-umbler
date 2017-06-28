<?php 

class ViewDAO{
	public function getAllViews(){
		$db = Database::getInstance();

		$db->query("SELECT * FROM tb_view");
		$views = $db->getResults();

		return $views;
	}
}
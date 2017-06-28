<?php 

class ViewReportDAO{
	public function getAllViews(){
		$db = Database::getInstance();

		$db->query("SELECT * FROM tb_view_report");

		return $db->getResults();
	}
}
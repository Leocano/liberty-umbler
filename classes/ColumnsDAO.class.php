<?php 

class ColumnsDAO{
	public function getAllColumns(){
		$db = Database::getInstance();

		$db->query("SELECT
						*
					FROM 
						tb_report_columns");

		return $db->getResults();
	}

	public function getDateColumns(){
		$db = Database::getInstance();

		$db->query("SELECT
						*
					FROM
						tb_report_columns
					WHERE
						name_column IN ('date_timekeeping' , 'creation_date')");

		return $db->getResults();
	}

	public function getNicknames($columns){
		$db = Database::getInstance();

		$db->query("SELECT nickname_column FROM tb_report_columns WHERE name_column IN (" . $columns . ") ORDER BY FIELD(name_column , " . $columns . ")");
		$nicknames = $db->getResults();

		return $nicknames;
	}
}
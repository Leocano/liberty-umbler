<?php 

class ConsultantContractDAO{
	public function getAllConsultantContracts(){
		$db = Database::getInstance();

		$db->query(
			"
			SELECT
				*
			FROM
				tb_consultant_contract
			"
		);

		return $db->getResults();
	}
}
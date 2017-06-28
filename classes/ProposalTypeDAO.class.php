<?php 

class ProposalTypeDAO{
	public function getAllProposalTypes(){
		$db = Database::getInstance();

		$db->query("SELECT * FROM tb_proposal_type");

		return $db->getResults();
	}
}
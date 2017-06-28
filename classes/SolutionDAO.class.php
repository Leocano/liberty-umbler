<?php 

class SolutionDAO{
	public function createSolution($solution){
		$db = Database::getInstance();

		$id_ticket = $solution->getIdTicket();
		$id_user = $solution->getIdUser();
		$desc_solution = $solution->getDescSolution();


		$db->query("INSERT INTO
						tb_solutions
					VALUES 
					(
						null 
					,	?
					,	?
					,	?
					,	CURRENT_TIMESTAMP
					)
					"
					,
					array($id_ticket, $id_user, $desc_solution)
					);
	}

	public function deleteSolution($id_ticket){
		$db = Database::getInstance();
		$db->query("DELETE FROM tb_solutions WHERE id_ticket = ?" , array($id_ticket));
	}

	public function getSolutionByTicket($id_ticket){
		$db = Database::getInstance();

		$db->query("SELECT
						solu.*
					,	user.name
					,	REPLACE(DATE_FORMAT(solu.date_solution, '%d/%m/%Y às %T'), '-', '/') as created
					FROM 
						tb_solutions 				solu
					,	tb_users			 		user
					WHERE
						user.id_user = solu.id_user
					AND
						solu.id_ticket = ?
					"
					,
					array($id_ticket)
					);

		return $db->getResults();
	}
}
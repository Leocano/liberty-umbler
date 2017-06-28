<?php 

class SolutionAttachmentDAO{
	public function createSolutionAttachment($id_solution, $name, $new_path){
		$db = Database::getInstance();

		$db->query("INSERT INTO
						tb_solutions_attachments
					VALUES
					(
						null
					,	?
					,	?
					,	?
					)"
					,
					array($id_solution, $name, $new_path)
					);
	}

	public function getSolutionAttachmentsByTicket($id_ticket){
		$db = Database::getInstance();

		$db->query("SELECT
						*
					FROM
						tb_solutions_attachments
					WHERE
						id_ticket = ?
				  "
				  ,
				  array($id_ticket)
				  );

		return $db->getResults();
	}

	public function deleteSolutionAttachment($id){
		$db = Database::getInstance();

		$db->query("SELECT *
					FROM 
						tb_solutions_attachments 
					WHERE 
						id_solution_attachment = ?"
						,
						array($id)
						);

		$attachment = $db->getResults();
		unlink($attachment[0]->path_solution_attachment);

		$db->query("DELETE FROM 
						tb_solutions_attachments
					WHERE
						id_solution_attachment = ?"
						,
						array($id)
						);
	}
}
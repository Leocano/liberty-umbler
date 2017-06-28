<?php 

class ProposalDAO{
	public function getAllProposals(){
		$db = Database::getInstance();

		$db->query("SELECT
						prop.*
					,	user.name
					,	comp.name_company
					,	type.desc_proposal_type
					FROM 
						tb_proposal			prop
					,	tb_users			user
					,	tb_companies		comp
					,	tb_proposal_type	type
					WHERE
						prop.id_company = comp.id_company
					AND
						prop.id_user = user.id_user
					AND
						type.id_proposal_type = prop.id_proposal_type
					ORDER BY
						prop.id_proposal DESC
					");

		return $db->getResults();
	}

	public function updateStatus($id, $status){
		$db = Database::getInstance();

		$db->query("UPDATE tb_proposal SET active_proposal = ? WHERE id_proposal = ?", array($status, $id));
	}

	public function updateProposal($proposal, $id_proposal){
		$db = Database::getInstance();

		$id_company = $proposal->getIdCompany();
		$id_user = $proposal->getIdUser();
		$id_proposal_type = $proposal->getIdProposalType();
		$name_proposal = $proposal->getNameProposal();
		$desc_proposal = $proposal->getDescProposal();
		$id_status = $proposal->getIdStatus();
		$hours = $proposal->getHours();
		$start = $proposal->getStart();
		$end = $proposal->getEnd();
		$percentage = $proposal->getPercentage();
		$months = $proposal->getMonths();


		$db->query("
			UPDATE
				tb_proposal
			SET
				id_company = ?
			,	id_user = ?
			,	id_proposal_type = ?
			,	name_proposal = ?
			,	desc_proposal = ?
			,	active_proposal = ?
			,	hours_proposal = ?
			,	start_proposal = ?
			,	end_proposal = ?
			,	percentage_proposal = ?
			,	months_proposal = ?
			WHERE
				id_proposal = ?
		"
		,
		array(
			$id_company
		,	$id_user
		,	$id_proposal_type
		,	$name_proposal
		,	$desc_proposal
		,	$id_status
		,	$hours
		,	$start
		,	$end
		,	$percentage
		,	$months
		,	$id_proposal
		)
		);
	}

	public function deleteProposal($id_proposal){
		$db = Database::getInstance();

		$db->query(
			"
			DELETE FROM
				tb_proposal
			WHERE
				id_proposal = ?
			"
			,
			array(
				$id_proposal
			)
		);
	}

	public function getTicketsByProposal($id_proposal){
		$db = Database::getInstance();

		$db->query("
			SELECT
				id_ticket
			FROM
				tb_tickets
			WHERE
				id_proposal = ?
			"
			,
			array(
				$id_proposal
			)
		);

		return $db->getResults();
	}

	public function createProposal($proposal){
		$db = Database::getInstance();

		$id_company = $proposal->getIdCompany();
		$id_user = $proposal->getIdUser();
		$id_proposal_type = $proposal->getIdProposalType();
		$name_proposal = $proposal->getNameProposal();
		$desc_proposal = $proposal->getDescProposal();
		$id_status = $proposal->getIdStatus();
		$hours = $proposal->getHours();
		$start = $proposal->getStart();
		$end = $proposal->getEnd();
		$percentage = $proposal->getPercentage();
		$months = $proposal->getMonths();

		$db->query("INSERT INTO
						tb_proposal
					VALUES
					(
						null
					,	?
					,	?
					,	?
					,	?
					,	?
					,	?
					,	?
					,	?
					,	?
					,	?
					,	?
					,	DEFAULT
					,	DEFAULT
					)
					"
					,
					array(
						$id_company
					,	$id_user
					,	$id_proposal_type
					,	$name_proposal
					,	$desc_proposal
					,	$id_status
					,	$hours
					,	$start
					,	$end
					,	$percentage
					,	$months
					)
		);
	}

	public function getProposalByCompanyAndType($id_company, $id_type){
		$db = Database::getInstance();

		$db->query("SELECT
						*
					FROM
						tb_proposal
					WHERE
						id_company = ?
					AND
						id_proposal_type = ?
					AND
						active_proposal = 1
					"
					,
					array(
						$id_company
					,	$id_type
					)
		);

		return $db->getResults();
	}

	public function getProposalById($id_proposal){
		$db = Database::getInstance();

		$db->query("SELECT * FROM tb_proposal where id_proposal = ?", array($id_proposal));

		return $db->getResults();
	}

	public function disableInactiveProposals($id_company, $id_proposal_type){
		$db = Database::getInstance();

		$db->query(
			"
			UPDATE
				tb_proposal
			SET
				active_proposal = 0
			WHERE
				id_company = ?
			AND
				id_proposal_type = ?
			"
			,
			array(
				$id_company
			,	$id_proposal_type
			)
		);
	}

	public function getActiveSupportProposal($id_company){
		$db = Database::getInstance();

		$db->query(
			"
			SELECT
				*
			FROM
				tb_proposal
			WHERE
				id_company = ?
			AND
				active_proposal = 1
			AND
				id_proposal_type = 1
			"
			,
			array(
				$id_company
			)
		);

		return $db->getResults();
	}

	public function getTotalHours($id_company){
		$db = Database::getInstance();

		$db->query(
			"
			SELECT
				hours_proposal
			,	extra_hours
			FROM
				tb_proposal
			WHERE
				id_company = ?
			AND
				id_proposal_type = 1
			AND
				active_proposal = 1
			"
			,
			array(
				$id_company
			)
		);

		return $db->getResults();
	}

	public function getHoursSpent($id_company){
		$db = Database::getInstance();

		$db->query(
			"
			SELECT
				SEC_TO_TIME(SUM(TIME_TO_SEC(time.hours))) as hours_spent
			FROM
				tb_timekeeping	time
			,	tb_tickets		tick
			,	tb_proposal 	prop
			WHERE
				time.id_ticket = tick.id_ticket
			AND
				tick.id_proposal = prop.id_proposal
			AND
				prop.id_company = ?
			AND
				prop.id_proposal_type = 1
			AND
				prop.active_proposal = 1
			AND
				MONTH(time.date_timekeeping) = MONTH(CURDATE())
			"
			,
			array(
				$id_company
			)
		);

		return $db->getResults();
	}

	public function getProposalInfo(){
		/********************* ALTERAR ABAIXO **************/

		$db = Database::getInstance();

		$db->query(
			"
			SELECT
				prop.id_proposal
			,	prop.hours_proposal
			,	prop.start_proposal
			,	prop.end_proposal
			,	prop.percentage_proposal
			,	prop.months_proposal
			,	prop.current_month
			,	prop.extra_hours
			,	SEC_TO_TIME(SUM(TIME_TO_SEC(time.hours))) as hours_spent
			FROM
				tb_timekeeping	time
			,	tb_tickets		tick
			,	tb_proposal 	prop
			WHERE
				time.id_ticket = tick.id_ticket
			AND
				tick.id_proposal = prop.id_proposal
			AND
				prop.id_proposal_type = 1
			AND
				prop.active_proposal = 1
			AND
				-- MUDAR PARA MES ATUAL MENOS 1 --
				MONTH(time.date_timekeeping) = MONTH(CURDATE())
			GROUP BY
				prop.id_proposal
			,	prop.hours_proposal
			,	prop.start_proposal
			,	prop.end_proposal
			,	prop.percentage_proposal
			,	prop.months_proposal
			,	prop.current_month
			,	prop.extra_hours
			"
		);

		return $db->getResults();
	}

	public function updateProposalMonthly($current_month, $extra_hours, $id_proposal){
		$db = Database::getInstance();

		$db->query(
			"
			UPDATE
				tb_proposal
			SET
				current_month = ?
			,	extra_hours = ?
			WHERE
				id_proposal = ?
			"
			,
			array(
				$current_month
			,	$extra_hours
			,	$id_proposal
			)
		);
	}
}
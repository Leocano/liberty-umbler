<?php 

class AssignDAO{
	public function assignConsultant($id_ticket, $id_users = array()){
		$db = Database::getInstance();

		// $db->query("DELETE FROM tb_assigned_users_tickets WHERE id_ticket = ?", array($id_ticket));

		foreach ($id_users as $user) {
			// $db->query("INSERT IGNORE INTO
			$db->query("INSERT INTO
						tb_assigned_users_tickets
					VALUES
					(
						?
					,	?
					,	DEFAULT
					,	0
					)
					"
					,
					array($id_ticket, $user)
					);
		}
	}

	public function deleteAllParticipants($id_ticket){
		$db = Database::getInstance();

		$db->query("
			DELETE FROM
				tb_assigned_users_tickets
			WHERE
				id_ticket = ?
			AND
				main = 0
			"
			,
			array(
				$id_ticket
			)
		);
	}

	public function deleteMainConsultant($id_ticket, $id_main){
		$db = Database::getInstance();

		$db->query("
			DELETE FROM
				tb_assigned_users_tickets
			WHERE
				id_ticket = ?
			AND
				main = 1
			AND
				id_user != $id_main
			"
			,
			array(
				$id_ticket
			)
		);
	}

	public function deleteOldConsultants($id_ticket, $id_users = array()){
		$db = Database::getInstance();

		$id_users = implode(",", $id_users);

		$db->query("DELETE FROM
						tb_assigned_users_tickets
					WHERE
						id_ticket = ?
					AND 
						id_user NOT IN(" . $id_users . ")
					AND
						main = 0
					"
					,
					array(
						$id_ticket
					)
		);
	}

	public function assignMainConsultant($id_ticket, $id_user){
		$db = Database::getInstance();

		// $db->query("DELETE FROM tb_assigned_users_tickets WHERE id_ticket = ?", array($id_ticket));

		// $db->query("INSERT IGNORE INTO
		$db->query("INSERT INTO
					tb_assigned_users_tickets
				VALUES
				(
					?
				,	?
				,	1
				,	0
				)
				"
				,
				array($id_ticket, $id_user)
				);
	}

	public function getAssignedByTicket($id_ticket){
		$db = Database::getInstance();

		$db->query("SELECT 
						tick.*
					,	user.name
					,	user.id_user
					FROM
						tb_assigned_users_tickets	tick
					,	tb_users 					user
					WHERE
						id_ticket = ?
					AND 
						tick.id_user = user.id_user
					AND 
						tick.main    = 0
					"
					,
					array($id_ticket)
					);

		return $db->getResults();
	}

	public function getMainConsultant($id_ticket){
		$db = Database::getInstance();

		$db->query("SELECT 
						tick.*
					,	user.name
					,	user.id_user
					FROM
						tb_assigned_users_tickets	tick
					,	tb_users 					user
					WHERE
						id_ticket = ?
					AND 
						tick.id_user = user.id_user
					AND
						tick.main = 1
					"
					,
					array($id_ticket)
					);

		return $db->getResults();
	}

	public function getEmailsByUserId($id_user = array()){
		$db = Database::getInstance();

		$id_user = implode(",", $id_user);

		$db->query("SELECT
						user.name
					,	user.email
					FROM
						tb_users 		user
					,	tb_assigned_users_tickets	assi
					WHERE
						user.id_user IN (" . $id_user . ")
					AND
						assi.sent = 0
					AND
						assi.id_user = user.id_user
					"
					);
		return $db->getResults();
	}

	public function updateSent($id_user = array()){
		$db = Database::getInstance();

		$id_user = implode(",", $id_user);

		$db->query("UPDATE
						tb_assigned_users_tickets
					SET
						sent = 1
					WHERE
						id_user IN (" . $id_user . ")
		");
	}

	public function getMailInfo($id_ticket){
		$db = Database::getInstance();

		$db->query("SELECT
						user.name
					,	comp.name_company
					,	prio.desc_priority
					,	cate.desc_category
					FROM
						tb_users user
					,	tb_priority prio
					,	tb_category cate
					,	tb_tickets tick
					,	tb_companies comp
					WHERE
						user.id_user = tick.id_creator
					AND
						prio.id_priority = tick.id_priority
					AND
						cate.id_category = tick.id_category
					AND
						id_ticket = ?
					AND
						comp.id_company = tick.id_company
					"
					,
					array($id_ticket)
					);

		return $db->getResults();
	}

	public function getLockedParticipants($id_ticket){
		$db = Database::getInstance();

		$db->query(
			"
				SELECT
					user.id_user
				FROM
					tb_users user
				,	tb_timekeeping	time
				,	tb_assigned_users_tickets	assi
				,	tb_tickets 		tick
				WHERE
					user.id_user = time.id_user
				AND
					assi.id_user = user.id_user
				AND
					tick.id_ticket = assi.id_ticket
				AND
					tick.id_ticket = time.id_ticket
				AND
					time.id_ticket = ?
				AND
					assi.main = 0
				GROUP BY
					user.id_user
			"
			,	
			array(
				$id_ticket
			)
		);

		return $db->getResults();
	}

	public function getLockedMain($id_ticket){
		$db = Database::getInstance();

		$db->query(
			"
				SELECT
					user.id_user
				FROM
					tb_users user
				,	tb_timekeeping	time
				,	tb_assigned_users_tickets	assi
				,	tb_tickets 		tick
				WHERE
					user.id_user = time.id_user
				AND
					assi.id_user = user.id_user
				AND
					tick.id_ticket = assi.id_ticket
				AND
					tick.id_ticket = time.id_ticket
				AND
					time.id_ticket = ?
				AND
					assi.main = 1
				GROUP BY
					user.id_user
			"
			,	
			array(
				$id_ticket
			)
		);

		return $db->getResults();
	}
}
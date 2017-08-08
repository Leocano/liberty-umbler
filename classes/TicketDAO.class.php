<?php 

class TicketDAO{
	public function createNewTicket($ticket){
		$db = Database::getInstance();

		$id_creator = $ticket->getIdCreator();
		$id_priority = $ticket->getIdPriority();
		$id_module = $ticket->getIdModule();
		$id_category = $ticket->getIdCategory();
		$subject = $ticket->getSubject();
		$desc = $ticket->getDesc();
		$cost_center = $ticket->getCostCenter();
		$id_company = $ticket->getIdCompany();
		$name_creator = $ticket->getNameCreator();
		$external_number = $ticket->getExternalNumber();
		$id_proposal = $ticket->getProposal();

		$db->query("INSERT
						INTO
							tb_tickets
						VALUES
						(
							null
						,	?
						,	?
						,	?
						,	?
						,	DEFAULT
						,	?
						,	DEFAULT
						,	?
						,	?
						,	?
						,	?
						,	?
						,	?
						,	DEFAULT
						,	DEFAULT
						,	DEFAULT
						)"
						,
						array(
							$id_creator, 
							$id_priority, 
							$id_module, 
							$id_category, 
							$id_company, 
							$subject, 
							$desc, 
							$cost_center, 
							$id_proposal, 
							$name_creator, 
							$external_number)
						);
	}

	public function createNewTicketConsultant($ticket){
		$db = Database::getInstance();

		$id_creator = $ticket->getIdCreator();
		$id_priority = $ticket->getIdPriority();
		$id_module = $ticket->getIdModule();
		$id_category = $ticket->getIdCategory();
		$subject = $ticket->getSubject();
		$desc = $ticket->getDesc();
		$cost_center = $ticket->getCostCenter();
		$proposal = $ticket->getProposal();
		$id_company = $ticket->getIdCompany();
		$name_creator = $ticket->getNameCreator();
		$external_number = $ticket->getExternalNumber();
		$id_subcategory = $ticket->getIdSubcategory();

		$db->query("INSERT
						INTO
							tb_tickets
						VALUES
						(
							null
						,	?
						,	?
						,	?
						,	?
						,	DEFAULT
						,	?
						,	DEFAULT
						,	?
						,	?
						,	?
						,	?
						,	?
						,	?
						,	?
						,	DEFAULT
						, 	DEFAULT
						)"
						,
						array(
							$id_creator, 
							$id_priority, 
							$id_module, 
							$id_category, 
							$id_company, 
							$subject, 
							$desc, 
							$cost_center,
							$proposal, 
							$name_creator, 
							$external_number,
							$id_subcategory)
						);
	}

	public function updateTicket($ticket){
		$db = Database::getInstance();

		$id_ticket = $ticket->getIdTicket();
		$id_priority = $ticket->getIdPriority();
		$id_module = $ticket->getIdModule();
		$id_category = $ticket->getIdCategory();
		$subject = $ticket->getSubject();
		$desc = $ticket->getDesc();
		$cost_center = $ticket->getCostCenter();
		$proposal = $ticket->getProposal();
		$external_number = $ticket->getExternalNumber();
		$id_creator = $ticket->getIdCreator();
		$id_company = $ticket->getIdCompany();
		$id_subcategory = $ticket->getIdSubcategory();

		$db->query("UPDATE
						tb_tickets
					SET
						id_priority = ?
					,	id_module = ?
					,	id_category = ?
					,	subject_ticket = ?
					,	desc_ticket = ?
					,	cost_center = ?
					,	id_proposal = ?
					,	external_number = ?
					,	id_creator = ?
					,	id_company = ?
					,	id_subcategory = ?
					WHERE
						id_ticket = ?
					"
					,
					array(
						$id_priority, 
						$id_module, 
						$id_category, 
						$subject, 
						$desc, 
						$cost_center, 
						$proposal, 
						$external_number, 
						$id_creator, 
						$id_company, 
						$id_subcategory,
						$id_ticket)
					);
	}

	public function getTicketsByUserId($id){
		$db = Database::getInstance();

		$db->query("
					SELECT
						tick.*
					,	REPLACE(DATE_FORMAT(tick.creation_date, '%d/%m/%Y às %T'), '-', '/') as created
					,	stat.*
					,	user.name
					,	prio.*
					FROM
						tb_tickets		tick
					,	tb_status		stat
					,	tb_users 		user
					,	tb_priority		prio
					WHERE
						tick.id_status = stat.id_status
					AND
						tick.id_creator = ?
					AND
						user.id_user = tick.id_creator
					AND
						prio.id_priority = tick.id_priority
					ORDER BY
						id_ticket DESC
					"
					,
					array($id)
					) or die(mysql_error());

		return $db->getResults();
	}

	public function getTicketsByCustomerId($id){
		$db = Database::getInstance();

		$db->query("
					SELECT
						tick.*
					,	REPLACE(DATE_FORMAT(tick.creation_date, '%d/%m/%Y às %T'), '-', '/') as created
					,	stat.*
					,	user.name
					,	prio.*
					FROM
						tb_tickets		tick
					,	tb_status		stat
					,	tb_users 		user
					,	tb_priority		prio
					WHERE
						tick.id_status = stat.id_status
					AND
						tick.id_creator = ?
					AND
						user.id_user = tick.id_creator
					AND
						prio.id_priority = tick.id_priority
					AND
						tick.id_category = 5
					AND
						tick.id_status != 2
					ORDER BY
						id_ticket DESC
					"
					,
					array($id)
					) or die(mysql_error());

		return $db->getResults();
	}

	public function getTicketById($id){
		$db = Database::getInstance();

		$db->query("
					SELECT
						tick.*
					,	REPLACE(DATE_FORMAT(tick.creation_date, '%d/%m/%Y às %T'), '-', '/') as created
					,	user.*
					,	prio.*
					,	modu.*
					,	cate.*
					,	stat.*
					,	comp.name_company 				as company
					,	comp.email 						as comp_email
					,	comp.address 					as comp_address
					,	comp.phone_company 				as comp_phone
					,	comp.bairro_company				as comp_bairro
					,	comp.city_company				as comp_city
					,	comp.cep_company				as comp_cep
					,	comp.main_contact_company		as comp_main_contact
					,	comp.cellphone_company			as comp_cellphone
					FROM
						tb_tickets		tick
					,	tb_users		user
					,	tb_priority		prio
					,	tb_modules		modu
					,	tb_category		cate
					,	tb_status		stat
					,	tb_companies	comp
					,	tb_proposal		prop
					WHERE
						tick.id_creator = user.id_user
					AND
						tick.id_priority = prio.id_priority
					AND 
						tick.id_module = modu.id_module
					AND
						tick.id_category = cate.id_category
					AND
						tick.id_status = stat.id_status
					AND 
						tick.id_company = comp.id_company
					AND
						tick.id_ticket = ?

					"
					,
					array($id)
					) or die(mysql_error());

		return $db->getResults();
	}

	public function getTicketsByCompany($id_company, $search){
		$db = Database::getInstance();

		$db->query("
					SELECT
						tick.*
					,	REPLACE(DATE_FORMAT(tick.creation_date, '%d/%m/%Y às %T'), '-', '/') as created
					,	stat.*
					,	user.name
					,	prio.*
					FROM
						tb_tickets		tick
					,	tb_status		stat
					,	tb_users 		user
					,	tb_priority		prio
					WHERE
						tick.id_status = stat.id_status
					AND
						tick.id_company = ?
					AND
						user.id_user = tick.id_creator
					AND 
						prio.id_priority = tick.id_priority
					ORDER BY
						id_ticket DESC
					"
					,
					array($id_company)
					) or die(mysql_error());

		return $db->getResults();
	}

	public function getTicketsByCustomerCompany($id_company, $search){
		$db = Database::getInstance();

		$db->query("
					SELECT
						tick.*
					,	REPLACE(DATE_FORMAT(tick.creation_date, '%d/%m/%Y às %T'), '-', '/') as created
					,	stat.*
					,	user.name
					,	prio.*
					FROM
						tb_tickets		tick
					,	tb_status		stat
					,	tb_users 		user
					,	tb_priority		prio
					WHERE
						tick.id_status = stat.id_status
					AND
						tick.id_company = ?
					AND
						user.id_user = tick.id_creator
					AND 
						prio.id_priority = tick.id_priority
					AND
						tick.id_category = 5
					AND
						tick.id_status != 2
					ORDER BY
						id_ticket DESC
					"
					,
					array($id_company)
					) or die(mysql_error());

		return $db->getResults();
	}

	public function getAllOpenTickets(){
		$db = Database::getInstance();

		$db->query("SELECT
						tick.*
					,	REPLACE(DATE_FORMAT(tick.creation_date, '%d/%m/%Y às %T'), '-', '/') as created
					,	user.*
					,	prio.*
					,	modu.*
					,	cate.*
					,	stat.*
					,	comp.name_company 				as company
					,	comp.email 						as comp_email
					,	comp.address 					as comp_address
					,	comp.phone_company 				as comp_phone
					,	comp.bairro_company				as comp_bairro
					,	comp.city_company				as comp_city
					,	comp.cep_company				as comp_cep
					,	comp.main_contact_company		as comp_main_contact
					,	comp.cellphone_company			as comp_cellphone
					FROM
						tb_tickets		tick
					,	tb_users		user
					,	tb_priority		prio
					,	tb_modules		modu
					,	tb_category		cate
					,	tb_status		stat
					,	tb_companies	comp
					WHERE
						tick.id_creator = user.id_user
					AND
						tick.id_priority = prio.id_priority
					AND 
						tick.id_module = modu.id_module
					AND
						tick.id_category = cate.id_category
					AND
						tick.id_status = stat.id_status
					AND 
						user.id_company = comp.id_company
					AND
						tick.id_status != 2
					ORDER BY
						id_ticket DESC"
					);

		return $db->getResults();
	}

	public function getAllTickets(){
		$db = Database::getInstance();

		$db->query("SELECT
						tick.*
					,	REPLACE(DATE_FORMAT(tick.creation_date, '%d/%m/%Y às %T'), '-', '/') as created
					,	user.id_user
					,	user.name
					,	prio.*
					,	modu.*
					,	cate.*
					,	stat.*
					,	comp.name_company 		as company
					FROM
						tb_tickets		tick
					,	tb_users		user
					,	tb_priority		prio
					,	tb_modules		modu
					,	tb_category		cate
					,	tb_status		stat
					,	tb_companies	comp
					WHERE
						tick.id_creator = user.id_user
					AND
						tick.id_priority = prio.id_priority
					AND 
						tick.id_module = modu.id_module
					AND
						tick.id_category = cate.id_category
					AND
						tick.id_status = stat.id_status
					AND 
						user.id_company = comp.id_company
					ORDER BY id_ticket DESC"
					);

		return $db->getResults();
	}

	public function changeStatus($id, $status){
		$db = Database::getInstance();

		if ($status == 2) {
			$db->query("UPDATE
							tb_tickets
						SET
							id_status = ?
						,	date_closed = CURRENT_TIMESTAMP
						WHERE 
							id_ticket = ?"
						,
						array($status, $id)
						);
		} else {
			$db->query("UPDATE
						tb_tickets
					SET
						id_status = ?
					WHERE 
						id_ticket = ?"
					,
					array($status, $id)
					);
		}
	}

	public function getAssignedTickets($id){
		$db = Database::getInstance();

		$db->query("SELECT
						tick.*
					,	REPLACE(DATE_FORMAT(tick.creation_date, '%d/%m/%Y às %T'), '-', '/') as created
					,	user.id_user
					,	user.name
					,	prio.*
					,	modu.*
					,	cate.*
					,	stat.*
					,	comp.name_company 		as company
					FROM
						tb_tickets					tick
					,	tb_users					user
					,	tb_priority					prio
					,	tb_modules					modu
					,	tb_category					cate
					,	tb_status					stat
					,	tb_companies				comp
					,	tb_assigned_users_tickets	assi
					WHERE
						tick.id_creator = user.id_user
					AND
						tick.id_priority = prio.id_priority
					AND 
						tick.id_module = modu.id_module
					AND
						tick.id_category = cate.id_category
					AND
						tick.id_status = stat.id_status
					AND 
						user.id_company = comp.id_company
					AND
						assi.id_ticket = tick.id_ticket
					AND
						assi.main = 0
					AND
					(
						assi.id_user = ?
					)
					ORDER BY id_ticket DESC"
					,
					array($id)
					);

		return $db->getResults();
	}

	public function getMainAssignedTickets($id){
		$db = Database::getInstance();

		$db->query("SELECT
						tick.*
					,	REPLACE(DATE_FORMAT(tick.creation_date, '%d/%m/%Y às %T'), '-', '/') as created
					,	user.id_user
					,	user.name
					,	prio.*
					,	modu.*
					,	cate.*
					,	stat.*
					,	comp.name_company 		as company
					FROM
						tb_tickets					tick
					,	tb_users					user
					,	tb_priority					prio
					,	tb_modules					modu
					,	tb_category					cate
					,	tb_status					stat
					,	tb_companies				comp
					,	tb_assigned_users_tickets	assi
					WHERE
						tick.id_creator = user.id_user
					AND
						tick.id_priority = prio.id_priority
					AND 
						tick.id_module = modu.id_module
					AND
						tick.id_category = cate.id_category
					AND
						tick.id_status = stat.id_status
					AND 
						user.id_company = comp.id_company
					AND
						assi.id_ticket = tick.id_ticket
					AND
						assi.main = 1
					AND
					(
						assi.id_user = ?
					)
					ORDER BY id_ticket DESC"
					,
					array($id)
					);

		return $db->getResults();
	}

	public function getAllAssignedTickets($id){
		$db = Database::getInstance();

		$db->query("SELECT
						tick.*
					,	REPLACE(DATE_FORMAT(tick.creation_date, '%d/%m/%Y às %T'), '-', '/') as created
					,	user.*
					,	prio.*
					,	modu.*
					,	cate.*
					,	stat.*
					,	comp.name_company 				as company
					,	comp.email 						as comp_email
					,	comp.address 					as comp_address
					,	comp.phone_company 				as comp_phone
					,	comp.bairro_company				as comp_bairro
					,	comp.city_company				as comp_city
					,	comp.cep_company				as comp_cep
					,	comp.main_contact_company		as comp_main_contact
					,	comp.cellphone_company			as comp_cellphone
					FROM
						tb_tickets					tick
					,	tb_users					user
					,	tb_priority					prio
					,	tb_modules					modu
					,	tb_category					cate
					,	tb_status					stat
					,	tb_companies				comp
					,	tb_assigned_users_tickets	assi
					WHERE
						tick.id_creator = user.id_user
					AND
						tick.id_priority = prio.id_priority
					AND 
						tick.id_module = modu.id_module
					AND
						tick.id_category = cate.id_category
					AND
						tick.id_status = stat.id_status
					AND 
						user.id_company = comp.id_company
					AND
						assi.id_ticket = tick.id_ticket
					AND
						tick.id_status != 2
					AND
					(
						assi.id_user = ?
					)
					ORDER BY id_ticket DESC"
					,
					array($id)
					);

		return $db->getResults();
	}

	public function getTotalHours($ticket_id){
		$db = Database::getInstance();

		$db->query("SELECT 
						SUM(HOUR(hours)) AS total_hours
					FROM
						tb_timekeeping
					WHERE
						id_ticket = ?
					"
					,
					array($ticket_id)
					);

		return $db->getResults();
	}

	public function getTotalMinutes($ticket_id) {
		$db = Database::getInstance();
		
		$db->query("SELECT 
			SUM(MINUTE(hours)) AS total_minutes
		FROM
			tb_timekeeping
		WHERE
			id_ticket = ?
		"
		,
		array($ticket_id)
		);

		return $db->getResults();
	}

	public function deleteTicket($id){
		$db = Database::getInstance();

		$db->query("DELETE FROM tb_tickets WHERE id_ticket = ?", array($id));
	}

	public function searchConsultantTickets($search){
		$db = Database::getInstance();

		// var_dump($search);
		$search = trim($search);

		$db->query("SELECT
						tick.*
					,	REPLACE(DATE_FORMAT(tick.creation_date, '%d/%m/%Y às %T'), '-', '/') as created
					,	user.id_user
					,	user.name
					,	prio.*
					,	modu.*
					,	cate.*
					,	stat.*
					,	comp.name_company 		as company
					FROM
						tb_tickets		tick
					,	tb_users		user
					,	tb_priority		prio
					,	tb_modules		modu
					,	tb_category		cate
					,	tb_status		stat
					,	tb_companies	comp
					,	tb_proposal		prop
					WHERE
						tick.id_creator = user.id_user
					AND
						tick.id_priority = prio.id_priority
					AND 
						tick.id_module = modu.id_module
					AND
						tick.id_category = cate.id_category
					AND
						tick.id_status = stat.id_status
					AND 
						user.id_company = comp.id_company
					AND
						prop.id_proposal = tick.id_proposal
					AND
						(
							tick.id_ticket LIKE ?
							OR
							user.name LIKE ?
							OR
							prio.desc_priority LIKE ?
							OR
							modu.desc_module LIKE ?
							OR
							cate.desc_category LIKE ?
							OR
							stat.desc_status LIKE ?
							OR
							comp.name_company LIKE ?
							OR
							tick.subject_ticket LIKE ?
							OR
							tick.desc_ticket LIKE ?
							OR
							tick.cost_center LIKE ?
							OR
							tick.name_creator_ticket LIKE ?
							OR
							prop.name_proposal LIKE ?
						)
					ORDER BY
						id_ticket DESC"
					,
					array(
						"%$search%"
					,	"%$search%"
					,	"%$search%"
					,	"%$search%"
					,	"%$search%"
					,	"%$search%"
					,	"%$search%"
					,	"%$search%"
					,	"%$search%"
					,	"%$search%"
					,	"%$search%"
					,	"%$search%"
					)
					);

		return $db->getResults();
	}

	public function searchTicketsByUserId($search, $id_user){
		$db = Database::getInstance();

		// var_dump($search);
		$search = trim($search);

		$db->query("SELECT
						tick.*
					,	REPLACE(DATE_FORMAT(tick.creation_date, '%d/%m/%Y às %T'), '-', '/') as created
					,	user.id_user
					,	user.name
					,	prio.*
					,	modu.*
					,	cate.*
					,	stat.*
					,	comp.name_company 		as company
					FROM
						tb_tickets		tick
					,	tb_users		user
					,	tb_priority		prio
					,	tb_modules		modu
					,	tb_category		cate
					,	tb_status		stat
					,	tb_companies	comp
					WHERE
						tick.id_creator = user.id_user
					AND
						tick.id_priority = prio.id_priority
					AND 
						tick.id_module = modu.id_module
					AND
						tick.id_category = cate.id_category
					AND
						tick.id_status = stat.id_status
					AND 
						user.id_company = comp.id_company
					AND
						(
							tick.id_ticket LIKE ?
							OR
							tick.subject_ticket LIKE ?
						)
					AND
						tick.id_creator = ?
					AND
						tick.id_category = 5
					ORDER BY
						id_ticket DESC"
					,
					array(
						"%$search%"
					,	"%$search%"
					,	$id_user
					)
					);

		return $db->getResults();
	}

	public function searchTicketsByCompanyId($search, $id_company){
		$db = Database::getInstance();

		// var_dump($search);
		$search = trim($search);

		$db->query("SELECT
						tick.*
					,	REPLACE(DATE_FORMAT(tick.creation_date, '%d/%m/%Y às %T'), '-', '/') as created
					,	user.id_user
					,	user.name
					,	prio.*
					,	modu.*
					,	cate.*
					,	stat.*
					,	comp.name_company 		as company
					FROM
						tb_tickets		tick
					,	tb_users		user
					,	tb_priority		prio
					,	tb_modules		modu
					,	tb_category		cate
					,	tb_status		stat
					,	tb_companies	comp
					WHERE
						tick.id_creator = user.id_user
					AND
						tick.id_priority = prio.id_priority
					AND 
						tick.id_module = modu.id_module
					AND
						tick.id_category = cate.id_category
					AND
						tick.id_status = stat.id_status
					AND 
						user.id_company = comp.id_company
					AND
						(
							tick.id_ticket LIKE ?
							OR
							tick.subject_ticket LIKE ?
						)
					AND
						tick.id_company = ?
					AND 
						tick.id_category = 5
					ORDER BY
						id_ticket DESC"
					,
					array(
						"%$search%"
					,	"%$search%"
					,	$id_company
					)
					);

		return $db->getResults();
	}

	public function getRunningTicketsByCompany($id_company){
		$db = Database::getInstance();

		$db->query(
			"
			SELECT
				COUNT(*) as qtd
			FROM
				tb_tickets
			WHERE
				id_company = ?
			AND
				id_status != 2
			"
			,
			array(
				$id_company
			)
		);

		return $db->getResults();
	}

	public function getMonthlyTickets($id_company){
		$db = Database::getInstance();

		$db->query(
			"
			SELECT
				COUNT(*) as qtd
			FROM
				tb_tickets
			WHERE
				id_company = ?
			AND
				MONTH(creation_date) = MONTH(CURDATE())
			"
			,
			array(
				$id_company
			)
		);

		return $db->getResults();
	}

	public function getAllRecentClosedTickets($id_company){
		$db = Database::getInstance();

		$db->query("
					SELECT
						tick.*
					,	REPLACE(DATE_FORMAT(tick.creation_date, '%d/%m/%Y às %T'), '-', '/') as created
					,	stat.*
					,	user.name
					,	prio.*
					FROM
						tb_tickets		tick
					,	tb_status		stat
					,	tb_users 		user
					,	tb_priority		prio
					WHERE
						tick.id_status = stat.id_status
					AND
						tick.id_company = ?
					AND
						user.id_user = tick.id_creator
					AND 
						prio.id_priority = tick.id_priority
					AND
						tick.id_category = 5
					AND
						tick.id_status = 2
					ORDER BY
						id_ticket DESC
					LIMIT
						100
					"
					,
					array($id_company)
					) or die(mysql_error());

		return $db->getResults();
	}

	public function getMyRecentClosedTickets($id_company, $id_user){
		$db = Database::getInstance();

		$db->query("
					SELECT
						tick.*
					,	REPLACE(DATE_FORMAT(tick.creation_date, '%d/%m/%Y às %T'), '-', '/') as created
					,	stat.*
					,	user.name
					,	prio.*
					FROM
						tb_tickets		tick
					,	tb_status		stat
					,	tb_users 		user
					,	tb_priority		prio
					WHERE
						tick.id_status = stat.id_status
					AND
						tick.id_company = ?
					AND
						user.id_user = tick.id_creator
					AND 
						prio.id_priority = tick.id_priority
					AND
						tick.id_category = 5
					AND
						tick.id_status = 2
					AND
						tick.id_creator = ?
					ORDER BY
						id_ticket DESC
					LIMIT
						100
					"
					,
					array(
						$id_company
					,	$id_user
					)
					) or die(mysql_error());

		return $db->getResults();
	}

	public function emailSent($id){
		$db = Database::getInstance();

		$db->query(
			"
			UPDATE
				tb_tickets
			SET
				sent_mail = 1
			WHERE
				id_ticket = ?
			"
			,
			array(
				$id
			)
		);
	}

	public function getSentMail($id, $id_creator){
		$db = Database::getInstance();

		$db->query("
					SELECT
						tick.sent_mail
					,	user.active
					FROM
						tb_tickets		tick
					,	tb_users 		user
					WHERE
						tick.id_ticket = ?
					AND
						user.id_user = tick.id_creator
					AND
						user.id_user = ?
					"
					,
					array($id, $id_creator)
					) or die(mysql_error());

		return $db->getResults();
	}
}
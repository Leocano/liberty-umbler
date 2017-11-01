<?php 

class TimekeepingDAO{

	public function getTimekeepingTypes(){
		$db = Database::getInstance();

		$db->query("SELECT
						*
					FROM
						tb_timekeeping_type
					");

		return $db->getResults();
	}

	public function insertTimekeeping($timekeeping){
		$db = Database::getInstance();

		$id_ticket = $timekeeping->getIdTicket();
		$id_user = $timekeeping->getIdUser();
		$id_timekeeping_type = $timekeeping->getIdTimekeepingType();
		$date_timekeeping = $timekeeping->getDateTimekeeping();
		$time_teste = $timekeeping->getHours();
		$desc_timekeeping = $timekeeping->getDescTimekeeping();
		$cost_timekeeping = $timekeeping->getCostTimekeeping();
		$month = $timekeeping->getMonth();
		$time_executed = $timekeeping->getTimeExecuted();

		$db->query("INSERT
					INTO
						tb_timekeeping
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
						)"
						,
						array($id_ticket, $id_user, $id_timekeeping_type, $date_timekeeping, $desc_timekeeping, $cost_timekeeping, $time_teste, $month, $time_executed)
						);
	}

	public function getTimekeepingByTicketId($ticket_id){
		$db = Database::getInstance();

		$db->query("SELECT 
						time.* 
					,	DATE_FORMAT(time.date_timekeeping, '%d/%m/%Y') as new_date_timekeeping
					,	type.*
					,	user.name
					FROM 
						tb_timekeeping 			time
					,	tb_timekeeping_type		type
					,	tb_users				user
					WHERE
						time.id_timekeeping_type = type.id_timekeeping_type
					AND
						time.id_ticket = ?
					AND
						time.id_user = user.id_user
					ORDER BY 
						id_timekeeping ASC
					"
					,
					array($ticket_id)
					);

		return $db->getResults();
	}

	public function deleteTimekeeping($id){
		$db = Database::getInstance();

		$db->query("DELETE FROM tb_timekeeping WHERE id_timekeeping = ?", array($id));
	}
	
	public function deleteTimekeepingProduct($id){
		$db = Database::getInstance();

		$db->query("DELETE FROM tb_product_timekeeping WHERE id_timekeeping = ?", array($id));
	}

	public function updateTimekeeping($timekeeping){
		$db = Database::getInstance();

		$id_timekeeping = $timekeeping->getIdTimekeeping();
		$id_ticket = $timekeeping->getIdTicket();
		$id_user = $timekeeping->getIdUser();
		$id_timekeeping_type = $timekeeping->getIdTimekeepingType();
		$hours = $timekeeping->getHours();
		$date_timekeeping = $timekeeping->getDateTimekeeping();
		$desc_timekeeping = $timekeeping->getDescTimekeeping();
		$cost_timekeeping = $timekeeping->getCostTimekeeping();
		$month = $timekeeping->getMonth();
		$time_executed = $timekeeping->getTimeExecuted();

		$db->query("UPDATE
						tb_timekeeping
					SET
						id_user = ?
					,	id_timekeeping_type = ?
					,	date_timekeeping = ?
					,	hours = ?
					,	desc_timekeeping = ?
					,	cost_timekeeping = ?
					,	month_timekeeping = ?
					,	time_executed = ?
					WHERE
						id_timekeeping = ?
					"
					,
					array($id_user, $id_timekeeping_type, $date_timekeeping, $hours, $desc_timekeeping, $cost_timekeeping, $month, $time_executed, $id_timekeeping)
		);
	}

	public function checkAssigned($main){
		$db = Database::getInstance();

		$db->query("SELECT id_user FROM tb_timekeeping WHERE id_user IN (?)", array($main));

		return $db->getResults();
	}

	public function getDateTimekeepingById($id_timekeeping){
		$db = Database::getInstance();

		$db->query("SELECT date_timekeeping FROM tb_timekeeping WHERE id_timekeeping = ?", array($id_timekeeping));

		return $db->getResults();
	}
	
	public function getTimekeepingByProductTicketId($ticket_id){
		$db = Database::getInstance();

		$db->query("SELECT 
						time.* 
					,	DATE_FORMAT(time.date_timekeeping, '%d/%m/%Y') as new_date_timekeeping
					,	user.name
					FROM 
						tb_product_timekeeping 			time
					,	tb_users				user
					WHERE
						time.id_ticket = ?
					AND
						time.id_user = user.id_user
					ORDER BY 
						id_timekeeping ASC
					"
					,
					array($ticket_id)
					);

		return $db->getResults();
	}
}
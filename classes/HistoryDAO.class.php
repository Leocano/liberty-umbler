<?php 

class HistoryDAO{
	public function insertHistory($id_ticket, $id_user, $desc_history){
		$db = Database::getInstance();

		$db->query("INSERT INTO
							tb_history_ticket
						VALUES
						(
							null 
						,	?
						,	?
						,	?
						,	DEFAULT
						)
						"
						,
						array($id_ticket, $id_user, $desc_history)
						);
	}

	public function getHistoryByTicket($ticket_id){
		$db = Database::getInstance();

		$db->query("SELECT
						hist.desc_history
					,	REPLACE(DATE_FORMAT(hist.date_history, '%d/%m/%Y às %T'), '-', '/') as hist_date
					,	user.name
					FROM
						tb_history_ticket	hist
					,	tb_users			user
					WHERE
						user.id_user = hist.id_user
					AND
						hist.id_ticket = ?
					ORDER BY id_history DESC
					"
					,
					array($ticket_id)
					);

		return $db->getResults();
	}

	public function insertHistoryProduct($id_ticket, $id_user, $desc_history){
		$db = Database::getInstance();

		$db->query("INSERT INTO
							tb_history_products
						VALUES
						(
							null 
						,	?
						,	?
						,	?
						,	DEFAULT
						)
						"
						,
						array($id_ticket, $id_user, $desc_history)
						);
	}
}
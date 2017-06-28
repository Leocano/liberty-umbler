<?php 

class ReportDAO{
	public static function storeReport($id_creator, $name, $query, $cols, $grouping, $sum, $hour_pos, $fields, $timespan, $persistent_grouping, $json_filters, $date_field){
		$db = Database::getInstance();

		$db->query("INSERT INTO
						tb_reports
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
					, 	CURRENT_TIMESTAMP
					,	?
					,	?
					,	?
					,	?
					)"
					,
					array($id_creator, $name, $query, $cols, $grouping, $sum, $hour_pos, $fields, $timespan, $persistent_grouping, $json_filters, $date_field)
					);
		return $db->getLastInsertId();
	}

	public static function updateReport($id_report, $name, $query, $cols, $grouping, $sum, $hour_pos, $fields, $timespan, $persistent_grouping, $date_field, $json_filters){
		$db = Database::getInstance();

		$db->query("UPDATE
						tb_reports
					SET
						name_report = ?
					,	query_report = ?
					,	columns_report = ?
					,	col_to_group_report = ?
					,	sum_report = ?
					,	hour_pos_report = ?
					,	edit_fields_report = ?
					,	timespan_report = ?
					,	persistent_group_report = ?
					,	date_field_report = ?
					,	json_filters_report = ?
					WHERE
						id_report = ?"
					,
					array($name, $query, $cols, $grouping, $sum, $hour_pos, $fields, $timespan, $persistent_grouping, $date_field, $json_filters, $id_report)
					);
	}

	public function getReportById($id_report){
		$db = Database::getInstance();

		$db->query("SELECT
						repo.*
					,	REPLACE(DATE_FORMAT(repo.creation_date_report, '%d/%m/%Y às %T'), '-', '/') as created
					,	user.name
					FROM
						tb_reports repo
					,	tb_users   user
					WHERE
						id_report = ?
					AND
						user.id_user = repo.id_creator_report
					"
					,
					array($id_report)
					);
		return $db->getResults();
	}

	public function getAllReports(){
		$db = Database::getInstance();

		$db->query("SELECT
						repo.*
					,	REPLACE(DATE_FORMAT(repo.creation_date_report, '%d/%m/%Y às %T'), '-', '/') as created
					,	user.name
					FROM
						tb_reports 		repo
					,	tb_users   		user
					WHERE
						user.id_user = repo.id_creator_report
					ORDER BY user.name ASC
					");

		return $db->getResults();
	}

	public function deleteReport($id_report){
		$db = Database::getInstance();

		$db->query("DELETE FROM tb_view_report WHERE id_report = ?", array($id_report));
		$db->query("DELETE FROM tb_view_report_companies WHERE id_report = ?", array($id_report));
		$db->query("DELETE FROM tb_reports WHERE id_report = ?", array($id_report));
	}

	public function getMyReports($id_user){
		$db = Database::getInstance();

		$db->query(
			"
			SELECT
				repo.*
			,	REPLACE(DATE_FORMAT(repo.creation_date_report, '%d/%m/%Y às %T'), '-', '/') as created
			,	user.name
			FROM
				tb_reports		repo
			,	tb_view_report  view
			,	tb_users		user
			WHERE
				view.id_user = ?
			AND
				repo.id_creator_report = user.id_user
			AND
				repo.id_report = view.id_report
			"
			,
			array(
				$id_user
			)
		);

		return $db->getResults();
	}

	public static function setViewUsers($id_users = array(), $id_report){
		$db = Database::getInstance();

		$db->query(
			"
			DELETE FROM
				tb_view_report
			WHERE
				id_report = ?
			"
			,
			array(
				$id_report
			)
		);

		foreach ($id_users as $id_user) {
			$db->query(
				"
				INSERT INTO
					tb_view_report
				VALUES
				(
					?
				,	?
				)
				"
				,
				array(
					$id_report
				,	$id_user
				)
			);
		}
	}

	public function getViewByReport($id_report){
		$db = Database::getInstance();

		$db->query(
			"
			SELECT
				user.*
			FROM
				tb_users		user
			,	tb_view_report  view
			WHERE
				user.id_user = view.id_user
			AND
				view.id_report = ?
			"
			,
			array(
				$id_report
			)
		);

		return $db->getResults();
	}

	public static function deleteCompanyView($id_report){
		$db = Database::getInstance();

		$db->query(
			"
			DELETE FROM
				tb_view_report_companies
			WHERE
				id_report = ?
			"
			,
			array(
				$id_report
			)
		);
	}

	public static function addCompany($id_company, $id_report){
		$db = Database::getInstance();

		$db->query(
			"
			INSERT INTO
				tb_view_report_companies
			VALUES
			(
				?
			,	?
			)
			"
			,
			array(
				$id_company
			,	$id_report
			)
		);
	}

	public function getCompanyViews($id_report){
		$db = Database::getInstance();

		$db->query(
			"
			SELECT
				*
			FROM
				tb_view_report_companies
			WHERE
				id_report = ?
			"
			,
			array(
				$id_report
			)
		);

		return $db->getResults();
	}

	public function getCompanyReports($id_company){
		$db = Database::getInstance();

		$db->query(
			"
			SELECT
				repo.*
			,	REPLACE(DATE_FORMAT(repo.creation_date_report, '%d/%m/%Y às %T'), '-', '/') as created
			FROM
				tb_reports 					repo
			,	tb_view_report_companies	view
			WHERE
				repo.id_report = view.id_report
			AND
				view.id_company = ?
			"
			,
			array(
				$id_company
			)
		);

		return $db->getResults();
	}
}
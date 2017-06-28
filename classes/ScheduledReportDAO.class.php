<?php 

class ScheduledReportDAO{
	public function scheduleReport($id_report, $frequency){
		$db = Database::getInstance();

		$db->query(
			"
			INSERT INTO
				tb_scheduled_reports
			VALUES
			(
				?
			,	?
			)
			"
			,
			array(
				$id_report
			,	$frequency
			)
		);
	}

	public function getDailyReports(){
		$db = Database::getInstance();

		$db->query(
			"
			SELECT
				repo.*
			,	user.*
			,	REPLACE(DATE_FORMAT(repo.creation_date_report, '%d/%m/%Y às %T'), '-', '/') as created
			FROM
				tb_reports 				repo
			,	tb_scheduled_reports	sche
			,	tb_users 				user
			WHERE
				repo.id_report = sche.id_report
			AND
				sche.frequency_scheduled_report = 'daily'
			AND
				user.id_user = repo.id_creator_report
			"
		);

		return $db->getResults();
	}
}
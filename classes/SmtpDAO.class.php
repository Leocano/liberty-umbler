<?php 

class SmtpDAO{
	public static $server;
	public static $port;

	public static function getSmtpData(){
		$db = Database::getInstance();
		$db->query("SELECT * FROM tb_smtp");
		$smtp = $db->getResults();

		self::$server = $smtp[0]->smtp_server;
		self::$port = $smtp[0]->smtp_port;
	}
}
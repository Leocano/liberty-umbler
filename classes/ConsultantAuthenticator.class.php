<?php 

class ConsultantAuthenticator extends UserAuthenticator{

	public function authenticate($user){
		SmtpDAO::getSmtpData();

		$email = $user->getEmail();
		$password = $user->getPassword();

		$db = Database::getInstance();
		$db->query("SELECT active FROM tb_users WHERE email = ?", array($email));

		$info = $db->getResults();
		$status = $info[0]->active;

		if ($status == 1 && imap_open("{" . SmtpDAO::$server . ":" . SmtpDAO::$port . "/pop3/novalidate-cert}INBOX", "$email", "$password", 0, 1)){
			return true;
		} else {
			return false;
		}
	}
}
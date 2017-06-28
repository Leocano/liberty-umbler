<?php 

class CustomerAuthenticator extends UserAuthenticator{

	public function authenticate($user){
		$login = $user->getLogin();
		$password = $user->getPassword();

		$db = Database::getInstance();

		$db->query("SELECT * FROM tb_users_password WHERE login = ? AND password = ? LIMIT 1", array($login, $password));
		$info = $db->getResults();


		$id_user = $info[0]->id_user;
		$db->query("SELECT active FROM tb_users WHERE id_user = ?", array($id_user));
		$active = $db->getResults();

		if ($info == null || $active[0]->active != 1){
			return false;
		} else {
			$user->setIdUser($info[0]->id_user);

			return true;
		}
	}
}
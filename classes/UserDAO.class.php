<?php 

class UserDAO{
	public function setUserData($user, $dataObj){
		foreach ($dataObj as $data) {
			$user->setIdUser($data->id_user);
			$user->setIdUserType($data->id_user_type);

			$company = new Company($data->id_company, $data->comp_name, $data->is_customer);
			$user->setCompany($company);

			$profile = new Profile($data->id_profile, $data->id_profile_type, $data->desc_profile);
			$user->setProfile($profile);

			$view = new View($data->id_view, $data->desc_view);
			$user->setView($view);

			$user->setName($data->name);
			$user->setPassword(null);
			$user->setEmail($data->email);
			$user->setAlternativeEmail($data->alternative_email);
			$user->setCellphone($data->cellphone);
			$user->setPhone($data->phone);
			$user->setRole($data->role);
			$user->setStatus($data->active);
		}
	}

	public function searchEmail($email){
		$db = Database::getInstance();

		$db->query("SELECT email from tb_users where email = ? LIMIT 1", array($email));

		return $db->getResults();
	}

	public function getAllUsers(){
		$db = Database::getInstance();

		$db->query("SELECT
						user.*
					,	comp.name_company
					FROM
						tb_users		user
					,	tb_companies	comp
					WHERE
						user.id_company = comp.id_company
					");

		return $db->getResults();
	}

	public function getUsersByCompany($id_company){
		$db = Database::getInstance();

		$db->query("SELECT
						user.*
					,	comp.name_company
					FROM
						tb_users		user
					,	tb_companies	comp
					WHERE
						user.id_company = comp.id_company
					AND
						user.id_company = ?
					ORDER BY
						user.name DESC
					"
					,
					array($id_company)
					);

		return $db->getResults();
	}
}
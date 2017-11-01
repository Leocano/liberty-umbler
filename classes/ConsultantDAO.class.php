<?php 

class ConsultantDAO extends UserDAO{
	public function getConsultantData($user){
		$db = Database::getInstance();
		$email = $user->getEmail();

		$db->query("CALL getConsultantData(?);", array($email));
		$consultantData = $db->getResults();

		$this->setUserData($user, $consultantData);
		$user->setCode($consultantData[0]->code);
		$user->setCost($consultantData[0]->cost);
	}

	public function createNewConsultant($consultant, $area){
		$db = Database::getInstance();

		$id_user = null;
		$id_user_type = $consultant->getIdUserType();
		$company = $consultant->getCompany();
		$profile = $consultant->getProfile();
		$view = 1;
		$name = $consultant->getName();
		$email = $consultant->getEmail();
		$alt_email = $consultant->getAlternativeEmail();
		$cellphone  = $consultant->getCellphone();
		$role = $consultant->getRole();
		$phone = $consultant->getPhone();
		$code = $consultant->getCode();
		$cost = $consultant->getCost();
		$contract = $consultant->getContract();
		
		$db->query("INSERT INTO tb_users VALUES 
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
				    ,	?
				    ,	?
				    ,	?
				    ,	DEFAULT
				    ,	?
				    ,	?
				    )", array($id_user_type, $company, $profile, $view, $name, $email, $alt_email, $cellphone, $role, $phone, $code, $cost, $area, $contract)) or die(mysql_error());
	}

	public function getAllConsultants(){
		$db = Database::getInstance();

		$db->query("SELECT 
						user.id_user
					,	user.name
					,	user.email
					,	user.active
					,	user.role
					,	prof.desc_profile
					FROM
						tb_users					user
					,	tb_profiles					prof
					WHERE
						id_user_type = 1
					AND
						user.id_profile = prof.id_profile
					");
		$consultants = $db->getResults();

		return $consultants;
	}
	
		public function getProductConsultants(){
			$db = Database::getInstance();
	
			$db->query("SELECT 
							user.id_user
						,	user.name
						,	user.email
						,	user.active
						,	user.role
						,	prof.desc_profile
						FROM
							tb_users					user
						,	tb_profiles					prof
						WHERE
							id_user_type = 1
						AND
							user.id_profile = prof.id_profile
						AND
							user.area_user = 5
						");
			$consultants = $db->getResults();
	
			return $consultants;
		}

	public function searchConsultant($search){
		$db = Database::getInstance();

		$db->query("SELECT
						id_user
					,	name
					,	email
					,	active
					FROM 
						tb_users 
					WHERE
						id_user_type = 1
					AND	(
							name  LIKE ?
						OR  email LIKE ?
						)"
					, array("%$search%", "%$search%"));

		$consultants = $db->getResults();

		return $consultants;
	}

	public function deleteConsultant($id){
		$db = Database::getInstance();

		$db->query("DELETE FROM tb_users WHERE id_user = ?", array($id));
	}

	public function getConsultantById($id){
		$db = Database::getInstance();

		$db->query("SELECT
						*
					FROM
						tb_users
					WHERE
						id_user 	=	?
					AND
						id_user_type = 1" 
					,
					array($id));
		$consultant = $db->getResults();

		return $consultant;
	}

	public function updateConsultant($consultant, $area){
		$db = Database::getInstance();

		$id_user = $consultant->getIdUser();
		$id_user_type = $consultant->getIdUserType();
		$company = $consultant->getCompany();
		$profile = $consultant->getProfile();
		// $view = $consultant->getView();
		$name = $consultant->getName();
		$email = $consultant->getEmail();
		$alt_email = $consultant->getAlternativeEmail();
		$cellphone  = $consultant->getCellphone();
		$role = $consultant->getRole();
		$phone = $consultant->getPhone();
		$code = $consultant->getCode();
		$cost = $consultant->getCost();
		$contract = $consultant->getContract();

		$db->query("UPDATE 
						tb_users
					SET
						id_user_type = ?
					,	id_company = ?
					,	id_profile = ?
					,	area_user = ?
					,	name = ?
					,	email = ?
					,	alternative_email = ?
					,	cellphone = ?
					,	role = ?
					,	phone = ?
					,	code = ?
					,	cost = ?
					,	id_contract = ?
					WHERE 
						id_user = ?"
					, 
					array($id_user_type, $company, $profile, $area, $name, $email, $alt_email, $cellphone, $role, $phone, $code, $cost, $contract, $id_user)
					);
	}

	public function updateStatus($id, $status){
		$db = Database::getInstance();

		$db->query("UPDATE tb_users SET active = ? WHERE id_user = ?", array($status, $id));
	}

	public function getUsersByTicket($id_ticket){
		$db = Database::getInstance();

		$db->query("SELECT
						user.*
					FROM
						tb_users user
					,	tb_assigned_users_tickets assi
					WHERE
						assi.id_user = user.id_user
					AND
						assi.id_ticket = ?
					"
					,
					array($id_ticket)
					);

		return $db->getResults();
	}

	public function getUsersByProductTicket($id_ticket){
		$db = Database::getInstance();

		$db->query("SELECT
						user.*
					FROM
						tb_users user
					,	tb_product_assignments assi
					WHERE
						assi.id_user = user.id_user
					AND
						assi.id_ticket = ?
					"
					,
					array($id_ticket)
					);

		return $db->getResults();
	}

	public function getAllManagers(){
		$db = Database::getInstance();

		$db->query("SELECT
						*
					FROM
						tb_users
					WHERE
						id_profile = 2
					OR 
						id_profile = 3");
		return $db->getResults();
	}
}
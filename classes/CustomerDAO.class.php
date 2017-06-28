<?php 

class CustomerDAO extends UserDAO{
	public function getCustomerData($user){
		$db = Database::getInstance();
		$id_user = $user->getIdUser();

		$db->query("CALL getCustomerData(?);", array($id_user));
		$customerData = $db->getResults();

		$this->setUserData($user, $customerData);
	}

	public function createNewCustomer($customer){
		$db = Database::getInstance();

		$id_user = null;
		$id_user_type = $customer->getIdUserType();
		$company = $customer->getCompany();
		$profile = $customer->getProfile();
		$view = $customer->getView();
		$name = $customer->getName();
		$email = $customer->getEmail();
		$alt_email = $customer->getAlternativeEmail();
		$cellphone  = $customer->getCellphone();
		$role = $customer->getRole();
		$phone = $customer->getPhone();

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
				    ,	null
				    ,	null
				    ,	DEFAULT
				    ,	null
				    ,	null
				    )", array($id_user_type, $company, $profile, $view, $name, $email, $alt_email, $cellphone, $role, $phone)) or die(mysql_error());


		$lastInsertId = $db->getLastInsertId();
		$login = $customer->getLogin();
		$password = $customer->getPassword();

		if ($password != "" && $login != ""){
			$db->query("INSERT INTO tb_users_password VALUES
						(
							?,
							?,
							?
						)", array($lastInsertId, $login, $password));
		}
	}

	public function getAllCustomers(){
		$db = Database::getInstance();

		$db->query("CALL getAllCustomers()");
		$customers = $db->getResults();

		return $customers;
	}

	public function searchCustomer($search){
		$db = Database::getInstance();

		$db->query("SELECT
						comp.name_company 	as company
	 				, 	user.*
					FROM 
						tb_users 		user
					,	tb_companies	comp
					WHERE
						id_user_type = 2
					AND 
						comp.id_company = user.id_company
					AND	(
							user.name  LIKE ?
						OR  user.email LIKE ?
						)
					ORDER BY user.name ASC
						"
					, array("%$search%", "%$search%"));

		$customers = $db->getResults();

		return $customers;
	}

	public function deleteCustomer($id){
		$db = Database::getInstance();

		$db->query("DELETE FROM tb_users_password WHERE id_user = ?", array($id));
		$db->query("DELETE FROM tb_users WHERE id_user = ? AND id_user_type = 2", array($id));
	}

	public function getCustomerById($id){
		$db = Database::getInstance();

		$db->query("SELECT
						*
					FROM
						tb_users 			user
					WHERE
						user.id_user 	=	?
					AND
						user.id_user_type = 2" , array($id));
		$customer = $db->getResults();

		return $customer;
	}

	public function getCustomerLogin($id){
		$db = Database::getInstance();

		$db->query(
			"
			SELECT
				*
			FROM
				tb_users_password
			WHERE
				id_user = ?
			"
			,
			array(
				$id
			)
		);

		return $db->getResults();
	}

	public function updateCustomer($customer){
		$db = Database::getInstance();

		$id_user = $customer->getIdUser();
		$id_user_type = $customer->getIdUserType();
		$company = $customer->getCompany();
		$view = $customer->getView();
		$name = $customer->getName();
		$email = $customer->getEmail();
		$alt_email = $customer->getAlternativeEmail();
		$cellphone  = $customer->getCellphone();
		$role = $customer->getRole();
		$phone = $customer->getPhone();

		$db->query("UPDATE 
						tb_users
					SET
						id_user_type = ?
					,	id_company = ?
					,	id_view = ?
					,	name = ?
					,	email = ?
					,	alternative_email = ?
					,	cellphone = ?
					,	role = ?
					,	phone = ?
					WHERE 
						id_user = ?"
					, 
					array($id_user_type, $company, $view, $name, $email, $alt_email, $cellphone, $role, $phone, $id_user)
					);

		// $login = $customer->getLogin();
		// $password = $customer->getPassword();

		// $db->query("UPDATE 
		// 				tb_users_password
		// 			SET
		// 				login = ?
		// 			,	password = ?
		// 			WHERE 
		// 				id_user = ?"
		// 			, 
		// 			array($login, $password, $id_user)
		// 			);
	}

	public function updateStatus($id, $status){
		$db = Database::getInstance();

		$db->query("UPDATE tb_users SET active = ? WHERE id_user = ?", array($status, $id));
	}

	public function updatePassword($id_user, $new_password){
		$db = Database::getInstance();

		$db->query("UPDATE
						tb_users_password
					SET
						password = ?
					WHERE
						id_user = ?"
					,
					array($new_password, $id_user)
					);
	}

	public function deleteLoginInformation($id_user){
		$db = Database::getInstance();

		$db->query(
			"
			DELETE FROM
				tb_users_password
			WHERE
				id_user = ?
			"
			,
			array(
				$id_user
			)
		);
	}

	public function createNewLogin($id_user, $login, $password){
		$db = Database::getInstance();

		$db->query(
			"
			INSERT INTO
				tb_users_password
			VALUES
				(
					?
				,	?
				,	?
				)
			"
			,
			array(
				$id_user
			,	$login
			,	$password
			)
		);
	}

	public function updateLogin($id_user, $login, $password){
		$db = Database::getInstance();

		$db->query(
			"
			UPDATE
				tb_users_password
			SET
				login = ?
			,	password = ?
			WHERE
				id_user = ?
			"
			,
			array(
				$login
			,	$password
			,	$id_user
			)
		);
	}
}
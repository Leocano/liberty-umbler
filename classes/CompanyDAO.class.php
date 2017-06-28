<?php 

class CompanyDAO{
	public function getAllCompanies(){
		$db = Database::getInstance();

		$db->query("SELECT * FROM tb_companies ORDER BY name_company ASC");
		$companies = $db->getResults();

		return $companies;
	}

	public function getCompanyById($id){
		$db = Database::getInstance();

		$db->query("SELECT * FROM tb_companies WHERE id_company = ? LIMIT 1", array($id));
		$company = $db->getResults();
		
		return $company;
	}

	public function createNewCompany($company){
		$db = Database::getInstance();

		$name = $company->getName();
		$email = $company->getEmail();
		$address = $company->getAddress();
		$is_customer = $company->getIsCustomer();
		$phone = $company->getPhone();
		$cellphone = $company->getCellphone();
		$city = $company->getCity();
		$bairro = $company->getBairro();
		$cep = $company->getCep();
		$contato_principal = $company->getContatoPrincipal();

		$db->query("INSERT INTO 
						tb_companies
					VALUES 
					(
						null
					,	?
					,	?
					,	?
					,	?
					,	DEFAULT
					,	?
					,	?
					,	?
					,	?
					,	?
					,	?
					)
					"
					,
					array($name, $is_customer, $email, $address, $phone, $cellphone, $city, $bairro, $cep, $contato_principal)
					);
	}

	public function updateStatus($id, $status){
		$db = Database::getInstance();

		$db->query("UPDATE
						tb_companies
					SET
						active = ?
					WHERE 
						id_company = ?"
						,
						array($status, $id)
						);

		$db->query("UPDATE
						tb_users
					SET
						active = ?
					WHERE 
						id_company = ?"
						,
						array($status, $id)
						);
	}

	// public function searchCompany($search){
	// 	$db = Database::getInstance();

	// 	$db->query("SELECT
	// 					*
	// 				FROM 
	// 					tb_companies
	// 				WHERE
	// 					name_company  LIKE ?"
	// 				, array("%$search%"));

	// 	$customers = $db->getResults();

	// 	return $customers;
	// }

	public function updateCompany($company){
		$db = Database::getInstance();

		$id = $company->getIdCompany();
		$name = $company->getName();
		$is_customer = $company->getIsCustomer();
		$email = $company->getEmail();
		$address = $company->getAddress();
		$phone = $company->getPhone();
		$cellphone = $company->getCellphone();
		$city = $company->getCity();
		$bairro = $company->getBairro();
		$cep = $company->getCep();
		$contato_principal = $company->getContatoPrincipal();

		$db->query("UPDATE
						tb_companies
					SET 
						name_company = ?
					,	is_customer = ?
					,	email = ?
					,	address = ?
					,	phone_company = ?
					,	cellphone_company = ?
					,	city_company = ?
					,	bairro_company = ?
					,	cep_company = ?
					,	main_contact_company = ?
					WHERE 
						id_company = ?"
					,
					array($name, $is_customer, $email, $address, $phone, $cellphone, $city, $bairro, $cep, $contato_principal, $id)
					);
	}
}
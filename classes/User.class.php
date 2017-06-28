<?php 

abstract class User{
	private $id_user;
	private $id_user_type;
	private $company;
	private $profile;
	private $view;
	private $name;
	private $password;
	private $email;
	private $alternative_email;
	private $cellphone;
	private $role;
	private $phone;
	private $status;
	private $contract;

	public abstract function login();

	public static function isLoggedIn(){
		if ($_SESSION['loggedIn'] === true){
			return true;
		} else {
			return false;
		}
	}

	public function checkProfile($id_profile = array()){
		foreach ($id_profile as $id) {
			if ($this->profile->getIdProfile() == $id){
				$permission = true;
				break;
			} else {
				$permission = false;
			}
		}
		
		return $permission;
	}

	public function logout(){
		$_SESSION['loggedIn'] = false;
		session_destroy();
		Redirect::to("index.php");
	}

	public function setContract($contract){
		$this->contract = $contract;
	}

	public function getContract(){
		return $this->contract;
	}

	public function setIdUser($id_user){
		$this->id_user = $id_user;
	}

	public function getIdUser(){
		return $this->id_user;
	}

	public function setIdUserType($id_user_type){
		$this->id_user_type = $id_user_type;
	}

	public function getIdUserType(){
		return $this->id_user_type;
	}

	public function setCompany($company){
		$this->company = $company;
	}

	public function getCompany(){
		return $this->company;
	}

	public function setProfile($profile){
		$this->profile = $profile;
	}

	public function getProfile(){
		return $this->profile;
	}

	public function setView($view){
		$this->view = $view;
	}

	public function getView(){
		return $this->view;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getName(){
		return $this->name;
	}

	public function setPassword($password){
		$this->password = $password;
	}

	public function getPassword(){
		return $this->password;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setAlternativeEmail($alternative_email){
		$this->alternative_email = $alternative_email;
	}

	public function getAlternativeEmail(){
		return $this->alternative_email;
	}

	public function setCellphone($cellphone){
		$this->cellphone = $cellphone;
	}

	public function getCellphone(){
		return $this->cellphone;
	}

	public function setRole($role){
		$this->role = $role;
	}

	public function getRole(){
		return $this->role;
	}

	public function setPhone($phone){
		$this->phone = $phone;
	}

	public function getPhone(){
		return $this->phone;
	}

	public function setStatus($status){
		$this->status = $status;
	}

	public function getStatus(){
		return $this->status;
	}
}
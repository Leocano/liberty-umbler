<?php 

class UserCustomer extends User{
	private $login;

	public function __construct($login, $password){
		$this->login = $login;
		$this->setPassword($password);
		$this->setIdUserType(2);
	}

	public function getLogin(){
		return $this->login;
	}

	public function login(){
		$dao = new CustomerDAO;
		$dao->getCustomerData($this);
		$_SESSION['user'] = $this;
		$_SESSION['loggedIn'] = true;
		Redirect::to("home.php");
	}
}
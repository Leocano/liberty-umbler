<?php 

class UserConsultant extends User{
	private $code;
	private $cost;

	public function __construct($login, $password){
		$this->setEmail($login);
		$this->setPassword($password);
		$this->setIdUserType(1); 
	}

	public function login(){
		$dao = new ConsultantDAO;
		$dao->getConsultantData($this);

		$_SESSION['user'] = $this;
		$_SESSION['loggedIn'] = true;
		Redirect::to("home.php");
	}

	public function setCode($code){
		$this->code = $code;
	}

	public function getCode(){
		return $this->code;
	}

	public function setCost($cost){
		$this->cost = $cost;
	}

	public function getCost(){
		return $this->cost;
	}
}
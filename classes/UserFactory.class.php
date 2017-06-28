<?php 

class UserFactory{
	static function factory($userType, $login, $password){
		switch($userType){
			case "Customer" :
				return new UserCustomer($login, $password);
				break;
			case "Consultant" :
				return new UserConsultant($login, $password);
				break;
		}
	}
}
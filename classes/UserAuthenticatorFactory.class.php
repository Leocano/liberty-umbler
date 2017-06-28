<?php 

class UserAuthenticatorFactory{
	function factory($user){
		switch(true){
			case $user instanceof UserConsultant:
				return new ConsultantAuthenticator();
				break;
			case $user instanceof UserCustomer:
				return new CustomerAuthenticator();
				break;
		}
	}
}
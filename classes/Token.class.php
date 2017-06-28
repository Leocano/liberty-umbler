<?php 

// Token.class.php
//	Classe utilizada para gerenciar os tokens de segurança

class Token{
	public static function generateToken(){
		$_SESSION['token'] = md5(microtime());
	}

	public static function validateToken($token){
		if(!isset($_SESSION['token']) || $token != $_SESSION['token']){
			Redirect::to('https://letnis1.websiteseguro.com/support-center-new/index.php');
		}
	}

	public static function destroyToken(){
		unset($_SESSION['token']);
	}
}
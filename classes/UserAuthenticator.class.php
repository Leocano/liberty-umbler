<?php 

abstract class UserAuthenticator{
	public abstract function authenticate($user);
}
<?php 

class Validator{
	public static function isEmpty($values = array()){
		foreach ($values as $value) {
			if ($value == ""){
				return true;
			}
		}
		return false;
	}
}
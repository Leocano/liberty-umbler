<?php 

class Debug{
	public static function kill($target){
		echo "<pre>";
		var_dump($target);
		echo "</pre>";
	}
}
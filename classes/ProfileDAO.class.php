<?php 

class ProfileDAO{
	public function getAllProfiles(){
		$db = Database::getInstance();

		$db->query("SELECT * FROM tb_profiles WHERE id_profile_type = 1");
		$profiles = $db->getResults();

		return $profiles;
	}
}
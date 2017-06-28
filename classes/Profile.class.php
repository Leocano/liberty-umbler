<?php 

class Profile{
	private $id_profile;
	private $id_profile_type;
	private $desc_profile;

	public function __construct($id_profile, $id_profile_type, $desc_profile){
		$this->id_profile = $id_profile;
		$this->id_profile_type = $id_profile_type;
		$this->desc_profile = $desc_profile;
	}

	public function getIdProfile(){
		return $this->id_profile;
	}
}
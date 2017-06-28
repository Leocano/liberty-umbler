<?php 

class View{
	private $id_view;
	private $desc_view;

	public function __construct($id_view, $desc_view){
		$this->id_view = $id_view;
		$this->desc_view = $desc_view;
	}

	public function getIdView(){
		return $this->id_view;
	}
}
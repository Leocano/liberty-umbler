<?php 

class Priority{
	private $id;
	private $desc;

	public function construct($id, $desc){
		$this->id = $id;
		$this->desc = $desc;
	}
}
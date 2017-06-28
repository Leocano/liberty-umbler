<?php 

class Attachment{
	private $id_attachment;
	private $id_ticket;
	private $path;

	public function __construct($id_attachment, $id_ticket, $path){
		$this->id_attachment = $id_attachment;
		$this->id_ticket = $id_ticket;
		$this->path = $path;
	}
}
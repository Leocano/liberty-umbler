<?php 

class Solution {
	private $id_solution;
	private $id_ticket;
	private $id_user;
	private $desc_solution;
	private $date_solution;

	public function __construct($id_solution, $id_ticket, $id_user, $desc_solution, $date_solution){
		$this->id_solution = $id_solution;
		$this->id_ticket = $id_ticket;
		$this->id_user = $id_user;
		$this->desc_solution = $desc_solution;
		$this->date_solution = $date_solution;
	}

	public function getIdSolution(){
		return $this->id_solution;
	}

	public function getIdTicket(){
		return $this->id_ticket;
	}

	public function getIdUser(){
		return $this->id_user;
	}

	public function getDescSolution(){
		return $this->desc_solution;
	}

	public function getDateSolution(){
		return $this->date_solution;
	}
}
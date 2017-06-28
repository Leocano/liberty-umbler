<?php 

class Proposal{
	private $id_proposal;
	private $id_company;
	private $id_user;
	private $id_proposal_type;
	private $name_proposal;
	private $desc_proposal;
	private $id_status;
	private $hours;
	private $start;
	private $end;
	private $percentage;
	private $months;

	public function __construct($id_company, $id_user, $id_proposal_type, $name_proposal, $desc_proposal, $id_status){
		$this->id_company = $id_company;
		$this->id_user = $id_user;
		$this->id_proposal_type = $id_proposal_type;
		$this->name_proposal = $name_proposal;
		$this->desc_proposal = $desc_proposal;
		$this->id_status = $id_status;
	}

	public function setMonths($months){
		$this->months = $months;
	}

	public function getMonths(){
		return $this->months;
	}

	public function setPercentage($percentage){
		$this->percentage = $percentage;
	}

	public function getPercentage(){
		return $this->percentage;
	}

	public function setEnd($end){
		$this->end = $end;
	}

	public function getEnd(){
		return $this->end;
	}

	public function setStart($start){
		$this->start = $start;
	}

	public function getStart(){
		return $this->start;
	}

	public function setHours($hours){
		$this->hours = $hours;
	}

	public function getHours(){
		return $this->hours;
	}

	public function getIdProposal(){
		return $this->id_proposal;
	}

	public function getIdCompany(){
		return $this->id_company;
	}

	public function getIdUser(){
		return $this->id_user;
	}

	public function getIdProposalType(){
		return $this->id_proposal_type;
	}

	public function getNameProposal(){
		return $this->name_proposal;
	}

	public function getDescProposal(){
		return $this->desc_proposal;
	}

	public function getIdStatus(){
		return $this->id_status;
	}
}
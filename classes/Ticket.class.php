<?php 

class Ticket{
	private $id_ticket;
	private $id_creator;
	private $id_priority;
	private $id_module;
	private $id_category;
	private $id_company;
	private $subject;
	private $desc;
	private $cost_center;
	private $proposal;
	private $name_creator;
	private $external_number;
	private $id_subcategory;

	public function __construct($id_ticket, $id_creator, $id_priority, $id_module, $id_category, $subject, $desc, $cost_center){
		$this->id_ticket = $id_ticket;
		$this->id_creator = $id_creator;
		$this->id_priority = $id_priority;
		$this->id_module = $id_module;
		$this->id_category = $id_category;
		$this->subject = $subject;
		$this->desc = $desc;
		$this->cost_center = $cost_center;
	}

	public function getIdSubcategory(){
		return $this->id_subcategory;
	}

	public function setIdSubcategory($id_subcategory){
		if ($id_subcategory == ""){
			$this->id_subcategory = null;
		} else {
			$this->id_subcategory = $id_subcategory;
		}
	}

	public function getExternalNumber(){
		return $this->external_number;
	}

	public function setExternalNumber($external_number){
		$this->external_number = $external_number;
	}

	public function setNameCreator($name_creator){
		$this->name_creator = $name_creator;
	}

	public function getNameCreator(){
		return $this->name_creator;
	}

	public function setIdCompany($id_company){
		$this->id_company = $id_company;
	}

	public function getIdCompany(){
		return $this->id_company;
	}

	public function setProposal($proposal){
		$this->proposal = $proposal;
	}

	public function getProposal(){
		return $this->proposal;
	}

	public function setIdTicket($id_ticket){
		$this->id_ticket = $id_ticket;
	}

	public function getIdTicket(){
		return $this->id_ticket;
	}

	public function setIdCreator($id_creator){
		$this->id_creator = $id_creator;
	}

	public function getIdCreator(){
		return $this->id_creator;
	}

	public function setIdPriority($id_priority){
		$this->id_priority = $id_priority;
	}

	public function getIdPriority(){
		return $this->id_priority;
	}

	public function setIdModule($id_module){
		$this->id_module = $id_module;
	}

	public function getIdModule(){
		return $this->id_module;
	}

	public function setIdCategory($id_category){
		$this->id_category = $id_category;
	}

	public function getIdCategory(){
		return $this->id_category;
	}

	public function setSubject($subject){
		$this->subject = $subject;
	}

	public function getSubject(){
		return $this->subject;
	}

	public function setDesc($desc){
		$this->desc = $desc;
	}

	public function getDesc(){
		return $this->desc;
	}

	public function setCostCenter($cost_center){
		$this->cost_center = $cost_center;
	}

	public function getCostCenter(){
		return $this->cost_center;
	}
}
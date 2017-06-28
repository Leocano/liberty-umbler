<?php 

class Report{
	private $fields;
	private $condition;
	private $criteria;
	private $parameter;
	private $connector;
	private $grouping;
	private $date_field;
	private $date_from;
	private $date_to;

	public function __construct($fields, $condition, $criteria, $parameter, $connector, $grouping, $date_field, $date_from, $date_to){
		$this->fields = $fields;
		$this->condition = $condition;
		$this->criteria = $criteria;
		$this->parameter = $parameter;
		$this->connector = $connector;
		$this->grouping = $grouping;
		$this->date_field = $date_field;
		$this->date_from = $date_from;
		$this->date_to = $date_to;
	}

	public function getDateField(){
		return $this->date_field;
	}

	public function getDateFrom(){
		return $this->date_from;
	}

	public function getDateTo(){
		return $this->date_to;
	}

	public function getConnector(){
		return $this->connector;
	}

	public function getGrouping(){
		return $this->grouping;
	}

	public function getFields(){
		return $this->fields;
	}

	public function getConsultants(){
		return $this->consultants;
	}

	public function getFrom(){
		return $this->from;
	}

	public function getTo(){
		return $this->to;
	}

	public function getCondition(){
		return $this->condition;
	}

	public function getCriteria(){
		return $this->criteria;
	}

	public function getParameter(){
		return $this->parameter;
	}
}
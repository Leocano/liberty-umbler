<?php 

class Timekeeping{
	private $id_timekeeping;
	private $id_ticket;
	private $id_user;
	private $id_timekeeping_type;
	private $date_timekeeping;
	private $desc_timekeeping;
	private $cost_timekeeping;
	private $hours;
	private $month;
	private $time_executed;

	public function __construct($id_timekeeping, $id_ticket, $id_user, $id_timekeeping_type, $date_timekeeping, $desc_timekeeping, $cost_timekeeping, $hours){
		$this->id_timekeeping = $id_timekeeping;
		$this->id_ticket = $id_ticket;
		$this->id_user = $id_user;
		$this->id_timekeeping_type = $id_timekeeping_type;
		$this->date_timekeeping = $date_timekeeping;
		$this->desc_timekeeping = $desc_timekeeping;
		$this->cost_timekeeping = $cost_timekeeping;
		$this->hours = $hours;
	}

	public function setTimeExecuted($time_executed){
		$this->time_executed = $time_executed;
	}

	public function getTimeExecuted(){
		return $this->time_executed;
	}

	public function setMonth($month){
		$this->month = $month;
	}

	public function getMonth(){
		return $this->month;
	}

	public function getHours(){
		return $this->hours;
	}

	public function getIdTimekeeping(){
		return $this->id_timekeeping;
	}

	public function getIdTicket(){
		return $this->id_ticket;
	}

	public function getIdUser(){
		return $this->id_user;
	}

	public function getIdTimekeepingType(){
		return $this->id_timekeeping_type;
	}

	public function getDateTimekeeping(){
		return $this->date_timekeeping;
	}

	public function getDescTimekeeping(){
		return $this->desc_timekeeping;
	}

	public function getCostTimekeeping(){
		return $this->cost_timekeeping;
	}
}
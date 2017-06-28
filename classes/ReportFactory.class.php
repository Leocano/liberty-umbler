<?php 

class ReportFactory{

	private $columns;
	private $from;
	private $where;
	private $results;
	private $grouping;
	private $sum;
	private $query;
	private $hour_pos;

	public function getSum(){
		return $this->sum;
	}

	public function getHourPos(){
		return $this->hour_pos;
	}

	public function getGrouping(){
		return $this->grouping;
	}

	public function getResults(){
		return $this->results;
	}

	public function getColumns(){
		return $this->columns;
	}

	public function getQuery(){
		return $this->query;
	}

	public function generateReport($report , $sum){
		$this->sum = $sum;
		$this->grouping = $report->getGrouping();

		//Montando os campos a serem selecionados
		$fields = $report->getFields();

		$index = 0;
		foreach ($fields as $field) {
			if ($field == "tb_timekeeping.hours"){
				$this->hour_pos = $index;
			}
			$index++;
		}

		$this->columns = $this->buildFields($fields);

		$condition = $report->getCondition();
		//Montando o From
		$date_field = $report->getDateField();
		$this->from = $this->buildFrom($fields, $condition, $date_field);

		//Montando o where
		$criteria = $report->getCriteria();
		$cond_value = $report->getParameter();
		$connector = $report->getConnector();
		$date_from = $report->getDateFrom();
		$date_to = $report->getDateTo();
		$this->where = $this->buildWhere($fields, $this->from, $condition, $criteria, $cond_value, $connector, $date_field, $date_from, $date_to);

		if ($this->grouping !== null){
			$order_by = " ORDER BY " . $this->grouping . " ASC";
		} else {
			$order_by = "";
		}

		$query = "SELECT " . $this->columns . " FROM " . $this->from . " WHERE" . $this->where . $order_by;

		$this->query = $query;

		$db = Database::getInstance();

		$db->query("$query");

		$this->results = $db->getResults();

		return $this;
	}

	private function buildFields($fields){
		$columns = implode(" , ", $fields);
		return $columns;
	}

	private function buildFrom($fields, $condition, $date_field){
		$table = array();

		foreach ($fields as $field) {
			$dummy = explode(".", $field);

			if (in_array($dummy[0], (array)$table) === false) {
				array_push($table, $dummy[0]);
			}
		}

		$dummy = explode(".", $date_field);
		if (in_array($dummy[0], (array)$table) === false){
			array_push($table, $dummy[0]);
		}

		if ($condition != null){
			foreach ($condition as $cond) {
				$dummy = explode(".", $cond);

				if (in_array($dummy[0], (array)$table) === false) {
					array_push($table, $dummy[0]);
				}
			}
		}

		$table = implode(" , ", $table);
		return $table;
	}

	private function buildWhere($fields , $table, $condition, $criteria, $cond_value, $connector, $date_field, $date_from, $date_to){

		$dummy = explode(" , ", $table);

		$where = $this->buildRelation($dummy);
		if ($connector != null){
			$where .= " (" . $this->buildParameters($condition, $criteria, $cond_value, $connector);
		}

		$lastSpacePosition = strrpos($where," ");
		$where = substr($where,0,$lastSpacePosition);

		if ($connector != null){
			$where .= ")";
		}

		$dummy = explode(".", $field);

		if ($date_field == "tb_timekeeping.current_month"){
			$where .= " AND MONTH(tb_timekeeping.date_timekeeping) = MONTH(CURRENT_DATE())"; 
		} else if($date_field == "tb_timekeeping.last_month"){
			$where .= " AND MONTH(tb_timekeeping.date_timekeeping) = (MONTH(CURRENT_DATE() - INTERVAL 1 MONTH))"; 
		} else {
			$where .= " AND " . $date_field . " BETWEEN '" . $date_from . "' AND '" . $date_to . " 23:59:59'";
		}

		return $where;
	}

	private function buildParameters($condition, $criteria, $cond_value, $connector){
		$total = count($condition) - 1;

		for($index = 0; $index <= $total; $index++){

			if ($criteria[$index] == "LIKE"){
				$cond_value[$index] = "%" . $cond_value[$index] . "%";
			}

			if($last_connector == "OR" && $last_condition == $condition[$index]){
				$group_parenthesis = "";
			} else {
				$group_parenthesis = " (";
			}
			
			if ($condition[$index + 1] != $condition[$index]){
				$end_parenthesis = ")";
			} else {
				$end_parenthesis = "";
			}

			$escaped_value = mysql_escape_string($cond_value[$index]);

			$parameters .= $group_parenthesis . " " . $condition[$index] . " " . $criteria[$index] . " '" . $escaped_value . "'" . $end_parenthesis . " " . $connector[$index];
			// $parameters .= " " . $condition[$index] . " " . $criteria[$index] . " '" . $escaped_value . "' " . $connector[$index];
		
			$last_connector = $connector[$index];
			$last_condition = $condition[$index];
		}

		return $parameters;
	}

	private function buildRelation($dummy){
		$db = Database::getInstance();

		$parameters = "'";
		$parameters .= implode("','", $dummy);
		$parameters .= "'";

		$query = "SELECT * FROM tb_relation_tables rel WHERE rel.name_table1 IN (" . $parameters . ") AND rel.name_table2 IN (" . $parameters . ") ";

		$db->query("$query");

		$results = $db->getResults();

		$repeat = array();
		foreach ($results as $result) {
			$where .= " " . $result->relation . " AND";

			if ($result->name_relation_table != null && !in_array($result->name_relation_table , $repeat)){

				$this->from .= "," . $result->name_relation_table;
				array_push($repeat, $result->name_relation_table);
			}
		}

		return $where;
	}

}
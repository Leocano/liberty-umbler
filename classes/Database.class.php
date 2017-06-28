<?php

// Database.class.php
// Arquivo para execução de queries no banco

class Database{
	private static $instance;
	private $connection;
	private $query;
	private $results;
	private $count;
	private $config;

	private function __construct(){
		$this->config = $GLOBALS['config'];
		try {
			$this->pdo = new PDO('mysql:host=' . $this->config['mysql']['host'] . ';dbname=' . $this->config['mysql']['db'], $this->config['mysql']['username'], $this->config['mysql']['password']);
		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}

	//cria uma nova instância ou retorna a instância já criada
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new Database;
		}
		return self::$instance;
	}

	//executa queries no banco de dados
	public function query($sql, $params = array()) {
		$this->query = $this->pdo->prepare($sql);

		if(count($params)){
			$counter = 1;
			foreach ($params as $param) {
				$this->query->bindValue($counter, $param);
				$counter++;
			}
		}

		$this->query->execute();
		$this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
		$this->count = $this->query->rowCount();

		return $this;

	}

	public function getResults(){
		return $this->results;
	}

	public function getCount() {
		return $this->count;
	}

	public function getLastInsertId(){
		return $this->pdo->lastInsertId();
	}
}
<?php 

class Company{
	private $id_company;
	private $name;
	private $is_customer;
	private $email;
	private $address;
	private $active;
	private $phone;
	private $cellphone;
	private $city;
	private $bairro;
	private $cep;
	private $contato_principal;

	public function __construct($id_company, $name, $is_customer, $email, $address, $active){
		$this->id_company = $id_company;
		$this->name = $name;
		$this->is_customer = $is_customer;
		$this->email = $email;
		$this->address = $address;
		$this->active = $active;
	}

	public function setContatoPrincipal($contato_principal){
		$this->contato_principal = $contato_principal;
	}

	public function getContatoPrincipal(){
		return $this->contato_principal;
	}

	public function setCep($cep){
		$this->cep = $cep;
	}

	public function getCep(){
		return $this->cep;
	}

	public function setBairro($bairro){
		$this->bairro = $bairro;
	}

	public function getBairro(){
		return $this->bairro;
	}

	public function setCity($city){
		$this->city = $city;
	}

	public function getCity(){
		return $this->city;
	}

	public function setCellphone($cellphone){
		$this->cellphone = $cellphone;
	}

	public function getCellphone(){
		return $this->cellphone;
	}

	public function setPhone($phone){
		$this->phone = $phone;
	}

	public function getPhone(){
		return $this->phone;
	}

	public function getIdCompany(){
		return $this->id_company;
	}

	public function getName(){
		return $this->name;
	}

	public function getEmail(){
		return $this->email;
	}

	public function getAddress(){
		return $this->address;
	}

	public function getIsCustomer(){
		return $this->is_customer;
	}
}
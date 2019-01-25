<?php 

namespace MVC\Model;

class Cliente extends Model {

	public function __construct(){
		$this->setTable('clientes');
	}

	public function addCliente($paciente){
		if($this->validate($paciente)){
			return $this->add($paciente);
		} 
		return false;
	}



	public function updCliente(array $cliente){
		if($this->validate($paciente)){
			return $this->upd($paciente);
		} 
		return false;
	}


	public function delClienteById(int $id){
		if($this->getOne($id)){
			return $this->delOneBy($id);
		}
		return false;
	}



}